<div class="modal fade" id="modalAddOrUpdateShift" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-hitech">
            <div class="modal-header modal-header-hitech">
                <div class="d-flex align-items-center">
                    <div class="modal-icon-header me-3">
                        <i class="bx bx-time-five fs-3"></i>
                    </div>
                    <h5 class="modal-title modal-title-hitech" id="modalShiftLabel">Add Shift</h5>
                </div>
                <button type="button" class="btn-close-hitech" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form class="add-edit-shift-form" id="shiftForm" onsubmit="return false;">
                <div class="modal-body modal-body-hitech">
                    @csrf
                    <input type="hidden" name="_method" id="shiftMethod" value="POST">
                    <input type="hidden" name="id" id="shift_id" value="">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label-hitech" for="shiftName">Shift Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-hitech" id="shiftName" placeholder="e.g., General Shift" name="name" required />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-hitech" for="shiftCode">Shift Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-hitech" id="shiftCode" placeholder="e.g., GS01" name="code" required />
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="startTime" class="form-label-hitech">Start Time <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-hitech flatpickr-input" placeholder="HH:MM" id="startTime" name="start_time" required readonly="readonly" />
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="endTime" class="form-label-hitech">End Time <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-hitech flatpickr-input" placeholder="HH:MM" id="endTime" name="end_time" required readonly="readonly" />
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label-hitech d-block mb-3">Working Days</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="checkbox" value="1" id="{{ $day }}Toggle" name="{{ $day }}">
                                        <label class="form-check-label text-white-50 small" for="{{ $day }}Toggle"> {{ ucfirst($day) }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label-hitech" for="shiftNotes">Notes</label>
                            <textarea class="form-control form-control-hitech" id="shiftNotes" name="notes" rows="2" placeholder="Optional notes..."></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12">
                            <small class="text-danger general-error-message"></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="reset" class="btn btn-label-secondary px-4 h-px-45 d-flex align-items-center" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-hitech px-5 h-px-45 d-flex align-items-center data-submit" id="submitShiftBtn">
                        <span>Submit</span>
                        <i class="bx bx-check-circle ms-2 fs-5"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
