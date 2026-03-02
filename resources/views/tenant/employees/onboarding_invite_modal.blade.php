<!-- Onboarding Invite Modal -->
<div class="modal fade" id="onboardingInviteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      <div class="modal-header border-0 pb-0 pt-6 px-6">
        <h5 class="modal-title fw-bold text-heading d-flex align-items-center gap-2">
           <div class="hitech-icon-wrapper-teal"><i class="bx bx-send"></i></div>
           Invite for Onboarding
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-6 pb-6 mt-4">
        <form id="onboardingInviteForm" action="{{ route('employees.initiateOnboarding') }}" method="POST">
          @csrf
          <div class="row g-4">
            <div class="col-12">
              <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-user"></i></span>
                <input type="text" name="firstName" class="form-control" placeholder="First Name" required>
                <input type="text" name="lastName" class="form-control" placeholder="Last Name" required>
              </div>
            </div>
            
            <div class="col-12">
              <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="personal-email@domain.com" required>
              </div>
              <small class="text-muted">The invitation link will be sent to this email.</small>
            </div>

            <div class="col-12">
              <label class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-phone"></i></span>
                <input type="text" name="phone" class="form-control" placeholder="10-digit mobile number" required maxlength="10">
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Role <span class="text-danger">*</span></label>
              <select name="role" class="form-select select2" required>
                <option value="">Select Role</option>
                @foreach($roles as $role)
                  <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Department (Team) <span class="text-danger">*</span></label>
              <select name="teamId" class="form-select select2" required>
                <option value="">Select Team</option>
                @foreach($teams as $team)
                  <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-3 mt-8">
            <button type="button" class="btn btn-label-secondary px-6" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-hitech-primary px-8">Send Invitation</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
.hitech-icon-wrapper-teal {
    width: 32px;
    height: 32px;
    background: rgba(0, 128, 128, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #008080;
}
</style>
