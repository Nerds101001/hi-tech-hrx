@php use Carbon\Carbon; @endphp
<!-- Edit Work Information Modal -->
<div class="modal fade" id="offcanvasEditWorkInfo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content modal-content-hitech">
      <div class="modal-header modal-header-hitech">
        <div class="d-flex align-items-center">
            <div class="modal-icon-header me-3">
                <i class="bx bx-briefcase"></i>
            </div>
            <h5 class="modal-title modal-title-hitech mb-0">@lang('Edit Work Information')</h5>
        </div>
        <button type="button" class="btn-close-hitech" data-bs-dismiss="modal">
          <i class="bx bx-x"></i>
        </button>
      </div>
      
      <div class="modal-body modal-body-hitech">
        <form id="workInfoForm" action="{{route('employees.updateWorkInformation')}}" method="POST">
          @csrf
          <input type="hidden" name="id" id="id" value="{{ $user->id }}">

          <div class="row g-4">
            <div class="col-md-6">
              <label class="form-label-hitech" for="designationId">@lang('Designation') <span class="text-danger">*</span></label>
              <select class="form-select form-select-hitech select2" id="designationId" name="designationId" required>
                <option value="">Select Designation</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label-hitech" for="role">@lang('Role') <span class="text-danger">*</span></label>
              <select class="form-select form-select-hitech select2" id="role" name="role" required>
                <option value="">Select Role</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label-hitech" for="teamId">@lang('Team') <span class="text-danger">*</span></label>
              <select class="form-select form-select-hitech select2" id="teamId" name="teamId" required>
                <option value="">Select Team</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label-hitech" for="shiftId">@lang('Shift') <span class="text-danger">*</span></label>
              <select class="form-select form-select-hitech select2" id="shiftId" name="shiftId" required>
                <option value="">Select Shift</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label-hitech" for="reportingToId">@lang('Reporting To') <span class="text-danger">*</span></label>
              <select class="form-select form-select-hitech select2" id="reportingToId" name="reportingToId" required>
                <option value="">Select Reporting To</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label-hitech" for="doj">Date of Joining <span class="text-danger">*</span></label>
              <input type="date" name="doj" id="doj" class="form-control form-control-hitech" required
                     value="{{ $user->date_of_joining != null ? Carbon::parse($user->date_of_joining)->format('Y-m-d') : '' }}"/>
            </div>

            <div class="col-12">
                <label class="form-label-hitech" for="attendanceType">@lang('Attendance Type') <span class="text-danger">*</span></label>
                <select class="form-select form-select-hitech" id="attendanceType" name="attendanceType" required>
                    <option value="open" {{ $user->attendance_type == 'open' ? 'selected' : '' }}>Open (Anywhere)</option>
                    @if($addonService->isAddonEnabled(ModuleConstants::GEOFENCE))
                        <option value="geofence" {{ $user->attendance_type == 'geofence' ? 'selected' : '' }}>Geofence Restricted</option>
                    @endif
                    @if($addonService->isAddonEnabled(ModuleConstants::IP_ADDRESS_ATTENDANCE))
                        <option value="ipAddress" {{ $user->attendance_type == 'ip_address' ? 'selected' : '' }}>IP Address Restricted</option>
                    @endif
                    @if($addonService->isAddonEnabled(ModuleConstants::QR_ATTENDANCE))
                        <option value="staticqr" {{ $user->attendance_type == 'qr_code' ? 'selected' : '' }}>Static QR Code</option>
                    @endif
                    @if($addonService->isAddonEnabled(ModuleConstants::DYNAMIC_QR_ATTENDANCE))
                        <option value="dynamicqr" {{ $user->attendance_type == 'dynamic_qr' ? 'selected' : '' }}>Dynamic QR Code</option>
                    @endif
                    @if($addonService->isAddonEnabled(ModuleConstants::SITE_ATTENDANCE))
                        <option value="site" {{ $user->attendance_type == 'site' ? 'selected' : '' }}>Site Restricted</option>
                    @endif
                    @if($addonService->isAddonEnabled(ModuleConstants::FACE_ATTENDANCE))
                        <option value="face" {{ $user->attendance_type == 'face_recognition' ? 'selected' : '' }}>Face Recognition</option>
                    @endif
                </select>
            </div>
          </div>

          <!-- Dynamic Attendance Settings Groups -->
          <div id="geofenceGroupDiv" class="mt-4" style="display:none;">
            <label class="form-label-hitech">Geofence Group</label>
            <select id="geofenceGroupId" name="geofenceGroupId" class="form-select form-select-hitech"></select>
          </div>

          <div id="ipGroupDiv" class="mt-4" style="display:none;">
            <label class="form-label-hitech">IP Group</label>
            <select id="ipGroupId" name="ipGroupId" class="form-select form-select-hitech"></select>
          </div>

          <div id="qrGroupDiv" class="mt-4" style="display:none;">
            <label class="form-label-hitech">QR Group</label>
            <select id="qrGroupId" name="qrGroupId" class="form-select form-select-hitech"></select>
          </div>

          <div id="dynamicQrDiv" class="mt-4" style="display:none;">
            <label class="form-label-hitech">QR Device</label>
            <select id="dynamicQrId" name="dynamicQrId" class="form-select form-select-hitech"></select>
          </div>

          <div id="siteDiv" class="mt-4" style="display:none;">
            <label class="form-label-hitech">Site</label>
            <select id="siteId" name="siteId" class="form-select form-select-hitech"></select>
          </div>

          <div class="modal-footer border-0 px-0 pb-0 pt-4 d-flex justify-content-end gap-3">
            <button type="button" class="btn btn-hitech-modal-cancel" data-bs-dismiss="modal">@lang('Cancel')</button>
            <button type="submit" class="btn btn-hitech-modal-submit">
              @lang('Save Changes') <i class="bx bx-check-circle ms-1"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /Edit Work Information Modal -->
