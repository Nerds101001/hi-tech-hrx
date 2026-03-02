<form action="{{ url('interview-schedule') }}" method="POST" class="needs-validation" novalidate>
  @csrf
<div class="modal-body pt-5">
  <div class="row g-4">
    <div class="col-md-6">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Candidate <span class="text-danger">*</span></label>
      <select class="form-select select2" name="candidate" required>
          <option value="">{{ __('Select Candidate') }}</option>
          @foreach($candidates as $key => $val)
              <option value="{{ $key }}">{{ $val }}</option>
          @endforeach
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Interviewer <span class="text-danger">*</span></label>
      <select class="form-select select2" name="employee" required>
          <option value="">{{ __('Select Interviewer') }}</option>
          @foreach($employees as $key => $val)
              <option value="{{ $key }}">{{ $val }}</option>
          @endforeach
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Interview Date <span class="text-danger">*</span></label>
      <input class="form-control" name="date" type="date" value="{{ date('Y-m-d') }}" required>
    </div>
    <div class="col-md-6">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Interview Time <span class="text-danger">*</span></label>
      <input class="form-control" name="time" type="time" value="{{ date('H:i') }}" required>
    </div>
    <div class="col-md-12">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Comment / Notes</label>
      <textarea class="form-control" name="comment" rows="3" placeholder="Additional instructions for the interview..."></textarea>
    </div>
  </div>
</div>
<div class="modal-footer border-top pt-4">
  <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
  <button type="submit" class="btn btn-primary px-6">Schedule Interview</button>
</div>
</form>

@if($candidate != 0)
<script>
  $(document).ready(function() {
    $('select[name="candidate"]').val({{ $candidate }}).trigger('change');
  });
</script>
@endif
