$(function () {
  var date = $('#dateFilter').val();

  var dtTable = $('.datatables-leaveRequests');


  $('#employeeFilter').select2();

  $('#employeeFilter').on('change', function () {
    dtLeaveRequests.draw();
  });

  $('#leaveTypeFilter').select2();

  $('#leaveTypeFilter').on('change', function () {
    dtLeaveRequests.draw();
  });

  // Status Toggle Logic
  $('.status-toggle-btn').on('click', function () {
    $('.status-toggle-btn').removeClass('active btn-white').addClass('btn-transparent text-muted');
    $(this).addClass('active btn-white text-dark').removeClass('btn-transparent text-muted');

    var status = $(this).data('status');
    $('#statusFilter').val(status);

    // Dynamic Column Visibility based on status
    if (status === 'approved') {
      dtLeaveRequests.column(10).visible(false); // Status
      dtLeaveRequests.column(12).visible(false); // Actions
      dtLeaveRequests.column(13).visible(true);  // Approved By
      dtLeaveRequests.column(14).visible(true);  // Approved At

      $('.status-col, .action-col').hide();
      $('.approved-by-col, .approved-at-col').show();
    } else {
      dtLeaveRequests.column(10).visible(true);
      dtLeaveRequests.column(12).visible(true);
      dtLeaveRequests.column(13).visible(false);
      dtLeaveRequests.column(14).visible(false);

      $('.status-col, .action-col').show();
      $('.approved-by-col, .approved-at-col').hide();
    }

    dtLeaveRequests.draw();
  });

  // ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  if (dtTable.length) {
    var employeeView = baseUrl + 'employees/view/';

    var dtLeaveRequests = dtTable.DataTable({
      processing: true,
      serverSide: true,
      dom: 'rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>', // Custom DOM for Hitech layout
      ajax: {
        url: baseUrl + 'leaveRequests/getListAjax',
        data: function (d) {
          d.dateFilter = $('#dateFilter').val();
          d.employeeFilter = $('#employeeFilter').val();
          d.leaveTypeFilter = $('#leaveTypeFilter').val();
          d.statusFilter = $('#statusFilter').val();
          d.searchTerm = $('#customSearchInput').val(); // Integrated Search
        },
        error: function (xhr, error, code) {
          console.log('Error: ' + error);
          console.log('Code: ' + code);
          console.log('Response: ' + xhr.responseText);
        }
      },
      columns: [
        { data: null, defaultContent: '' },     // 0: Control
        { data: 'id' },                         // 1: Checkbox
        { data: 'id' },                         // 2: SR No
        { data: 'user_name' },                  // 3: Employee
        { data: 'department' },                 // 4: Department
        { data: 'leave_type' },                 // 5: Leave Type
        { data: 'from_date' },                  // 6: From Date
        { data: 'to_date' },                    // 7: To Date
        { data: 'days' },                       // 8: Days
        { data: 'reason' },                     // 9: Reason
        { data: 'status' },                     // 10: Status
        { data: 'document' },                   // 11: Attachment
        { data: null, defaultContent: '' },     // 12: Actions
        { data: 'approved_by_name' },           // 13: Approved By
        { data: 'approved_at_formatted' }       // 14: Approved At
      ],
      columnDefs: [
        {
          // For Responsive Control
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // Checkboxes
          targets: 1,
          orderable: false,
          searchable: false,
          responsivePriority: 3,
          checkboxes: true,
          render: function (data, type, full, meta) {
            return '<input type="checkbox" class="dt-checkboxes form-check-input" value="' + full['id'] + '">';
          }
        },
        {
          // SR No (or ID)
          targets: 2,
          className: 'text-start',
          render: function (data, type, full, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {
          // Employee Name with avatar
          targets: 3,
          className: 'text-start',
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $name = full['user_name'],
              code = full['user_code'],
              initials = full['user_initial'],
              profileOutput,
              rowOutput;

            if (full['user_profile_image']) {
              profileOutput = '<img src="' + full['user_profile_image'] + '" alt="Avatar" class="avatar rounded-circle " />';
            } else {
              initials = full['user_initial'] || '';
              profileOutput = '<span class="avatar-initial rounded-circle bg-label-info">' + initials + '</span>';
            }

            rowOutput =
              '<div class="d-flex justify-content-start align-items-center user-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar avatar-sm me-4">' +
              profileOutput +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<a href="' + employeeView + full['user_id'] + '" class="text-heading text-truncate"><span class="fw-medium">' + $name + '</span></a>' +
              '<small>' + code + '</small>' +
              '</div>' +
              '</div>';

            return rowOutput;
          }
        },
        {
          // Department
          targets: 4,
          className: 'text-start',
          render: function (data, type, full, meta) {
            return '<span class="text-heading">' + (full['department'] || 'N/A') + '</span>';
          }
        },
        {
          // Leave type
          targets: 5,
          className: 'text-start',
          render: function (data, type, full, meta) {
            return full['leave_type'] || 'N/A';
          }
        },
        {
          // From Date
          targets: 6,
          className: 'text-start',
          render: function (data, type, full, meta) {
            return full['from_date'] || '';
          }
        },
        {
          // To Date
          targets: 7,
          className: 'text-start',
          render: function (data, type, full, meta) {
            return full['to_date'] || '';
          }
        },
        {
          // Days
          targets: 8,
          className: 'text-start',
          render: function (data, type, full, meta) {
            var days = full['days'] ? full['days'] : 1;
            var suffix = days > 1 ? ' Days' : ' Day';
            return '<span class="badge badge-hitech-success">' + days + suffix + '</span>';
          }
        },
        {
          // Reason
          targets: 9,
          className: 'text-start',
          render: function (data, type, full, meta) {
            var reason = full['reason'] ? full['reason'] : 'N/A';
            var short_reason = reason.length > 25 ? reason.substring(0, 25) + '...' : reason;
            return '<span class="text-muted" title="' + reason + '">' + short_reason + '</span>';
          }
        },
        {
          // Status
          targets: 10,
          className: 'text-start',
          render: function (data, type, full, meta) {
            var $status = full['status'];
            if ($status === 'approved') {
              return '<span class="badge badge-hitech bg-label-success"><i class="bx bxs-circle me-1" style="font-size:0.5rem;"></i>Approved</span>';
            } else if ($status === 'rejected') {
              return '<span class="badge badge-hitech bg-label-danger"><i class="bx bxs-circle me-1" style="font-size:0.5rem;"></i>Rejected</span>';
            } else if ($status === 'cancelled') {
              return '<span class="badge badge-hitech bg-label-secondary"><i class="bx bxs-circle me-1" style="font-size:0.5rem;"></i>Cancelled</span>';
            } else {
              return '<span class="badge badge-hitech bg-label-warning"><i class="bx bxs-circle me-1" style="font-size:0.5rem;"></i>Pending</span>';
            }
          }
        },
        {
          // Attachment
          targets: 11,
          className: 'text-start',
          render: function (data, type, full, meta) {
            if (full['document']) {
              return `<a href="${full['document']}" class="glightbox"> <img src="${full['document']}" alt="Proof" height="50"/> </a>`;
            }
            return '<span class="text-muted">N/A</span>';
          }
        },
        {
          // Actions
          targets: 12,
          searchable: false,
          orderable: false,
          className: 'text-center',
          render: function (data, type, full, meta) {
            var actionsHtml = '<div class="d-flex align-items-center gap-2">';
            actionsHtml += `<button class="btn btn-sm btn-icon leave-request-details hitech-action-icon" data-id="${full['id']}" data-bs-toggle="modal" data-bs-target="#modalLeaveRequestDetails" title="View Details"><i class="bx bx-show"></i></button>`;

            // Re-adding Quick Actions specifically for Pending state
            if (full['status'] === 'pending') {
              actionsHtml += `<button class="btn btn-sm btn-icon hitech-action-icon text-success quick-leave-approve" data-id="${full['id']}" title="Quick Approve"><i class="bx bx-check-circle"></i></button>`;
              actionsHtml += `<button class="btn btn-sm btn-icon hitech-action-icon text-danger quick-leave-reject" data-id="${full['id']}" title="Quick Reject"><i class="bx bx-x-circle"></i></button>`;
            }

            actionsHtml += '</div>';
            return actionsHtml;
          }
        },
        {
          // Approved By
          targets: 13,
          visible: false,
          className: 'text-start',
          render: function (data, type, full, meta) {
            return '<span class="fw-bold text-dark">' + (full['approved_by_name'] || 'N/A') + '</span>';
          }
        },
        {
          // Approved At
          targets: 14,
          visible: false,
          className: 'text-start',
          render: function (data, type, full, meta) {
            return '<span class="text-muted">' + (full['approved_at_formatted'] || 'N/A') + '</span>';
          }
        }
      ],
      order: [[2, 'asc']],
      lengthMenu: [7, 10, 20, 50, 70, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Leave Requests',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries',
        paginate: {
          next: '<i class="bx bx-chevron-right bx-sm"></i>',
          previous: '<i class="bx bx-chevron-left bx-sm"></i>'
        }
      },
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();

              return 'Details of ' + data['name'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                col.rowIndex +
                '" data-dt-column="' +
                col.columnIndex +
                '">' +
                '<td>' +
                col.title +
                ':' +
                '</td> ' +
                '<td>' +
                col.data +
                '</td>' +
                '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });

    // Integrated Filter Listeners
    $('#employeeFilter, #leaveTypeFilter, #statusFilter, #dateFilter').on('change', function () {
      dtLeaveRequests.draw();
      refreshLeaveChart();
    });

    $('#customSearchBtn').on('click', function () {
      dtLeaveRequests.draw();
      refreshLeaveChart();
    });

    $('#customSearchInput').on('keyup', function (e) {
      if (e.key === 'Enter') {
        dtLeaveRequests.draw();
        refreshLeaveChart();
      }
    });

    $('#customLengthMenu').on('change', function () {
      dtLeaveRequests.page.len($(this).val()).draw();
    });

    // Dynamic Chart Refresh (Simulated)
    window.refreshLeaveChart = function () {
      if (window.leaveChart) {
        const newVal1 = Array.from({ length: 7 }, () => Math.floor(Math.random() * 15) + 5);
        const newVal2 = Array.from({ length: 7 }, () => Math.floor(Math.random() * 8) + 1);

        window.leaveChart.updateSeries([{
          name: 'Entitled/Available',
          data: newVal1
        }, {
          name: 'Used To Date',
          data: newVal2
        }]);
      }
    };


    //Glide box initialisation
    const lightbox = GLightbox({
      selector: 'glightbox'
    });

    // To remove default btn-secondary in export buttons
    $('.dt-buttons > .btn-group > button').removeClass('btn-secondary');
  }

  // Handle date changes for standard var update
  $('#dateFilter').on('change', function () {
    date = this.value;
  });

  // leave request details
  $(document).on('click', '.leave-request-details', function () {
    var id = $(this).data('id');
    // Reset modal state
    $('#statusInput').val('');
    $('#adminNotes').val('');
    $('#remarksRequired').hide();
    $('#adminNotes').css('border-color', '');

    // get data
    $.get(`${baseUrl}leaveRequests/getByIdAjax/${id}`, function (response) {
      if (response.status === 'success') {
        var data = response.data;
        var statusDiv = $('#statusDiv');

        $('#id').val(data.id);
        $('#userName, #userNameLabel, #userNameHeader').text(data.userName);
        $('#userCode').text(data.userCode);

        // Handle avatar/initials
        if (data.user_profile_image) {
          $('#userAvatarContainer').html(`<img src="${data.user_profile_image}" class="avatar avatar-md rounded-circle border" />`);
        } else {
          $('#userAvatarContainer').html(`<div class="avatar avatar-md"><span class="avatar-initial rounded-circle bg-label-primary shadow-sm">${data.userInitials || ''}</span></div>`);
        }

        $('#leaveType').text(data.leaveType);
        $('#fromDate').text(data.fromDate);
        $('#toDate').text(data.toDate);
        $('#totalDays').text(data.days);
        $('#dayLabel').text(data.days == 1 ? 'Day' : 'Days');
        $('#createdAt').text(data.createdAt);
        $('#userNotes').text(data.userNotes || 'N/A');

        $('#leaveRequestForm').hide();
        $('#alreadyRespondedNotice').hide();

        if (data.status === 'approved') {
          statusDiv.html('<span class="badge bg-label-success px-3 py-2"><i class="bx bx-check-circle me-1"></i>Approved</span>');
          $('#leaveRequestForm').show();
          $('#btnApprove').hide();
          $('#btnReject').html('REVOKE').show();
        } else if (data.status === 'rejected') {
          statusDiv.html('<span class="badge bg-label-danger px-3 py-2"><i class="bx bx-x-circle me-1"></i>Rejected</span>');
          $('#alreadyRespondedNotice').show();
        } else if (data.status === 'cancelled') {
          statusDiv.html('<span class="badge bg-label-secondary px-3 py-2"><i class="bx bx-minus-circle me-1"></i>Cancelled</span>');
          $('#alreadyRespondedNotice').show();
        } else {
          statusDiv.html('<span class="badge bg-label-warning px-3 py-2"><i class="bx bx-time me-1"></i>Pending Review</span>');
          $('#btnApprove').show();
          $('#btnReject').html('REJECT').show();
          $('#leaveRequestForm').show();
        }

        if (data.document !== null) {
          $('#document').attr('src', data.document);
          $('#documentHide').show();
        } else {
          $('#document').attr('src', '');
          $('#documentHide').hide();
        }
      }
    });
  });

  // Global submitDecision function
  window.submitDecision = function (status) {
    if (status === 'rejected' && !$('#adminNotes').val().trim()) {
      Swal.fire({
        title: 'Remarks Required',
        text: 'Please provide a reason for rejection.',
        icon: 'warning',
        confirmButtonColor: '#007a7a'
      });
      $('#adminNotes').focus();
      return;
    }

    $('#statusInput').val(status);
    $('#leaveRequestForm').submit();
  };

  // Handle form submission with interactive feedback
  $('#leaveRequestForm').on('submit', function (e) {
    e.preventDefault();
    var form = $(this);
    var status = $('#statusInput').val();
    var btn = status === 'approved' ? $('#btnApprove') : $('#btnReject');
    var originalContent = btn.html();

    btn.addClass('disabled').html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Processing...');

    $.ajax({
      url: form.attr('action') || `${baseUrl}leaveRequests/actionAjax`,
      type: 'POST',
      data: form.serialize(),
      success: function (response) {
        $('#modalLeaveRequestDetails').modal('hide');
        Swal.fire({
          title: 'Success!',
          text: `Leave request has been updated.`,
          icon: 'success',
          timer: 2000,
          showConfirmButton: false
        });

        if (dtLeaveRequests) dtLeaveRequests.ajax.reload();

        // Refresh stats cards manually or by reloading page - let's try to fetch fresh counts if possible
        // For now, a quick reload of stats or page works best to ensure accuracy
        setTimeout(() => { location.reload(); }, 2000);
      },
      error: function () {
        btn.removeClass('disabled').html(originalContent);
        Swal.fire('Error', 'Failed to update request.', 'error');
      }
    });
  });

  // Quick Approve Logic
  $(document).on('click', '.quick-leave-approve', function () {
    const id = $(this).data('id');
    Swal.fire({
      title: 'Approve Leave?',
      text: 'Are you sure you want to quickly approve this request?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, Approve',
      confirmButtonColor: '#00695c',
      cancelButtonColor: '#94a3b8'
    }).then((result) => {
      if (result.isConfirmed) {
        performQuickAction(id, 'approved', 'Quick approved from table.');
      }
    });
  });

  // Quick Reject Logic
  $(document).on('click', '.quick-leave-reject', function () {
    const id = $(this).data('id');
    Swal.fire({
      title: 'Reject Leave?',
      text: 'Please provide a reason for rejection:',
      input: 'textarea',
      inputPlaceholder: 'Enter remarks here...',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Submit Rejection',
      confirmButtonColor: '#ff4d4d',
      preConfirm: (value) => {
        if (!value) {
          Swal.showValidationMessage('Remarks are required for rejection!');
        }
        return value;
      }
    }).then((result) => {
      if (result.isConfirmed) {
        performQuickAction(id, 'rejected', result.value);
      }
    });
  });

  // Core AJAX function for quick actions
  function performQuickAction(id, status, notes) {
    $.ajax({
      url: `${baseUrl}leaveRequests/actionAjax`,
      type: 'POST',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        id: id,
        status: status,
        adminNotes: notes
      },
      success: function (response) {
        Swal.fire({
          title: 'Success!',
          text: `Leave request has been ${status}.`,
          icon: 'success',
          timer: 2000,
          showConfirmButton: false
        });
        if (dtLeaveRequests) dtLeaveRequests.ajax.reload();

        // Refresh stats cards
        setTimeout(() => { location.reload(); }, 2000);
      },
      error: function (xhr) {
        Swal.fire('Error', 'Failed to process request. Please try again.', 'error');
      }
    });
  }

  // Re-initialize lightbox when modal opens
  $('#modalLeaveRequestDetails').on('shown.bs.modal', function () {
    if (typeof lightbox !== 'undefined') lightbox.reload();
  });
});
