<form action="{{ url('job-application') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
  @csrf
<div class="modal-body pt-5">
  <div class="row g-4">
    <div class="col-md-12">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Job Posting <span class="text-danger">*</span></label>
      <select class="form-select select2" id="jobs" name="job" required>
          <option value="">{{ __('Select Job') }}</option>
          @foreach($jobs as $key => $val)
              <option value="{{ $key }}">{{ $val }}</option>
          @endforeach
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Full Name <span class="text-danger">*</span></label>
      <input class="form-control" name="name" type="text" required placeholder="Enter candidate name">
    </div>
    <div class="col-md-6">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Email Address <span class="text-danger">*</span></label>
      <input class="form-control" name="email" type="email" required placeholder="Enter email address">
    </div>
    <div class="col-md-6">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Phone Number <span class="text-danger">*</span></label>
      <input class="form-control" name="phone" type="text" required placeholder="Enter phone number">
    </div>

    {{-- Dynamically shown based on job selection --}}
    <div class="col-md-6 dob d-none">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Date of Birth</label>
      <input class="form-control" name="dob" type="date" value="{{ old('dob') }}" autocomplete="off">
    </div>
    <div class="col-md-6 gender d-none">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Gender</label>
      <div class="d-flex gap-4 pt-2">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="gender" id="g_male" value="Male">
          <label class="form-check-label" for="g_male">Male</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="gender" id="g_female" value="Female">
          <label class="form-check-label" for="g_female">Female</label>
        </div>
      </div>
    </div>

    <div class="col-md-12 address d-none">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Street Address</label>
      <textarea class="form-control" name="address" rows="2" placeholder="Enter address"></textarea>
    </div>
    <div class="col-md-4 address d-none">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">City</label>
      <input class="form-control" name="city" type="text" placeholder="City">
    </div>
    <div class="col-md-4 address d-none">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">State</label>
      <input class="form-control" name="state" type="text" placeholder="State">
    </div>
    <div class="col-md-4 address d-none">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Zip Code</label>
      <input class="form-control" name="zip_code" type="text" placeholder="Zip Code">
    </div>

    <div class="col-md-6 profile d-none">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Profile Image</label>
      <div class="input-group">
        <input type="file" name="profile" class="form-control" id="profile_input" onchange="previewImage(this, 'profile_preview')">
      </div>
      <div class="mt-2 text-center">
        <img id="profile_preview" src="" style="max-height: 80px; display: none;" class="rounded border shadow-sm">
      </div>
    </div>

    <div class="col-md-6 resume d-none">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">CV / Resume</label>
      <div class="input-group">
        <input type="file" name="resume" class="form-control" id="resume_input">
      </div>
    </div>

    <div class="col-md-12 letter d-none">
      <label class="form-label fw-bold small text-muted text-uppercase mb-2">Cover Letter</label>
      <textarea class="form-control" name="cover_letter" rows="3" placeholder="Enter cover letter"></textarea>
    </div>

    @foreach ($questions as $question)
      <div class="col-md-12 question_{{ $question->id }} d-none">
        <label class="form-label fw-bold small text-muted text-uppercase mb-2">{{ $question->question }} @if($question->is_required == 'yes')<span class="text-danger">*</span>@endif</label>
        <input type="text" class="form-control" name="question[{{ $question->question }}]" {{ $question->is_required == 'yes' ? 'required' : '' }} placeholder="Enter answer">
      </div>
    @endforeach
  </div>
</div>
<div class="modal-footer border-top pt-4">
  <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
  <button type="submit" class="btn btn-primary px-6">Submit Application</button>
</div>
</form>

<script>
  function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#' + previewId).attr('src', e.target.result).show();
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  $(document).on('change', '#jobs', function() {
    var id = $(this).val();
    $.ajax({
      url: "{{ route('get.job.application') }}",
      type: 'POST',
      data: {
        "id": id,
        "_token": "{{ csrf_token() }}",
      },
      success: function(data) {
        var job = JSON.parse(data);
        var applicant = job.applicant || [];
        var visibility = job.visibility || [];
        var question = job.custom_question || [];

        // Reset visibility
        $('.dob, .gender, .address, .profile, .resume, .letter, [class*="question_"]').addClass('d-none');

        // Apply new visibility
        if (applicant.includes("dob")) $('.dob').removeClass('d-none');
        if (applicant.includes("gender")) $('.gender').removeClass('d-none');
        if (applicant.includes("address")) $('.address').removeClass('d-none');
        if (visibility.includes("profile")) $('.profile').removeClass('d-none');
        if (visibility.includes("resume")) $('.resume').removeClass('d-none');
        if (visibility.includes("letter")) $('.letter').removeClass('d-none');

        if (question.length > 0) {
          question.forEach(function(qid) {
            $('.question_' + qid).removeClass('d-none');
          });
        }
      }
    });
  });
</script>
