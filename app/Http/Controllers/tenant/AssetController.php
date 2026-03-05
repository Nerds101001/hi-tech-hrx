<?php

namespace App\Http\Controllers\tenant;

use App\ApiClasses\Error;
use App\ApiClasses\Success;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AssetController extends Controller
{
    public function index()
    {
        try {
            $assets = \App\Models\Asset::with(['category', 'assignedUser', 'createdBy'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            $categories = AssetCategory::where('status', 'active')
                ->orderBy('name')
                ->get();

            $stats = [
                'total' => \App\Models\Asset::count(),
                'available' => \App\Models\Asset::where('status', 'available')->count(),
                'assigned' => \App\Models\Asset::where('status', 'assigned')->count(),
                'maintenance' => \App\Models\Asset::where('status', 'maintenance')->count(),
                'retired' => \App\Models\Asset::where('status', 'retired')->count(),
                'total_value' => \App\Models\Asset::sum('current_value'),
            ];

            return view('tenant.assets.index', [
                'pageConfigs' => ['contentLayout' => 'wide'],
                'assets' => $assets,
                'categories' => $categories,
                'stats' => $stats
            ]);

        } catch (Exception $e) {
            Log::error('Asset index error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to load assets. Please try again.');
        }
    }

    public function create()
    {
        try {
            $categories = AssetCategory::where('status', 'active')
                ->orderBy('name')
                ->get();
                
            $users = User::where('status', 'active')
                ->orderBy('first_name')
                ->get(['id', 'first_name', 'last_name']);

            return view('tenant.assets.create', compact('categories', 'users'));

        } catch (Exception $e) {
            Log::error('Asset create form error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to load asset creation form. Please try again.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'asset_code' => 'required|string|max:50|unique:assets',
                'category_id' => 'required|exists:asset_categories,id',
                'assigned_to' => 'nullable|exists:users,id',
                'purchase_date' => 'required|date',
                'purchase_cost' => 'required|numeric|min:0',
                'current_value' => 'required|numeric|min:0',
                'status' => 'required|in:available,assigned,maintenance,retired',
                'location' => 'nullable|string|max:255',
                'serial_number' => 'nullable|string|max:100',
                'brand' => 'nullable|string|max:100',
                'model' => 'nullable|string|max:100',
                'warranty_expiry' => 'nullable|date|after:purchase_date',
                'description' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $asset = \App\Models\Asset::create([
                'name' => $request->name,
                'asset_code' => $request->asset_code,
                'category_id' => $request->category_id,
                'assigned_to' => $request->assigned_to,
                'purchase_date' => $request->purchase_date,
                'purchase_cost' => $request->purchase_cost,
                'current_value' => $request->current_value,
                'status' => $request->status,
                'location' => $request->location,
                'serial_number' => $request->serial_number,
                'brand' => $request->brand,
                'model' => $request->model,
                'warranty_expiry' => $request->warranty_expiry,
                'description' => $request->description,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('assets.index')
                ->with('success', 'Asset created successfully!');

        } catch (Exception $e) {
            Log::error('Asset creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create asset. Please try again.');
        }
    }

    public function show($id)
    {
        $asset = \App\Models\Asset::with(['category', 'assignedUser', 'createdBy', 'maintenanceRecords'])
            ->findOrFail($id);

        return view('tenant.assets.show', compact('asset'));
    }

    public function edit($id)
    {
        $asset = \App\Models\Asset::findOrFail($id);
        $categories = AssetCategory::where('status', Status::ACTIVE)
            ->orderBy('name')
            ->get();
        $users = User::where('status', Status::ACTIVE)
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name']);

        return view('tenant.assets.edit', compact('asset', 'categories', 'users'));
    }

    public function update(Request $request, $id)
    {
        try {
            $asset = \App\Models\Asset::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'asset_code' => 'required|string|max:50|unique:assets,asset_code,' . $id,
                'category_id' => 'required|exists:asset_categories,id',
                'assigned_to' => 'nullable|exists:users,id',
                'purchase_date' => 'required|date',
                'purchase_cost' => 'required|numeric|min:0',
                'current_value' => 'required|numeric|min:0',
                'status' => 'required|in:available,assigned,maintenance,retired',
                'location' => 'nullable|string|max:255',
                'serial_number' => 'nullable|string|max:100',
                'brand' => 'nullable|string|max:100',
                'model' => 'nullable|string|max:100',
                'warranty_expiry' => 'nullable|date|after:purchase_date',
                'description' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $asset->update([
                'name' => $request->name,
                'asset_code' => $request->asset_code,
                'category_id' => $request->category_id,
                'assigned_to' => $request->assigned_to,
                'purchase_date' => $request->purchase_date,
                'purchase_cost' => $request->purchase_cost,
                'current_value' => $request->current_value,
                'status' => $request->status,
                'location' => $request->location,
                'serial_number' => $request->serial_number,
                'brand' => $request->brand,
                'model' => $request->model,
                'warranty_expiry' => $request->warranty_expiry,
                'description' => $request->description,
            ]);

            return redirect()->route('assets.index')
                ->with('success', 'Asset updated successfully!');

        } catch (Exception $e) {
            Log::error('Asset update failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update asset. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $asset = \App\Models\Asset::findOrFail($id);
            $asset->delete();

            return redirect()->route('assets.index')
                ->with('success', 'Asset deleted successfully!');

        } catch (Exception $e) {
            Log::error('Asset deletion failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete asset. Please try again.');
        }
    }

    public function getListAjax(Request $request)
    {
        $query = \App\Models\Asset::with(['category', 'assignedUser']);

        if ($request->has('searchTerm') && !empty($request->searchTerm)) {
            $search = $request->searchTerm;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('asset_code', 'like', "%{$search}%")
                    ->orWhereHas('category', function($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return DataTables::of($query)
            ->addColumn('action', function ($asset) {
                $editBtn = '';
                $deleteBtn = '';
                
                if (auth()->user()->hasRole(['admin', 'hr'])) {
                    $editBtn = '<button class="btn btn-sm btn-icon edit-record" onclick="editAsset(' . $asset->id . ')"><i class="bx bx-pencil"></i></button>';
                    $deleteBtn = '<button class="btn btn-sm btn-icon delete-record" onclick="deleteAsset(' . $asset->id . ')"><i class="bx bx-trash text-danger"></i></button>';
                }

                return '<div class="d-flex align-items-center justify-content-center gap-2">' . $editBtn . $deleteBtn . '</div>';
            })
            ->addColumn('status_badge', function ($asset) {
                return '<span class="badge ' . $asset->status_badge . ' rounded-pill">' . ucfirst($asset->status) . '</span>';
            })
            ->addColumn('assigned_user', function ($asset) {
                return $asset->assignedUser ? $asset->assignedUser->first_name . ' ' . $asset->assignedUser->last_name : '<span class="text-muted">Unassigned</span>';
            })
            ->addColumn('category_name', function ($asset) {
                return $asset->category ? $asset->category->name : '<span class="text-muted">N/A</span>';
            })
            ->addColumn('formatted_purchase_cost', function ($asset) {
                return '₹' . number_format($asset->purchase_cost, 2);
            })
            ->addColumn('formatted_current_value', function ($asset) {
                return '₹' . number_format($asset->current_value, 2);
            })
            ->rawColumns(['action', 'status_badge', 'assigned_user', 'category_name'])
            ->make(true);
    }

    public function assignAsset(Request $request, $id)
    {
        try {
            $asset = \App\Models\Asset::findOrFail($id);
            $asset->update([
                'assigned_to' => $request->user_id,
                'status' => 'assigned',
            ]);

            return response()->json(['success' => true, 'message' => 'Asset assigned successfully!']);

        } catch (Exception $e) {
            Log::error('Asset assignment failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to assign asset.'], 500);
        }
    }

    public function unassignAsset($id)
    {
        try {
            $asset = \App\Models\Asset::findOrFail($id);
            $asset->update([
                'assigned_to' => null,
                'status' => 'available',
            ]);

            return response()->json(['success' => true, 'message' => 'Asset unassigned successfully!']);

        } catch (Exception $e) {
            Log::error('Asset unassignment failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to unassign asset.'], 500);
        }
    }
}
