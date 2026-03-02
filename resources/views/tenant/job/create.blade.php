@extends('layouts/layoutMaster')

@section('title', 'Create Job')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/quill/typography.scss',
    'resources/assets/vendor/libs/quill/katex.scss',
    'resources/assets/vendor/libs/quill/editor.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/tagify/tagify.scss',
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/quill/katex.js',
    'resources/assets/vendor/libs/quill/quill.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/tagify/tagify.js',
    'resources/assets/vendor/libs/flatpickr/flatpickr.js'
  ])
@endsection

@section('page-script')
  <script>
    $(document).ready(function() {
      // Initialize Flatpickr
      $('.flatpickr-date').flatpickr({
        dateFormat: 'Y-m-d',
        defaultDate: 'today'
      });

      // Initialize Tagify for Skills
      const skillEl = document.querySelector('#skill');
      if (skillEl) {
        new Tagify(skillEl);
      }

      // Initialize Quill Editors
      const quillDescription = new Quill('#description-editor', {
        theme: 'snow',
        modules: {
          toolbar: [
            [{ header: [1, 2, false] }],
            ['bold', 'italic', 'underline'],
            ['image', 'code-block']
          ]
        }
      });
      quillDescription.on('text-change', function() {
        document.getElementById('description').value = quillDescription.root.innerHTML;
      });

      const quillRequirement = new Quill('#requirement-editor', {
        theme: 'snow',
        modules: {
          toolbar: [
            [{ header: [1, 2, false] }],
            ['bold', 'italic', 'underline'],
            ['image', 'code-block']
          ]
        }
      });
      quillRequirement.on('text-change', function() {
        document.getElementById('requirement').value = quillRequirement.root.innerHTML;
      });

      const quillTerms = new Quill('#terms-editor', {
        theme: 'snow',
        modules: {
          toolbar: [
            [{ header: [1, 2, false] }],
            ['bold', 'italic', 'underline'],
            ['image', 'code-block']
          ]
        }
      });
      quillTerms.on('text-change', function() {
        document.getElementById('terms_and_conditions').value = quillTerms.root.innerHTML;
      });

      // Handle Terms and Conditions visibility
      const checkTerms = document.getElementById('check-terms');
      const termsWrapper = document.getElementById('terms-wrapper');
      if (checkTerms && termsWrapper) {
        checkTerms.addEventListener('change', function() {
          termsWrapper.style.display = this.checked ? 'block' : 'none';
        });
      }
    });
  </script>
@endsection

