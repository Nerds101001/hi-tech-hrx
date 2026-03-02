@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Roles - Apps')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
    ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
    ])
@endsection

@section('page-script')
  @vite([
    'resources/assets/js/app/role-index.js',
    ])
@endsection

@section('content')
  <h4 class="mb-4 text-white animate__animated animate__fadeInLeft">@lang('Roles & Permissions')</h4>
  
  <!-- Role cards -->
  <div class="row g-4 animate__animated animate__fadeInUp">
    @foreach($roles as $role)
      <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="hitech-card h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h6 class="fw-normal mb-0 text-white opacity-75">@lang('Total') {{$role->users()->count()}} @lang('Users')</h6>
              <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                @foreach($role->users()->limit(3)->get() as $user)
                  @php
                    $randomStatusColor = ['primary', 'success', 'danger', 'warning', 'info', 'dark'];
                    $randomColor = $randomStatusColor[array_rand($randomStatusColor)];
                  @endphp
                  <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                      title="{{$user->getFullName()}}"
                      class="avatar pull-up">
                    @if($user->profile_picture)
                      <img class="rounded-circle"
                           src="{{$user->getProfilePicture()}}"
                           alt="Avatar">
                    @else
                      <span
                        class="avatar-initial rounded-circle bg-label-{{$randomColor}}">{{ $user->getInitials() }}</span>
                    @endif
                  </li>
                @endforeach
                @if($role->users()->count() > 3)
                  <li class="avatar">
                    <span class="avatar-initial rounded-circle pull-up" data-bs-toggle="tooltip"
                          data-bs-placement="bottom"
                          title="{{$role->users()->count() - 3}} more">+{{$role->users()->count() - 3}}</span>
                  </li>
                @endif

              </ul>
            </div>
            <div class="d-flex justify-content-between align-items-end">
              <div class="role-heading">
                <h4 class="mb-1 text-white">{{$role->name}}</h4>
                @if(in_array($role->name, Constants::BuiltInRoles))
                    <small class="text-success"><i class="bx bx-check-shield me-1"></i> System Role</small>
                @else
                    <small class="text-warning"><i class="bx bx-user me-1"></i> Custom Role</small>
                @endif
              </div>
              <div class="d-flex gap-2">
                <a href="javascript:void(0);" class="btn btn-icon btn-label-warning edit" data-value="{{$role}}">
                    <i class="bx bx-pencil"></i>
                </a>
                @if(!in_array($role->name, Constants::BuiltInRoles))
                <a href="javascript:void(0);" class="btn btn-icon btn-label-danger" onclick="deleteRole({{$role->id}})">
                    <i class="bx bx-trash"></i>
                </a>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
    
    <div class="col-xl-4 col-lg-6 col-md-6">
      <div class="hitech-card h-100 d-flex align-items-center justify-content-center" style="background: rgba(255, 255, 255, 0.02); border-style: dashed;">
        <div class="row w-100 h-100">
          <div class="col-sm-12">
            <div class="card-body text-center d-flex flex-column align-items-center justify-content-center h-100">
              <button data-bs-target="#addOrUpdateRoleModal" data-bs-toggle="modal"
                      class="btn btn-primary btn-hitech-glow mb-3 text-nowrap add-new-role">
                  <i class="bx bx-plus me-1"></i> @lang('Add New Role')
              </button>
              <p class="mb-0 text-muted small">Add a new role to assign permissions <br> and manage user access levels.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Role cards -->

  @if($settings->is_helper_text_enabled)
    <div class="alert alert-warning alert-dismissible fade show mt-4 animate__animated animate__fadeIn" role="alert" style="background: rgba(255, 171, 0, 0.1); border: 1px solid rgba(255, 171, 0, 0.3); color: #ffab00;">
      <div class="d-flex">
          <i class="bx bx-info-circle me-2 mt-1"></i>
          <div>
              <h6 class="alert-heading mb-1 text-warning">Warning</h6>
              <p class="mb-0 opacity-75">
                Do not delete the default system roles <strong>{{implode(', ', Constants::BuiltInRoles)}}</strong>. Deleting
                these roles will cause the system to malfunction.
              </p>
          </div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Add Role Modal -->
  @include('_partials._modals.role.addOrUpdate-role')
  <!-- / Add Role Modal -->
@endsection
