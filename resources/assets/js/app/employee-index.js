/**
 * Page User List
 */

'use strict';

// Datatable (jquery)
$(function () {
  // Variable declaration for table
  var dt_user_table = $('.datatables-users'),
    userView = baseUrl + 'account/viewUser/',
    employeeView = baseUrl + 'employees/view/',
    offCanvasForm = $('#offcanvasAddUser');

  var statusObj = {
    inactive: { title: 'Inactive', class: 'bg-label-warning' },
    pending: { title: 'Pending', class: 'bg-label-warning' },
    active: { title: 'Active', class: 'bg-label-success' },
    retired: { title: 'Inactive', class: 'bg-label-secondary' },
    onboarding: { title: 'Onboarding', class: 'bg-label-info' },
    onboarding_submitted: { title: 'Review Required', class: 'bg-label-warning' },
    relieved: { title: 'Relieved', class: 'bg-label-danger' },
    terminated: { title: 'Terminated', class: 'bg-label-danger' },
    probation: { title: 'Probation', class: 'bg-label-primary' },
    resigned: { title: 'Resigned', class: 'bg-label-danger' },
    suspended: { title: 'Suspended', class: 'bg-label-danger' },
    default: { title: 'Unknown', class: 'bg-label-secondary' }
  };

  // ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  //set on change on all filter and redraw datatable
  $('#roleFilter, #teamFilter, #designationFilter, #statusFilter').on('change', function () {
    console.log('filter changed to ' + $(this).val());
    dt_user.draw();
  });

  //Initialize select2
  $('#roleFilter, #teamFilter, #designationFilter, #statusFilter').select2({});

  // Users datatable
  if (dt_user_table.length) {
    var dt_user = dt_user_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + 'employees/indexAjax',
        type: 'POST',
        data: function (d) {
          d.roleFilter = $('#roleFilter').val();
          d.teamFilter = $('#teamFilter').val();
          d.designationFilter = $('#designationFilter').val();
          d.statusFilter = $('#statusFilter').val();
        }
      },
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'id' },
        { data: 'name' },
        { data: 'code' },
        { data: 'team' },
        { data: 'designation' },
        { data: 'status' },
        { data: 'joined' },
        { data: '' }
      ],
      columnDefs: [
        {
          // For Responsive Control Toggle (Hidden since we use native scrolling)
          className: 'control',
          searchable: false,
          orderable: false,
          visible: false,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          targets: 1,
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return `<span>${meta.row + 1}</span>`;
          }
        },
        {
          // User full name
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $name = full['name'];
            var $email = full['email'];

            // For Avatar badge
            var stateNum = Math.floor(Math.random() * 6);
            var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
            var $state = states[stateNum],
              $initials = $name.match(/\b\w/g) || [],
              $output;
            if (full['profile_picture']) {
              $output = '<img src="' + full['profile_picture'] + '" alt="Avatar" class="avatar rounded-circle " />';
            } else {
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial-hitech">' + $initials + '</span>';
            }

            // Creates full output for row
            return (
              '<div class="d-flex justify-content-start align-items-center user-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar avatar-sm me-3">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<a href="' +
              employeeView +
              full['id'] +
              '" class="text-heading text-truncate"><span class="fw-medium mb-0" style="font-size: 0.875rem;">' +
              $name +
              '</span></a>' +
              '<small class="text-muted" style="font-size: 0.75rem;">' +
              $email +
              '</small>' +
              '</div>' +
              '</div>'
            );
          }
        },
        {
          // Employee ID
          targets: 3,
          render: function (data, type, full, meta) {
            return '<span class="badge badge-code-hitech">' + (full['code'] || 'N/A') + '</span>';
          }
        },
        {
          //Department
          targets: 4,
          render: function (data, type, full, meta) {
            return '<span class="text-body">' + (full['team'] || 'N/A') + '</span>';
          }
        },
        {
          //Designation
          targets: 5,
          render: function (data, type, full, meta) {
            return '<span class="text-body">' + (full['designation'] || 'N/A') + '</span>';
          }
        },
        {
          //Status
          targets: 6,
          render: function (data, type, full, meta) {
            var $status = full['status'];

            var statusInfo = statusObj[$status] || statusObj['default'];
            return (
              '<span class="badge bg-teal-light text-teal rounded-pill px-3 py-1 fw-bold">' +
              statusInfo.title +
              '</span>'
            );
          }
        },
        {
          //Joined
          targets: 7,
          render: function (data, type, full, meta) {
            return '<span class="text-body">' + (full['joined'] || 'N/A') + '</span>';
          }
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-flex align-items-center justify-content-center gap-2">' +
              `<a class="icon-sophisticated view" data-id="${full['id']}" href="${employeeView + full['id']}" title="View"><i class="bx bx-show"></i></a>` +
              `<a class="icon-sophisticated edit edit-record" data-id="${full['id']}" href="javascript:;" title="Edit"><i class="bx bx-edit"></i></a>` +
              `<a class="icon-sophisticated reset-password" data-id="${full['id']}" href="javascript:;" title="Reset Password"><i class="bx bx-key"></i></a>` +
              `<a class="icon-sophisticated deactivate delete-record" data-id="${full['id']}" href="javascript:;" title="Deactivate"><i class="bx bx-lock-alt"></i></a>` +
              '</div>'
            );
          }
        }
      ],
      order: [[1, 'desc']],
      dom: 't<"d-flex justify-content-between align-items-center mx-3 mt-4 mb-2" <"small text-muted" i> <"pagination-wrapper" p>>',
      lengthMenu: [10, 25, 50, 100],
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        info: 'Showing _START_ to _END_ of _TOTAL_ employees',
        paginate: {
          next: 'Next',
          previous: 'Previous'
        }
      },
      // Buttons with Dropdown
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-secondary dropdown-toggle mx-4 d-none',
          text: '<i class="bx bx-export me-2 bx-sm"></i>Export',
          buttons: [
            {
              extend: 'print',
              title: 'Users',
              text: '<i class="bx bx-printer me-2" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be print
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function (win) {
                //customize print view for dark
                $(win.document.body)
                  .css('color', config.colors.headingColor)
                  .css('border-color', config.colors.borderColor)
                  .css('background-color', config.colors.body);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              title: 'Users',
              text: '<i class="bx bx-file me-2" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be print
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'excel',
              text: '<i class="bx bxs-file-export me-2"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'pdf',
              title: 'Users',
              text: '<i class="bx bxs-file-pdf me-2"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'copy',
              title: 'Users',
              text: '<i class="bx bx-copy me-2" ></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be copy
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
          ]
        },
        {
          text: '<i class="bx bx-plus bx-sm me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add New</span>',
          className: 'add-new btn btn-primary',
          action: function () {
            window.open('employees/create', '_self');
          }
        }
      ],
      // Native Horizontal Scroll
      scrollX: true
    });
    // To remove default btn-secondary in export buttons
    $('.dt-buttons > .btn-group > button').removeClass('btn-secondary');

    // Custom Search Functionality
    $('#customSearchBtn').on('click', function () {
      dt_user.search($('#customSearchInput').val()).draw();
    });

    $('#customSearchInput').on('keyup', function (e) {
      if (e.key === 'Enter') {
        dt_user.search($(this).val()).draw();
      }
    });

    // Custom Length Menu
    $('#customLengthMenu').on('change', function () {
      dt_user.page.len($(this).val()).draw();
    });
  }

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var user_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // sweetalert for confirmation of delete
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        // delete the data
        $.ajax({
          type: 'DELETE',
          url: `${baseUrl}employees/deleteEmployeeAjax/${user_id}`,
          success: function () {
            // success sweetalert
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'The user has been deleted!',
              customClass: {
                confirmButton: 'btn btn-success'
              }
            });
            dt_user.draw();
          },
          error: function (error) {
            console.log(error);
          }
        });
      }
    });
  });
  // reset password
  $(document).on('click', '.reset-password', function () {
    var user_id = $(this).data('id');

    Swal.fire({
      title: 'Are you sure?',
      text: "This will reset the user's password to the default: 123456",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, reset it!',
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        $.ajax({
          type: 'POST',
          url: `${baseUrl}employees/resetPasswordAjax`,
          data: {
            id: user_id,
            _token: $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            Swal.fire({
              icon: 'success',
              title: 'Reset!',
              text: 'Password has been reset successfully.',
              customClass: {
                confirmButton: 'btn btn-success'
              }
            });
          },
          error: function (error) {
            console.log(error);
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Something went wrong while resetting the password.',
              customClass: {
                confirmButton: 'btn btn-danger'
              }
            });
          }
        });
      }
    });
  });

  // edit record
  $(document).on('click', '.edit-record', function () {
    var user_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // changing the title of offcanvas
    $('#offcanvasAddUserLabel').html('Edit User');

    // get data
    $.get(`${baseUrl}employee\/editUserAjax\/${user_id}`, function (data) {
      console.log(data);
      $('#userId').val(data.id);
      $('#firstName').val(data.firstName);
      $('#lastName').val(data.lastName);
      $('#email').val(data.email);
      $('#phone').val(data.phone);
      $('#role').val(data.role);
    });
  });

  // changing the title
  $('.add-new').on('click', function () {
    $('#userId').val(''); //reseting input field
    $('#offcanvasAddUserLabel').html('Add User');
    // loadRoles();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);

});
