<?php

namespace App\Http\Controllers\tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Enums\UserAccountStatus;
use App\Models\User;
use App\Models\Team;
use App\Models\Designation;
use App\Models\BankAccount;
use App\Notifications\Onboarding\OnboardingStatusChanged;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Constants;

class OnboardingController extends Controller
{
    /**
     * Show the onboarding form to the user.
     */
    public function index()
    {
        $user = Auth::user();
        
        $userStatus = $user->status instanceof \UnitEnum ? $user->status->value : $user->status;

        // If already submitted, redirect to status page
        if ($userStatus === UserAccountStatus::ONBOARDING_SUBMITTED->value) {
            return redirect()->route('onboarding.status');
        }

        if ($userStatus !== UserAccountStatus::ONBOARDING->value) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.onboarding.form', [
            'user' => $user,
            'teams' => Team::all(),
            'designations' => Designation::all(),
            'pageConfigs' => ['myLayout' => 'blank'],
        ]);
    }

    /**
     * Store the onboarding submission.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Basic Validation (will expand this based on the form steps)
        $request->validate([
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'marital_status' => 'required|string',
            'blood_group' => 'required|string',
            'aadhaar_no' => 'required|string|max:20',
            'pan_no' => 'required|string|max:20',
            // Bank details
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:20',
            // Documents (Placeholder validation)
            'photo' => 'nullable|image|max:2048',
            'aadhaar_file' => 'required|file|mimes:pdf,jpg,png|max:5120',
            'pan_file' => 'required|file|mimes:pdf,jpg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $userData = $request->only([
                'first_name', 'last_name', 'dob', 'gender', 'blood_group',
                'father_name', 'mother_name', 'marital_status', 'spouse_name', 
                'no_of_children', 'citizenship', 'birth_country',
                'phone', 'alternate_number', 'home_phone',
                'perm_street', 'perm_building', 'perm_zip', 'perm_city', 'perm_state', 'perm_country',
                'temp_street', 'temp_building', 'temp_zip', 'temp_city', 'temp_state', 'temp_country',
                'aadhaar_no', 'pan_no', 'passport_no', 'passport_issue_date', 'passport_expiry_date',
                'visa_type', 'visa_issue_date', 'visa_expiry_date',
                'frro_registration', 'frro_issue_date', 'frro_expiry_date',
                'emergency_contact_name', 'emergency_contact_relation', 'emergency_contact_phone'
            ]);

            if ($request->has('same_as_permanent')) {
                $userData['temp_street'] = $userData['perm_street'] ?? null;
                $userData['temp_building'] = $userData['perm_building'] ?? null;
                $userData['temp_city'] = $userData['perm_city'] ?? null;
                $userData['temp_state'] = $userData['perm_state'] ?? null;
                $userData['temp_zip'] = $userData['perm_zip'] ?? null;
                $userData['temp_country'] = $userData['perm_country'] ?? null;
            }

            if (isset($userData['first_name']) && isset($userData['last_name'])) {
                $userData['name'] = $userData['first_name'] . ' ' . $userData['last_name'];
            }

            if ($request->has('consent_accepted')) {
                $userData['consent_accepted_at'] = now();
            }

            // Update User Profile
            $user->update($userData);

            // Update/Create Bank Account
            BankAccount::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'account_name' => $request->account_name ?? $user->name,
                    'bank_code' => $request->ifsc_code, // Assuming bank_code is IFSC
                    'branch_name' => $request->branch_name ?? 'N/A',
                    'branch_code' => $request->ifsc_code,
                ]
            );

            // Handle File Uploads (Photo, Cheque, Documents)
            $this->handleFileUploads($request, $user);

            // Set Status to Submitted
            $user->status = UserAccountStatus::ONBOARDING_SUBMITTED;
            $user->onboarding_completed_at = now();
            $user->consent_accepted_at = now();
            $user->save();

            DB::commit();

            return redirect()->route('onboarding.status')->with('success', 'Your onboarding details have been submitted for review.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Onboarding Submission Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again or contact HR.');
        }
    }

    /**
     * Show submission status.
     */
    public function status()
    {
        $user = Auth::user();
        return view('tenant.onboarding.status', ['user' => $user]);
    }

    /**
     * Handle onboarding file uploads.
     */
    private function handleFileUploads(Request $request, User $user)
    {
        $folder = Constants::BaseFolderOnboardingDocuments . $user->id;

        $files = [
            'photo' => 'profile_photo',
            'aadhaar_file' => 'aadhaar_card',
            'pan_file' => 'pan_card',
            'passport_file' => 'passport_document',
            'visa_file' => 'visa_document',
            'frro_file' => 'frro_document',
            'cheque_file' => 'cancelled_cheque',
            'matric_file' => 'matric_certificate',
            'inter_file' => 'inter_certificate',
            'graduation_file' => 'graduation_certificate',
        ];

        foreach ($files as $inputName => $fileNamePrefix) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $path = $file->storeAs($folder, $fileNamePrefix . '_' . time() . '.' . $file->getClientOriginalExtension(), 'public');
                
                // If it's the profile photo, update the user's profile_picture field
                if ($inputName === 'photo') {
                    $user->profile_picture = $path;
                    $user->save();
                }
                
                // We should also store these in the user_documents table if needed, 
                // but for now we are just saving them to the storage folder.
            }
        }
    }
}
