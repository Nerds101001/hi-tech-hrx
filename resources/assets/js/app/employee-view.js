'use strict';
let baseUrl = $('html').attr('data-base-url') + '/';

$(function () {
  var basicInfoForm = $('#basicInfoForm');
  var workInfoForm = $('#workInfoForm');
  var profilePictureForm = $('#profilePictureForm');

  //Sales Targets
  $('#period').datepicker({
    format: 'yyyy',
    viewMode: 'years',
    minViewMode: 'years',
    autoclose: true,
    clearBtn: true,
    startDate: new Date(new Date().getFullYear(), 0, 1)
  });

  $('#incentiveType').on('change', function () {
    var value = this.value;
    if (value === 'none') {
      $('#amountDiv').hide();
      $('#percentageDiv').hide();
    } else if (value === 'fixed') {
      $('#amountDiv').show();
      $('#percentageDiv').hide();
    } else if (value === 'percentage') {
      $('#amountDiv').hide();
      $('#percentageDiv').show();
    } else {
      $('#amountDiv').hide();
      $('#percentageDiv').hide();
    }
  });

  $(document).on('click', '.edit-target', function () {
    var targetId = $(this).data('id');

    fetch(`${baseUrl}employees/getTargetByIdAjax/${targetId}`)
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        var target = data.data;
        $('#targetId').val(target.id);
        $('#period').val(target.period);
        $('#targetType').val(target.target_type).trigger('change');
        $('#targetAmount').val(target.target_amount);
        $('#incentiveAmount').val(target.incentive_amount);
        $('#incentivePercentage').val(target.incentive_percentage);
        $('#incentiveType').val(target.incentive_type).trigger('change');
      });

    console.log(targetId);
  });

  window.editAdjustment = function (adjustment) {
    console.log("Editing adjustment:", adjustment);
    $('.modal-title-hitech').text('Edit Payroll Adjustment');
    $('#adjustmentId').val(adjustment.id);
    $('#adjustmentName').val(adjustment.name);
    $('#adjustmentCode').val(adjustment.code || '');
    $('#adjustmentType').val(adjustment.type).trigger('change');
    $('#adjustmentAmount').val(adjustment.amount);
    $('#adjustmentPercentage').val(adjustment.percentage);
    $('#adjustmentNotes').val(adjustment.notes);

    if (adjustment.amount > 0) {
      $('#adjustmentCategory').val('fixed').trigger('change');
    } else {
      $('#adjustmentCategory').val('percentage').trigger('change');
    }

    $('#adjustmentSubmitBtn').text('Update Adjustment');
    var myModal = new bootstrap.Modal(document.getElementById('offcanvasPayrollAdjustment'));
    myModal.show();
  };

  $('#adjustmentCategory').on('change', function () {
    var val = $(this).val();
    console.log("Adjustment category changed to:", val);
    if (val === 'percentage') {
      $('#percentageDiv').attr('style', 'display: block !important;');
      $('#amountDiv').attr('style', 'display: none !important;');
      $('#adjustmentAmount').val('');
    } else {
      $('#amountDiv').attr('style', 'display: block !important;');
      $('#percentageDiv').attr('style', 'display: none !important;');
      $('#adjustmentPercentage').val('');
    }
  });

  $('#addPayrollAdjustment').on('click', function () {
    console.log("Adding new adjustment");
    $('.modal-title-hitech').text('Add Payroll Adjustment');
    $('#adjustmentId').val('');
    $('#adjustmentName').val('');
    $('#adjustmentCode').val('');
    $('#adjustmentAmount').val('');
    $('#adjustmentPercentage').val('');
    $('#adjustmentCategory').val('fixed').trigger('change');
    $('#adjustmentNotes').val('');
    $('#adjustmentSubmitBtn').text('Add Adjustment');
  });


  //Sales Targets

  var userRole = role;

  $('#ipGroupDiv').hide();
  $('#qrGroupDiv').hide();
  $('#dynamicQrDiv').hide();
  $('#siteDiv').hide();
  $('#geofenceGroupDiv').hide();
  $('#dynamicQrDiv').hide();

  if (attendanceType !== 'open') {
    console.log('Attendance Type: ' + attendanceType);
    switch (attendanceType) {
      case 'geofence':
        $('#geofenceGroupDiv').show();
        getGeofenceGroups();
        break;
      case 'ip_address':
        $('#ipGroupDiv').show();
        getIpGroups();
        break;
      case 'qr_code':
        $('#qrGroupDiv').show();
        getQrGroups();
        break;
      case 'site':
        $('#siteDiv').show();
        getSites();
        break;
      case 'dynamic_qr':
        $('#dynamicQrDiv').show();
        getDynamicQrDevices();
        break;
      default:
        break;
    }
  }

  $('#attendanceType').on('change', function () {
    var value = this.value;
    console.log(value);

    $('#ipGroupDiv').hide();
    $('#qrGroupDiv').hide();
    $('#dynamicQrDiv').hide();
    $('#siteDiv').hide();
    $('#geofenceGroupDiv').hide();
    $('#dynamicQrDiv').hide();

    if (value === 'geofence') {
      $('#geofenceGroupDiv').show();
      getGeofenceGroups();
    } else if (value === 'ipAddress') {
      $('#ipGroupDiv').show();
      getIpGroups();
    } else if (value === 'staticqr') {
      $('#qrGroupDiv').show();
      getQrGroups();
    } else if (value == 'site') {
      $('#siteDiv').show();
      getSites();
    } else if (value == 'dynamicqr') {
      $('#dynamicQrDiv').show();
      getDynamicQrDevices();
    } else {
      $('#geofenceGroupDiv').hide();
      $('#ipGroupDiv').hide();
      $('#qrGroupDiv').hide();
      $('#siteDiv').hide();
      $('#dynamicQrDiv').hide();
    }
  });

  window.loadSelectList = async function () {
    try {
      var roleSelector = $('#role'),
        teamSelector = $('#teamId'),
        shiftSelector = $('#shiftId'),
        reportingToSelector = $('#reportingToId'),
        designationSelector = $('#designationId');

      // Show loading state if needed
      [roleSelector, teamSelector, shiftSelector, reportingToSelector, designationSelector].forEach(s => s.prop('disabled', true));

      // Fetch all data in parallel
      const [roles, teams, shifts, reportingUsers, designations] = await Promise.all([
        getRoles(),
        getTeams(),
        getShifts(),
        getReportingToUsers(),
        getDesignations()
      ]);

      // Re-enable selectors
      [roleSelector, teamSelector, shiftSelector, reportingToSelector, designationSelector].forEach(s => s.prop('disabled', false));

      // Populate Roles
      roleSelector.empty().append('<option value="">Select Role</option>');
      roles.forEach(role => {
        roleSelector.append(`<option value="${role.name}" ${userRole === role.name ? 'selected' : ''}>${role.name}</option>`);
      });

      // Populate Teams
      teamSelector.empty().append('<option value="">Select Team</option>');
      teams.forEach(team => {
        teamSelector.append(`<option value="${team.id}" ${team.id === user.team_id ? 'selected' : ''}>${team.code}-${team.name}</option>`);
      });

      // Populate Shifts
      shiftSelector.empty().append('<option value="">Select Shift</option>');
      shifts.forEach(shift => {
        shiftSelector.append(`<option value="${shift.id}" ${shift.id === user.shift_id ? 'selected' : ''}>${shift.code}-${shift.name}</option>`);
      });

      // Populate Reporting To
      reportingToSelector.empty().append('<option value="">Select Reporting To</option>');
      reportingUsers.filter(u => u.id !== user.id).forEach(u => {
        reportingToSelector.append(`<option value="${u.id}" ${u.id === user.reporting_to_id ? 'selected' : ''}>${u.first_name} ${u.last_name}</option>`);
      });

      // Populate Designations
      designationSelector.empty().append('<option value="">Select Designation</option>');
      designations.forEach(d => {
        designationSelector.append(`<option value="${d.id}" ${d.id === user.designation_id ? 'selected' : ''}>${d.name}</option>`);
      });

      // Initialize/Refresh Select2
      const s2Config = { dropdownParent: $('#offcanvasEditWorkInfo'), width: '100%' };
      roleSelector.select2(s2Config);
      teamSelector.select2(s2Config);
      shiftSelector.select2(s2Config);
      reportingToSelector.select2(s2Config);
      designationSelector.select2(s2Config);

      setupWorkInfoFormValidator();
    } catch (err) {
      console.error('Error loading selects:', err);
    }
  };

  window.setupWorkInfoFormValidator = function () {
    console.log('Loading Work Info form validator');
    var workInfoForm = document.getElementById('workInfoForm');
    if (workInfoForm) {
      var fv = FormValidation.formValidation(workInfoForm, {
        fields: {
          role: {
            validators: {
              notEmpty: {
                message: 'The Role is required'
              }
            }
          },
          teamId: {
            validators: {
              notEmpty: {
                message: 'The Team is required'
              }
            }
          },
          shiftId: {
            validators: {
              notEmpty: {
                message: 'The Shift is required'
              }
            }
          },
          designationId: {
            validators: {
              notEmpty: {
                message: 'The Designation is required'
              }
            }
          },
          doj: {
            validators: {
              notEmpty: {
                message: 'The Joining Date is required'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          /* bootstrap5: new FormValidation.plugins.Bootstrap5({
             // Use this for enabling/changing valid/invalid class
             eleValidClass: '',
             rowSelector: function (field, ele) {
               return '.mb-6';
             }
           }),*/
          submitButton: new FormValidation.plugins.SubmitButton(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        }
      }).on('core.form.valid', function () {
        console.log('Form Submitted');
        workInfoForm.submit();
      });
    }

    console.log('Form validator loaded!');
  };

  window.loadEditBasicInfo = function () {
    console.log('Loading Basic Info');

    var basicInfoForm = document.getElementById('basicInfoForm');

    $('#gender').select2({
      dropdownParent: basicInfoForm
    });

    var fv = FormValidation.formValidation(basicInfoForm, {
      fields: {
        firstName: {
          validators: {
            notEmpty: {
              message: 'The First name is required'
            },
            stringLength: {
              min: 2,
              max: 30,
              message: 'The name must be more than 3 and less than 30 characters long'
            },
            regexp: {
              regexp: /^[a-zA-Z0-9 ]+$/,
              message: 'The name can only consist of alphabetical, number and space'
            }
          }
        },
        lastName: {
          validators: {
            notEmpty: {
              message: 'The last name is required'
            }
          }
        },
        email: {
          validators: {
            notEmpty: {
              message: 'The Email is required'
            },
            emailAddress: {
              message: 'The value is not a valid email address'
            },
            remote: {
              url: `${baseUrl}employees/checkEmailValidationAjax`,
              message: 'The email is already taken',
              method: 'GET',
              data: function () {
                return {
                  id: basicInfoForm.querySelector('[name="id"]').value
                };
              }
            }
          }
        },
        phone: {
          validators: {
            notEmpty: {
              message: 'The Phone is required'
            },
            stringLength: {
              min: 10,
              max: 10,
              message: 'The Phone must be 10 characters long'
            },
            remote: {
              url: `${baseUrl}employees/checkPhoneValidationAjax`,
              message: 'The phone is already taken',
              method: 'GET',
              data: function () {
                return {
                  id: basicInfoForm.querySelector('[name="id"]').value
                };
              }
            }
          }
        },
        altPhone: {
          validators: {
            stringLength: {
              min: 10,
              max: 10,
              message: 'The Phone must be 10 characters long'
            }
          }
        },
        gender: {
          validators: {
            notEmpty: {
              message: 'Please choose'
            }
          }
        },
        dob: {
          validators: {
            notEmpty: {
              message: 'The Date of Birth is required'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          eleValidClass: '',
          rowSelector: function (field, ele) {
            return '.mb-6';
          }
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      }
    }).on('core.form.valid', function () {
      console.log('Form Submitted');
      basicInfoForm.submit();
    });

    console.log('Form validator loaded!');
  };

  window.toggleUploadForm = function (formId) {
    const form = document.getElementById(formId);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
  };

  //Profile Update
  const profilePictureInput = document.getElementById('file');
  const changeProfilePictureButton = document.getElementById('changeProfilePictureButton');

  changeProfilePictureButton.addEventListener('click', function () {
    profilePictureInput.click();
  });

  profilePictureInput.addEventListener('change', function () {
    console.log('Profile Picture Changed');
    if (profilePictureInput.files.length > 0) {
      $(profilePictureForm).submit();
    }
  });

  var maritalStatusSelector = $('#maritalStatus');

  var maritalStatus = maritalStatusSelector.val();
  if (maritalStatus === 'married') {
    $('#marriedDiv').show();
  } else {
    $('#marriedDiv').hide();
  }

  maritalStatusSelector.on('change', function () {
    var maritalStatus = $(this).val();
    if (maritalStatus === 'married') {
      $('#marriedDiv').show();
    } else {
      $('#marriedDiv').hide();
    }
  });

  // Additional Management Control Logic
  window.approveOnboarding = function (userId) {
    Swal.fire({
      html: `
              <div class="text-center mb-4">
                  <div class="mx-auto bg-label-success rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                      <i class="bx bx-check-shield text-success" style="font-size: 3rem;"></i>
                  </div>
                  <h4 class="mb-2 fw-bold text-dark">Approve Onboarding?</h4>
                  <p class="text-muted small mb-0">This will move the employee to ACTIVE status.</p>
              </div>
          `,
      showCancelButton: true,
      confirmButtonText: 'Yes, Approve',
      cancelButtonText: 'Cancel',
      customClass: {
        popup: 'rounded-4 shadow-lg border-0',
        confirmButton: 'btn btn-success rounded-pill px-4 fw-bold shadow-sm',
        cancelButton: 'btn btn-light rounded-pill px-4 fw-bold ms-3'
      },
      buttonsStyling: false,
      showCloseButton: false
    }).then((result) => {
      if (result.isConfirmed) {
        $.post(`${baseUrl}employees/onboarding/approve/${userId}`, { _token: $('meta[name="csrf-token"]').attr('content') }, function (response) {
          Swal.fire({
            html: `
                    <div class="text-center">
                        <div class="mx-auto bg-label-success rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bx bx-check text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-0 fw-bold text-dark">Approved!</h4>
                    </div>
                `,
            timer: 1500,
            showConfirmButton: false,
            customClass: { popup: 'rounded-4 shadow-lg border-0' }
          });
          setTimeout(() => location.reload(), 1500);
        }).fail((xhr) => {
          console.error("Approve Error:", xhr);
          Swal.fire('Error', xhr.responseJSON?.message || 'Unable to approve onboarding', 'error');
        });
      }
    });
  };

  window.requestModification = function (userId) {
    Swal.fire({
      html: `
              <div class="text-center mb-4">
                  <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; background-color: #fff7ed;">
                      <i class="bx bx-edit-alt" style="font-size: 3rem; color: #ea580c;"></i>
                  </div>
                  <h4 class="mb-2 fw-bold text-dark">Request Modification</h4>
                  <p class="text-muted small mb-0">A notification will be sent to the employee to update their details.</p>
              </div>
          `,
      input: 'textarea',
      inputPlaceholder: 'Type your feedback here...',
      showCancelButton: true,
      confirmButtonText: 'Send Request',
      cancelButtonText: 'Cancel',
      customClass: {
        popup: 'rounded-4 shadow-lg border-0',
        confirmButton: 'btn rounded-pill px-4 fw-bold text-white shadow-sm ms-3 mt-3',
        cancelButton: 'btn btn-light rounded-pill px-4 fw-bold mt-3'
      },
      didOpen: () => {
        const confirmBtn = Swal.getConfirmButton();
        confirmBtn.style.backgroundColor = '#f97316';
        confirmBtn.style.borderColor = '#ea580c';
      },
      buttonsStyling: false,
      showCloseButton: false
    }).then((result) => {
      if (result.isConfirmed) {
        $.post(`${baseUrl}employees/onboarding/resubmit/${userId}`, {
          _token: $('meta[name="csrf-token"]').attr('content'),
          notes: result.value
        }, function (response) {
          Swal.fire({
            html: `
                  <div class="text-center">
                      <div class="mx-auto bg-label-success rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                          <i class="bx bx-check text-success" style="font-size: 3rem;"></i>
                      </div>
                      <h4 class="mb-0 fw-bold text-dark">Feedback Sent!</h4>
                      <p class="text-muted small">Employee has been notified to modify their details.</p>
                  </div>
              `,
            timer: 2000,
            showConfirmButton: false,
            customClass: { popup: 'rounded-4 shadow-lg border-0' }
          });
          setTimeout(() => location.reload(), 2000);
        }).fail((xhr) => {
          console.error("Modification Error:", xhr);
          Swal.fire('Error', xhr.responseJSON?.message || 'Unable to send modification request', 'error');
        });
      }
    });
  };

  window.viewTaskDetails = function (title, description, due, status) {
    let statusBadge = '';
    switch (status.toLowerCase()) {
      case 'new': statusBadge = '<span class="badge bg-label-info">New</span>'; break;
      case 'in progress': statusBadge = '<span class="badge bg-label-warning">In Progress</span>'; break;
      case 'completed': statusBadge = '<span class="badge bg-label-success">Completed</span>'; break;
      case 'closed': statusBadge = '<span class="badge bg-label-secondary">Closed</span>'; break;
      case 'late': statusBadge = '<span class="badge bg-label-danger">Late</span>'; break;
      default: statusBadge = `<span class="badge bg-label-primary">${status}</span>`;
    }

    Swal.fire({
      html: `
              <div class="text-start p-2">
                  <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                      <h4 class="fw-bold mb-0" style="color:#1e293b;">${title}</h4>
                      ${statusBadge}
                  </div>
                  <div class="mb-4">
                      <span class="d-block text-muted text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">DESCRIPTION</span>
                      <p class="text-dark small" style="line-height:1.6;">${description || 'No description provided.'}</p>
                  </div>
                  <div class="bg-light p-3 rounded-3 d-flex align-items-center border">
                      <div class="bg-white p-2 rounded me-3 shadow-sm"><i class="bx bx-calendar text-primary fs-4"></i></div>
                      <div>
                          <span class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.6rem; letter-spacing: 1px;">DUE DATE</span>
                          <span class="text-dark fw-bold small">${due}</span>
                      </div>
                  </div>
              </div>
          `,
      showConfirmButton: true,
      confirmButtonText: 'Close',
      customClass: {
        popup: 'rounded-4 shadow-lg border-0',
        confirmButton: 'btn btn-primary rounded-pill px-4 fw-bold shadow-sm'
      },
      buttonsStyling: false
    });
  };

  window.viewDocumentPopup = function (url, title) {
    Swal.fire({
      title: title,
      html: `
            <div class="p-2" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(10px); border-radius: 16px;">
                <iframe src="${url}" style="width:100%; height:450px; border:none; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);"></iframe>
            </div>
        `,
      width: '800px',
      showCloseButton: true,
      showConfirmButton: false,
      customClass: {
        popup: 'rounded-4 shadow-lg border-0 glass-morphism',
        title: 'fw-extrabold text-dark pt-4'
      }
    });
  };

  window.viewDocumentNumber = function (title, number) {
    Swal.fire({
      html: `
            <div class="text-center py-3">
                <div class="mx-auto bg-label-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                    <i class="bx bx-id-card text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h6 class="text-muted text-uppercase smallest fw-bold mb-1">${title}</h6>
                <h3 class="fw-extrabold text-dark mb-0">${number}</h3>
            </div>
        `,
      showConfirmButton: true,
      confirmButtonText: 'Got it',
      customClass: {
        popup: 'rounded-4 shadow-lg border-0',
        confirmButton: 'btn btn-primary rounded-pill px-5 fw-bold'
      },
      buttonsStyling: false
    });
  };
});
