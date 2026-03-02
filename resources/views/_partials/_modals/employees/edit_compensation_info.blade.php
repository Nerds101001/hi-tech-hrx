<!-- Edit Compensation Information Modal -->
<div class="modal fade" id="offcanvasEditCompInfo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content modal-content-hitech">
      <div class="modal-header modal-header-hitech">
        <div class="d-flex align-items-center">
            <div class="modal-icon-header me-3">
                <i class="bx bx-dollar-circle"></i>
            </div>
            <h5 class="modal-title modal-title-hitech mb-0">@lang('Edit Compensation Information')</h5>
        </div>
        <button type="button" class="btn-close-hitech" data-bs-dismiss="modal">
          <i class="bx bx-x"></i>
        </button>
      </div>
      
      <div class="modal-body modal-body-hitech">
        <form action="{{route('employees.updateCompensationInfo')}}" method="POST">
          @csrf
          <input type="hidden" name="id" id="id" value="{{ $user->id }}">

          <div class="row g-4">
            <div class="col-md-6">
              <label class="form-label-hitech" for="baseSalary">@lang('Base Monthly Salary')</label>
              <input type="number" name="baseSalary" id="baseSalary" class="form-control form-control-hitech" placeholder="0.00" value="{{$user->base_salary}}" />
            </div>
            
            <div class="col-md-6">
              <label class="form-label-hitech" for="availableLeaveCount">@lang('Available Leave Count')</label>
              <input type="number" name="availableLeaveCount" id="availableLeaveCount" class="form-control form-control-hitech" placeholder="0" value="{{$user->available_leave_count}}" />
            </div>
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
<!-- /Edit Compensation Information Modal -->
