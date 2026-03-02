<form action="{{ route('interview-schedule.update', $interviewSchedule->id) }}" method="POST" class="needs-validation" novalidate>
  @csrf
  @method('PUT')
<div class="modal-body pt-5">
  <div class="row g-4">
    <div class="col-md-6">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Candidate <span class="text-danger">*</span></label>
      <select class="form-select select2" name="candidate" required>
          <option value="">{{ __('Select Candidate') }}</option>
          @foreach($candidates as $key => $val)
              <option value="{{ $key }}" {{ $interviewSchedule->candidate == $key ? 'selected' : '' }}>{{ $val }}</option>
          @endforeach
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Interviewer <span class="text-danger">*</span></label>
      <select class="form-select select2" name="employee" required>
          <option value="">{{ __('Select Interviewer') }}</option>
          @foreach($employees as $key => $val)
              <option value="{{ $key }}" {{ $interviewSchedule->employee == $key ? 'selected' : '' }}>{{ $val }}</option>
          @endforeach
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Interview Date <span class="text-danger">*</span></label>
      <input class="form-control" name="date" type="date" value="{{ $interviewSchedule->date }}" required>
    </div>
    <div class="col-md-6">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Interview Time <span class="text-danger">*</span></label>
      <input class="form-control" name="time" type="time" value="{{ $interviewSchedule->time }}" required>
    </div>
    <div class="col-md-12">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Comment / Notes</label>
      <textarea class="form-control" name="comment" rows="3" placeholder="Additional instructions for the interview...">{{ $interviewSchedule->comment }}</textarea>
    </div>
  </div>
</div>
<div class="modal-footer border-top pt-4">
  <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
  <button type="submit" class="btn btn-primary px-6">Update Schedule</button>
</div>
</form>
