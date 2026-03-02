@extends('layouts/layoutMaster')

@section('title', 'Manage Custom Questions')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'
  ])
@endsection

@section('page-script')
  <script>
    $(document).ready(function() {
      var table = $('.datatable').DataTable({
        dom: 't<"d-flex justify-content-between align-items-center mx-3 mt-4 mb-2" <"small text-muted" i> <"pagination-wrapper" p>>',
        language: {
          info: 'Showing _START_ to _END_ of _TOTAL_ questions',
          paginate: {
            next: '<i class="bx bx-chevron-right"></i>',
            previous: '<i class="bx bx-chevron-left"></i>'
          }
        }
      });
      $('#customSearchInput').on('keyup', function() {
        table.search(this.value).draw();
      });
      $('#customSearchBtn').on('click', function() {
        table.search($('#customSearchInput').val()).draw();
      });
      $('#customLengthMenu').on('change', function() {
        table.page.len($(this).val()).draw();
      });
    });
  </script>
@endsection

@section('content')
<div class="layout-full-width animate__animated animate__fadeIn">
  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-6 px-4">
    <h3 class="mb-0 fw-bold text-heading" style="font-size: 1.5rem;">Custom Questions</h3>
    @can('Create Custom Question')
      <a href="#" data-url="{{ route('custom-question.create') }}" data-ajax-popup="true" data-size="md" data-title="Create New Custom Question" class="btn btn-hitech-primary shadow-sm">
        <i class="bx bx-plus me-1"></i>New Question
      </a>
    @endcan
  </div>

  <div class="px-4">
    <div class="hitech-card-white mb-6 overflow-hidden">
      <div class="card-body p-sm-5 p-4">
        <div class="row align-items-center g-4">
          <div class="col-md-9 d-flex align-items-center gap-3 w-100">
            <div class="search-wrapper-hitech flex-grow-1">
              <i class="bx bx-search text-muted ms-3"></i>
              <input type="text" class="form-control border-0 bg-transparent shadow-none" placeholder="Search Questions..." id="customSearchInput">
            </div>
            <button class="btn btn-primary btn-sm px-3 shadow-sm d-flex align-items-center gap-1" id="customSearchBtn" style="background-color: #0f766e; border-color: #0f766e;">
              <i class="bx bx-search"></i> Search
            </button>
          </div>
          <div class="col-md-3 d-flex align-items-center justify-content-end gap-3 mt-0">
            <span class="text-muted fw-semibold small text-nowrap">Per Page:</span>
            <select class="form-select flex-shrink-0 w-px-80 rounded text-center border-light shadow-none fw-bold" id="customLengthMenu" style="background-color: #f8f9fa;">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="hitech-card-white p-0 overflow-hidden">
      <div class="card-datatable table-responsive">
        <table class="datatable table m-0 shadow-none table-hover">
            <thead>
              <tr class="text-muted small text-uppercase">
                <th class="border-bottom">Question</th>
                <th class="border-bottom text-center">Is Required?</th>
                <th class="border-bottom text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($questions as $question)
                <tr>
                  <td><span class="fw-bold text-heading">{{ $question->question }}</span></td>
                  <td class="text-center">
                    @if ($question->is_required == 'yes')
                      <span class="badge bg-label-success rounded-pill px-3">Yes</span>
                    @else
                      <span class="badge bg-label-secondary rounded-pill px-3">No</span>
                    @endif
                  </td>
                  <td class="text-end">
                    <div class="d-flex justify-content-end gap-2">
                      @can('Edit Custom Question')
                        <a href="#" data-url="{{ route('custom-question.edit', $question->id) }}" data-ajax-popup="true" data-title="Edit Custom Question" class="btn btn-icon btn-label-info btn-sm" data-bs-toggle="tooltip" title="Edit">
                          <i class="bx bx-edit-alt"></i>
                        </a>
                      @endcan

                      @can('Delete Custom Question')
                        <form action="{{ route('custom-question.destroy', $question->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-icon btn-label-danger btn-sm bs-pass-para" data-bs-toggle="tooltip" title="Delete">
                            <i class="bx bx-trash"></i>
                          </button>
                        </form>
                      @endcan
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
