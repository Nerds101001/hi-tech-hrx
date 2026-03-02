<form action="{{ url('custom-question') }}" method="POST" class="needs-validation" novalidate>
  @csrf
<div class="modal-body pt-5">
  <div class="row g-4">
    <div class="col-md-12">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Question <span class="text-danger">*</span></label>
      <input class="form-control" name="question" type="text" placeholder="Enter interview question" required>
    </div>
    <div class="col-md-12">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Is Required? <span class="text-danger">*</span></label>
      <select class="form-select select2" id="is_required" name="is_required" required>
          @foreach($is_required as $key => $val)
              <option value="{{ $key }}">{{ $val }}</option>
          @endforeach
      </select>
    </div>
  </div>
</div>
<div class="modal-footer border-top pt-4">
  <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
  <button type="submit" class="btn btn-primary px-6">Create Question</button>
</div>
</form>
