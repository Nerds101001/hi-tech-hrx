@extends('layouts/layoutMaster')

@section('title', 'Employees')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
  <style>
    .bg-light-soft {
        background-color: rgba(0, 90, 90, 0.04) !important;
    }
  </style>
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
  ])
@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite([
    'resources/js/main-helper.js',
    'resources/assets/js/app/employee-index.js',
    'resources/js/main-select2.js'
])
@endsection


@section('content')
<div class="layout-full-width animate__animated animate__fadeIn">
  {{-- Integrated Header & Toggle --}}
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6 mx-4">
    <h3 class="fw-extrabold mb-0 text-dark">Employee Directory</h3>
    @if(auth()->user()->can('user-create') || auth()->user()->hasRole('hr'))
      <a href="{{ route('employees.create') }}" class="btn-hitech shadow-sm rounded-pill px-5">
        <i class="bx bx-plus-circle me-1"></i>Add Employee
      </a>
    @endif
  </div>

  <div class="px-4" id="filter-section">
    <div class="hitech-card-white mb-6 overflow-hidden">
      <div class="card-body p-sm-5 p-4">
        <div class="row align-items-center g-4">
          {{-- Search & Filters Toggle --}}
          <div class="col-lg-7 d-flex flex-wrap align-items-center gap-3">
            <div class="search-wrapper-hitech w-px-400 mw-100">
              <i class="bx bx-search text-muted ms-3"></i>
              <input type="text" class="form-control" placeholder="Search..." id="customSearchInput">
              <button class="btn-search d-none d-sm-flex" id="customSearchBtn">
                <i class="bx bx-search fs-5"></i>Search
              </button>
            </div>
            <button class="btn btn-white shadow-sm rounded-pill px-4 border d-flex justify-content-center align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
              <i class="bx bx-filter-alt text-muted"></i>
              <span class="fw-semibold">Filters</span>
            </button>
          </div>

          {{-- View Toggle & Per Page --}}
          <div class="col-lg-5 d-flex flex-wrap align-items-center justify-content-lg-end gap-3 mt-3 mt-lg-0">
            <div class="view-toggle-hitech shadow-sm">
              <button class="btn-toggle active" onclick="toggleView('list')" id="list-toggle-btn">
                <i class="bx bx-list-ul"></i>
              </button>
              <button class="btn-toggle" onclick="toggleView('card')" id="card-toggle-btn">
                <i class="bx bx-grid-alt"></i>
              </button>
            </div>

            <div class="d-flex align-items-center gap-3">
              <span class="text-muted fw-semibold small">Per Page:</span>
              <select class="form-select w-px-80 rounded-pill border-light shadow-none fw-bold" id="customLengthMenu">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
          </div>
        </div>

        <div class="collapse" id="advancedFilters">
          <div class="row g-4 pt-5 mt-4 border-top">
            <div class="col-md-3">
              <label class="form-label fw-bold small text-muted text-uppercase mb-2">Role</label>
              <select class="form-select select2" id="roleFilter">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                  <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-bold small text-muted text-uppercase mb-2">Department (Team)</label>
              <select class="form-select select2" id="teamFilter">
                <option value="">All Departments</option>
                @foreach($teams as $team)
                  <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-bold small text-muted text-uppercase mb-2">Designation</label>
              <select class="form-select select2" id="designationFilter">
                <option value="">All Designations</option>
                @foreach($designations as $designation)
                  <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-bold small text-muted text-uppercase mb-2">Status</label>
              <select class="form-select select2" id="statusFilter">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="relieved">Relieved</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Users List Table (List View) --}}
  <div id="list-view-container" class="animate__animated animate__fadeIn px-4">
    <div class="hitech-card-white p-0 overflow-hidden">
      <div class="card-datatable table-responsive">
        <table class="datatables-users table m-0 shadow-none">
          <thead>
          <tr>
            <th></th>
            <th>#</th>
            <th>Name</th>
            <th>Employee ID</th>
            <th>Department</th>
            <th>Designation</th>
            <th>Employee Status</th>
            <th>Joined</th>
            <th class="text-center">Actions</th>
          </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <!-- Users Grid (Card View) -->
  <div id="card-view-container" style="display: none;" class="animate__animated animate__fadeIn px-4">
    <div class="row g-6">
      @forelse($users as $user)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
          <div class="hitech-employee-card shadow-sm">
            {{-- Action Dropdown (Top Right) --}}
            <div class="card-actions-dropdown">
              <div class="dropdown">
                <button class="btn-dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                  <li><a class="dropdown-item d-flex align-items-center gap-2 reset-password" href="javascript:;" data-id="{{ $user->id }}"><i class="bx bx-key text-success"></i>Reset Password</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item d-flex align-items-center gap-2 text-danger delete-record" href="javascript:;" data-id="{{ $user->id }}"><i class="bx bx-lock-alt"></i>Deactivate Account</a></li>
                </ul>
              </div>
            </div>

            {{-- Header: Avatar + Info (Reference 4 Horizontal Layout) --}}
            <div class="card-header-flex px-3">
              <div class="avatar-box">
                @if($user->profile_picture)
                  <img src="{{ $user->getProfilePicture() }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #fff;">
                @else
                  <div class="avatar-initial-hitech rounded-circle shadow-sm" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; background: var(--hitech-primary-light); color: var(--hitech-primary); font-weight: 700; border: 2px solid #fff;">
                    {{ $user->getInitials() }}
                  </div>
                @endif
              </div>
              <div class="info-box ms-3 overflow-hidden">
                <h6 class="mb-0 text-truncate text-heading fw-bold" title="{{ $user->full_name }}">{{ $user->full_name }}</h6>
                <div class="email text-truncate text-muted small mb-1" title="{{ $user->email }}">{{ $user->email }}</div>
                <div class="emp-id-badge d-inline-block px-2 py-0 rounded bg-light text-muted" style="font-size: 0.65rem; font-weight: 700;">ID: {{ $user->code ?? 'N/A' }}</div>
              </div>
            </div>

            {{-- Details Sub-Box (Light Gray Background with Internal Padding) --}}
            <div class="details-sub-box p-4 rounded mt-3 m-3" style="background: #f8fafc; border: 1px solid rgba(0,0,0,0.03);">
              <div class="row g-3">
                <div class="col-6">
                  <div class="detail-item">
                    <span class="label d-block text-muted text-uppercase fw-bold mb-1" style="font-size: 0.6rem; letter-spacing: 0.05em;">Department</span>
                    <span class="value text-truncate d-block fw-semibold text-heading small">{{ $user->team?->name ?? 'No Team' }}</span>
                  </div>
                </div>
                <div class="col-6">
                  <div class="detail-item">
                    <span class="label d-block text-muted text-uppercase fw-bold mb-1" style="font-size: 0.6rem; letter-spacing: 0.05em;">Designation</span>
                    <span class="value text-truncate d-block fw-semibold text-heading small">{{ $user->designation?->name ?? 'Staff Member' }}</span>
                  </div>
                </div>
                <div class="col-12 mt-3 pt-3 border-top" style="border-color: rgba(0,0,0,0.05) !important;">
                   <div class="d-flex justify-content-between align-items-center">
                      <span class="label text-muted text-uppercase fw-bold" style="font-size: 0.6rem;">Joined</span>
                      <span class="value fw-semibold text-heading small">{{ $user->date_of_joining ?? 'N/A' }}</span>
                   </div>
                </div>
              </div>
            </div>

            {{-- Footer Buttons (Equal Width, Outlined Styled) --}}
            <div class="card-footer-btns d-flex gap-3 mt-1 px-3 pb-3">
              <a href="javascript:;" class="btn btn-teal-outline flex-grow-1 edit-record d-flex align-items-center justify-content-center" data-id="{{ $user->id }}" style="border-radius: 8px; font-size: 0.8rem; font-weight: 700; padding: 0.5rem;">
                <i class="bx bx-edit me-1"></i>Edit
              </a>
              <a href="{{ route('employees.show', $user->id) }}" class="btn btn-teal-outline flex-grow-1 d-flex align-items-center justify-content-center" style="border-radius: 8px; font-size: 0.8rem; font-weight: 700; padding: 0.5rem;">
                <i class="bx bx-show me-1"></i>View
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center py-10">
          <p class="text-muted">No employees found.</p>
        </div>
      @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-8 d-flex justify-content-center">
      {{ $users->links() }}
    </div>
  </div>

  {{-- Removed onboarding_submissions_table include as it's now a separate full-page Review Center --}}
</div> {{-- End layout-full-width --}}

@include('tenant.employees.onboarding_invite_modal')

  <script>
    window.toggleView = function(view) {
      const listView = document.getElementById('list-view-container');
      const cardView = document.getElementById('card-view-container');
      const listBtn = document.getElementById('list-toggle-btn');
      const cardBtn = document.getElementById('card-toggle-btn');

      if (view === 'list') {
        listView.style.display = 'block';
        cardView.style.display = 'none';
        listBtn.classList.add('active');
        cardBtn.classList.remove('active');
      } else {
        listView.style.display = 'none';
        cardView.style.display = 'block';
        cardBtn.classList.add('active');
        listBtn.classList.remove('active');
      }
    }
  </script>
@endsection
