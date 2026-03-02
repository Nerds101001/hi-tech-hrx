<form action="{{ route('job-stage.update', $jobStage->id) }}" method="POST" class="needs-validation" novalidate>
  @csrf
  @method('PUT')
<div class="modal-body pt-5">
  <div class="row g-4">
    <div class="col-md-12">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Stage Title <span class="text-danger">*</span></label>
      <input class="form-control" name="title" type="text" value="{{ $jobStage->title }}" placeholder="Enter stage title" required>
    </div>
  </div>
</div>
<div class="modal-footer border-top pt-4">
  <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
  <button type="submit" class="btn btn-primary px-6">Update Stage</button>
</div>
</form>
