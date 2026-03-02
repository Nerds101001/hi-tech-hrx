@extends('layouts/layoutMaster')

@section('title', __('Loan Management'))

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/scss/pages/hitech-portal.scss'
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
  ])
@endsection

@section('content')
<div class="row g-6">
  <!-- Hero Banner -->
  <div class="col-lg-12">
    <x-hero-banner 
      title="Loan Management" 
      subtitle="Manage employee loan requests and repayment statuses"
      icon="bx-money"
      gradient="success"
    />
  </div>

  <!-- Stats Cards -->
  <x-stat-card 
    title="Total Loan Amount" 
    value="{{ number_format($totalLoanAmount ?? 0, 2) }}" 
    icon="bx-wallet" 
    color="primary"
    animation-delay="0.1s"
  />
  
  <x-stat-card 
    title="Pending Requests" 
    value="{{ $pendingLoans ?? 0 }}" 
    icon="bx-time" 
    color="warning"
    animation-delay="0.2s"
  />
  
  <x-stat-card 
    title="Approved Loans" 
    value="{{ $approvedLoans ?? 0 }}" 
    icon="bx-check-circle" 
    color="success"
    animation-delay="0.3s"
  />
  
  <x-stat-card 
    title="Rejected Loans" 
    value="{{ $rejectedLoans ?? 0 }}" 
    icon="bx-trending-down" 
    color="danger"
    animation-delay="0.4s"
  />

  <!-- Table -->
  <div class="col-12">
    <div class="hitech-card animate__animated animate__fadeInUp" style="animation-delay: 0.5s">
      <div class="hitech-card-header">
        <h5 class="title mb-0">Loan Requests List</h5>
      </div>
      <div class="card-datatable table-responsive">
        <table class="datatables-loanRequests table border-top">
          <thead>
            <tr>
              <th>ID</th>
              <th>Employee</th>
              <th>Requested Amount</th>
              <th>Approved Amount</th>
              <th>Status</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Action Modal -->
<div class="modal fade" id="actionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content hitech-card">
      <div class="hitech-card-header">
        <h5 class="title">Loan Action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="actionForm">
          <input type="hidden" id="requestId" name="id">
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" id="requestStatus">
              <option value="Pending">Pending</option>
              <option value="Approved">Approved</option>
              <option value="Rejected">Rejected</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Approved Amount</label>
            <input type="number" step="0.01" class="form-control" name="approved_amount" id="approvedAmount">
          </div>
          <div class="mb-3">
            <label class="form-label">Admin Remarks</label>
            <textarea class="form-control" name="admin_remarks" id="adminRemarks" rows="3"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="submitAction()">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = $('.datatables-loanRequests').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('loan.getListAjax') }}",
        columns: [
            { data: 'id' },
            { data: 'user' },
            { 
                data: 'amount',
                render: function(data) { return data ? data.toLocaleString() : '0'; }
            },
            { 
                data: 'approved_amount',
                render: function(data) { return data ? data.toLocaleString() : '-'; }
            },
            { 
                data: 'status',
                render: function(data) {
                    let badgeClass = 'bg-label-primary';
                    if (data === 'Approved') badgeClass = 'bg-label-success';
                    if (data === 'Rejected') badgeClass = 'bg-label-danger';
                    if (data === 'Pending') badgeClass = 'bg-label-warning';
                    return `<span class="badge ${badgeClass}">${data}</span>`;
                }
            },
            { data: 'created_at' },
            {
                data: null,
                render: function(data) {
                    return `<button class="btn btn-sm btn-icon btn-hitech" onclick="openActionModal(${data.id}, '${data.status}', '${data.amount}', '${data.admin_remarks || ''}')"><i class="bx bx-edit"></i></button>`;
                }
            }
        ],
        order: [[0, 'desc']],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
            paginate: {
                next: '<i class="bx bx-chevron-right"></i>',
                previous: '<i class="bx bx-chevron-left"></i>'
            }
        }
    });
});

function openActionModal(id, status, amount, remarks) {
    $('#requestId').val(id);
    $('#requestStatus').val(status);
    $('#approvedAmount').val(amount);
    $('#adminRemarks').val(remarks);
    $('#actionModal').modal('show');
}

function submitAction() {
    const formData = $('#actionForm').serialize();
    $.ajax({
        url: "{{ route('loan.actionAjax') }}",
        method: "POST",
        data: formData + "&_token={{ csrf_token() }}",
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message,
                customClass: { confirmButton: 'btn btn-primary' }
            });
            $('#actionModal').modal('hide');
            $('.datatables-loanRequests').DataTable().ajax.reload();
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong',
                customClass: { confirmButton: 'btn btn-primary' }
            });
        }
    });
}
</script>
@endsection
