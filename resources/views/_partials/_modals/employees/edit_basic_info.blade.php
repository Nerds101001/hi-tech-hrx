@php use App\Enums\Gender;use Carbon\Carbon; @endphp
<!-- Edit Basic Info Modal -->
<div class="modal fade" id="offcanvasEditBasicInfo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Increased to LG for more fields -->
    <div class="modal-content modal-content-hitech">
      <div class="modal-header modal-header-hitech">
        <div class="d-flex align-items-center">
            <div class="modal-icon-header me-3">
                <i class="bx bx-user"></i>
            </div>
            <h5 class="modal-title modal-title-hitech mb-0">@lang('Edit Full Profile Details')</h5>
        </div>
        <button type="button" class="btn-close-hitech" data-bs-dismiss="modal">
          <i class="bx bx-x"></i>
        </button>
      </div>
      
      <div class="modal-body modal-body-hitech">
        <form id="basicInfoForm" action="{{route('employees.updateBasicInfo')}}" method="POST">
          @csrf
          <input type="hidden" name="id" id="id" value="{{ $user->id }}">
          
          <!-- SECTION 1: PERSONAL INFORMATION -->
          <div class="d-flex align-items-center mb-3 mt-2">
            <div class="p-1 me-2 rounded" style="background:#E6F4F1;"><i class="bx bx-user-circle" style="color:#127464;"></i></div>
            <h6 class="mb-0 fw-bold text-uppercase small" style="color:#127464; font-size:0.72rem; letter-spacing:0.08em;">Personal Information</h6>
          </div>
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <label class="form-label-hitech" for="firstName">@lang('First Name')<span class="text-danger ms-1">*</span></label>
              <input type="text" class="form-control form-control-hitech" id="firstName" name="firstName" value="{{ $user->first_name }}" required />
            </div>
            <div class="col-md-4">
              <label class="form-label-hitech" for="lastName">@lang('Last Name')<span class="text-danger ms-1">*</span></label>
              <input type="text" class="form-control form-control-hitech" id="lastName" name="lastName" value="{{ $user->last_name }}" required />
            </div>
            <div class="col-md-4">
              <label class="form-label-hitech" for="dob">Date of Birth<span class="text-danger ms-1">*</span></label>
              <input type="date" name="dob" id="dob" class="form-control form-control-hitech" value="{{ $user->dob ? Carbon::parse($user->dob)->format('Y-m-d') : '' }}" required />
            </div>
            <div class="col-md-4">
              <label class="form-label-hitech" for="gender">Gender<span class="text-danger ms-1">*</span></label>
              <select class="form-select form-select-hitech" id="gender" name="gender" required>
                <option value="">Select Gender</option>
                @foreach(Gender::cases() as $gender)
                  <option value="{{$gender->value}}" {{$user->gender == $gender->value ? 'selected':''}} >{{ucfirst($gender->value)}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label-hitech" for="blood_group">Blood Group</label>
              <input type="text" class="form-control form-control-hitech" id="blood_group" name="blood_group" value="{{ $user->blood_group }}" placeholder="e.g. O+" />
            </div>
            <div class="col-md-4">
              <label class="form-label-hitech" for="maritalStatus">Marital Status</label>
              <select class="form-select form-select-hitech" id="maritalStatus" name="marital_status">
                <option value="single" {{ $user->marital_status == 'single' ? 'selected' : '' }}>Single</option>
                <option value="married" {{ $user->marital_status == 'married' ? 'selected' : '' }}>Married</option>
                <option value="divorced" {{ $user->marital_status == 'divorced' ? 'selected' : '' }}>Divorced</option>
                <option value="widowed" {{ $user->marital_status == 'widowed' ? 'selected' : '' }}>Widowed</option>
              </select>
            </div>
          </div>

          <!-- SECTION 2: FAMILY & ORIGIN -->
          <div class="d-flex align-items-center mb-3">
            <div class="p-1 me-2 rounded" style="background:#E6F4F1;"><i class="bx bx-group" style="color:#127464;"></i></div>
            <h6 class="mb-0 fw-bold text-uppercase small" style="color:#127464; font-size:0.72rem; letter-spacing:0.08em;">Family &amp; Nationality</h6>
          </div>
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <label class="form-label-hitech">Father's Name</label>
              <input type="text" name="father_name" class="form-control form-control-hitech" value="{{ $user->father_name }}" />
            </div>
            <div class="col-md-4">
              <label class="form-label-hitech">Mother's Name</label>
              <input type="text" name="mother_name" class="form-control form-control-hitech" value="{{ $user->mother_name }}" />
            </div>
            <div class="col-md-4" id="marriedDiv">
              <label class="form-label-hitech">Spouse Name</label>
              <input type="text" name="spouse_name" class="form-control form-control-hitech" value="{{ $user->spouse_name }}" />
            </div>
            <div class="col-md-4">
              <label class="form-label-hitech">No. of Children</label>
              <input type="number" name="no_of_children" class="form-control form-control-hitech" value="{{ $user->no_of_children }}" min="0" />
            </div>
            <div class="col-md-4">
              <label class="form-label-hitech">Birth Country</label>
              <input type="text" name="birth_country" class="form-control form-control-hitech" value="{{ $user->birth_country }}" />
            </div>
            <div class="col-md-4">
              <label class="form-label-hitech">Citizenship</label>
              <input type="text" name="citizenship" class="form-control form-control-hitech" value="{{ $user->citizenship }}" />
            </div>
          </div>

          {{-- Hidden fields so controller validation still passes --}}
          <input type="hidden" name="email" value="{{ $user->email }}">
          <input type="hidden" name="phone" value="{{ $user->phone }}">
          <input type="hidden" name="altPhone" value="{{ $user->alternate_number }}">


          <div class="modal-footer border-0 px-0 pb-0 pt-4 d-flex justify-content-end gap-3">
            <button type="button" class="btn btn-hitech-modal-cancel px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-hitech-modal-submit px-5">
              Save Basic Info <i class="bx bx-check-circle ms-1"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- /Edit Basic Info Modal -->