@section('content')
<div class="layout-full-width animate__animated animate__fadeIn">
  <div class="d-flex justify-content-between align-items-center mb-6 px-4">
    <h3 class="mb-0 fw-bold text-heading" style="font-size: 1.5rem;">Create New Job</h3>
    <a href="{{ route('job.index') }}" class="btn btn-label-secondary shadow-sm">
      <i class="bx bx-chevron-left me-1"></i>Back to List
    </a>
  </div>

  <div class="px-4">
    <form action="{{ route('job.store') }}" method="post" class="needs-validation" novalidate>
    @csrf
    <div class="row g-6">
      <div class="col-md-6">
        <div class="hitech-card-white mb-6">
          <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Job Information</h5>
          </div>
          <div class="card-body pt-5">
            <div class="row g-4">
              <div class="col-12">
                <label class="form-label fw-bold small text-muted text-uppercase mb-2">Job Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" required placeholder="Enter job title">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold small text-muted text-uppercase mb-2">Branch / Site <span class="text-danger">*</span></label>
                <select name="branch" class="form-select select2" required>
                  <option value="">Select Branch</option>
                  @foreach($branches as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold small text-muted text-uppercase mb-2">Job Category <span class="text-danger">*</span></label>
                <select name="category" class="form-select select2" required>
                  @foreach($categories as $id => $title)
                    <option value="{{ $id }}">{{ $title }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold small text-muted text-uppercase mb-2">No. of Positions <span class="text-danger">*</span></label>
                <input type="number" name="position" class="form-control" required placeholder="Enter number of positions">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold small text-muted text-uppercase mb-2">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select select2" required>
                  @foreach($status as $key => $val)
                    <option value="{{ $key }}">{{ $val }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold small text-muted text-uppercase mb-2">Start Date</label>
                <input type="text" name="start_date" class="form-control flatpickr-date" autocomplete="off">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold small text-muted text-uppercase mb-2">End Date</label>
                <input type="text" name="end_date" class="form-control flatpickr-date" autocomplete="off">
              </div>
              <div class="col-12">
                <label class="form-label fw-bold small text-muted text-uppercase mb-2">Skills <span class="text-danger">*</span></label>
                <input id="skill" name="skill" class="form-control" required placeholder="Enter skills">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="hitech-card-white mb-6" style="height: calc(100% - 1.5rem);">
          <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Application Requirements</h5>
          </div>
          <div class="card-body pt-5">
            <div class="row g-4">
              <div class="col-md-6">
                <h6 class="fw-bold small text-muted text-uppercase mb-4">Need to Ask?</h6>
                <div class="d-flex flex-column gap-3">
                  <div class="form-check custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="check-gender">
                      <input class="form-check-input" type="checkbox" name="applicant[]" value="gender" id="check-gender">
                      <span class="custom-option-header">
                        <span class="h6 mb-0">Gender</span>
                      </span>
                    </label>
                  </div>
                  <div class="form-check custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="check-dob">
                      <input class="form-check-input" type="checkbox" name="applicant[]" value="dob" id="check-dob">
                      <span class="custom-option-header">
                        <span class="h6 mb-0">Date of Birth</span>
                      </span>
                    </label>
                  </div>
                  <div class="form-check custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="check-address">
                      <input class="form-check-input" type="checkbox" name="applicant[]" value="address" id="check-address">
                      <span class="custom-option-header">
                        <span class="h6 mb-0">Address</span>
                      </span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <h6 class="fw-bold small text-muted text-uppercase mb-4">Show Options?</h6>
                <div class="d-flex flex-column gap-3">
                  <div class="form-check custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="check-profile">
                      <input class="form-check-input" type="checkbox" name="visibility[]" value="profile" id="check-profile">
                      <span class="custom-option-header">
                        <span class="h6 mb-0">Profile Image</span>
                      </span>
                    </label>
                  </div>
                  <div class="form-check custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="check-resume">
                      <input class="form-check-input" type="checkbox" name="visibility[]" value="resume" id="check-resume">
                      <span class="custom-option-header">
                        <span class="h6 mb-0">Resume</span>
                      </span>
                    </label>
                  </div>
                  <div class="form-check custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="check-letter">
                      <input class="form-check-input" type="checkbox" name="visibility[]" value="letter" id="check-letter">
                      <span class="custom-option-header">
                        <span class="h6 mb-0">Cover Letter</span>
                      </span>
                    </label>
                  </div>
                  <div class="form-check custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="check-terms">
                      <input class="form-check-input" type="checkbox" name="visibility[]" value="terms" id="check-terms">
                      <span class="custom-option-header">
                        <span class="h6 mb-0">Terms & Conditions</span>
                      </span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-12 mt-4 pt-4 border-top">
                <h6 class="fw-bold small text-muted text-uppercase mb-4">Custom Questions</h6>
                <div class="row g-3">
                  @foreach ($customQuestion as $question)
                    <div class="col-md-6">
                      <div class="form-check custom-option custom-option-basic">
                        <label class="form-check-label custom-option-content" for="custom_question_{{ $question->id }}">
                          <input class="form-check-input" type="checkbox" name="custom_question[]" value="{{ $question->id }}" id="custom_question_{{ $question->id }}" @if($question->is_required == 'yes') required @endif>
                          <span class="custom-option-header">
                            <span class="h6 mb-0">{{ $question->question }} @if($question->is_required == 'yes') <span class="text-danger">*</span> @endif</span>
                          </span>
                        </label>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="hitech-card-white mb-6">
          <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Job Description <span class="text-danger">*</span></h5>
          </div>
          <div class="card-body pt-5">
            <div id="description-editor" style="height: 300px;"></div>
            <input type="hidden" name="description" id="description" required>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="hitech-card-white mb-6">
          <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Job Requirements <span class="text-danger">*</span></h5>
          </div>
          <div class="card-body pt-5">
            <div id="requirement-editor" style="height: 300px;"></div>
            <input type="hidden" name="requirement" id="requirement" required>
          </div>
        </div>
      </div>

      <div class="col-12" id="terms-wrapper" style="display: none;">
        <div class="hitech-card-white mb-6">
          <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Terms & Conditions <span class="text-danger">*</span></h5>
          </div>
          <div class="card-body pt-5">
            <div id="terms-editor" style="height: 300px;"></div>
            <input type="hidden" name="terms_and_conditions" id="terms_and_conditions">
          </div>
        </div>
      </div>

      <div class="col-12 text-end mb-6">
        <a href="{{ route('job.index') }}" class="btn btn-label-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary px-6">Create Job</button>
      </div>
    </div>
    </form>
  </div>
</div>
@endsection
