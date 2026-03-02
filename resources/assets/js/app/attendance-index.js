/* Attendance Index */

'use strict';

$(function () {
  console.log('Attendance Index');

  var dataTable = $('#attendanceTable').DataTable({
    processing: true,
    serverSide: true,
    dom: 'rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>', // Remove default search/length
    ajax: {
      url: 'attendance/indexAjax',
      data: function data(d) {
        d.userId = $('#userId').val();
        d.date = $('#date').val();
        d.shiftId = $('#shiftId').val();
        d.teamId = $('#teamId').val();
        d.searchTerm = $('#customSearchInput').val();
      }
    },
    columns: [
      { data: 'user', name: 'user' },
      { data: 'date', name: 'date' },
      { data: 'shift', name: 'shift' },
      { data: 'check_in_time', name: 'check_in_time' },
      { data: 'check_out_time', name: 'check_out_time' },
      { data: 'working_hours', name: 'working_hours' },
      { data: 'status', name: 'status' },
      { data: 'actions', name: 'actions' }
    ],
    columnDefs: [
      { targets: [3, 4, 5, 6, 7], className: 'text-start' },
      { targets: 7, className: 'status-col' }
    ],
  });

  $('#userId, #shiftId, #teamId').select2();

  $('#userId, #date, #shiftId, #teamId').on('change', function () {
    dataTable.draw();
  });

  $('#customSearchBtn').on('click', function () {
    dataTable.draw();
  });

  $('#customSearchInput').on('keyup', function (e) {
    if (e.key === 'Enter') {
      dataTable.draw();
    }
  });

  $('#customLengthMenu').on('change', function () {
    dataTable.page.len($(this).val()).draw();
  });

  // Chart Logic
  $('#chartTeamFilter, #chartPeriod, #chartUserFilter').select2();

  $('#chartTeamFilter, #chartPeriod, #chartUserFilter').on('change', function () {
    refreshChart();
  });
});

function refreshChart() {
  const teamId = $('#chartTeamFilter').val();
  const period = $('#chartPeriod').val();
  const userId = $('#chartUserFilter').val();

  console.log('Refreshing chart:', { teamId, period, userId });

  if (window.attendanceChart) {
    let categories = [];
    let dataPoints = 7;

    switch (period) {
      case 'today':
      case 'yesterday':
        categories = ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '23:59'];
        dataPoints = 7;
        break;
      case '7days':
        categories = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        dataPoints = 7;
        break;
      case '1month':
        categories = ['Wk 1', 'Wk 2', 'Wk 3', 'Wk 4'];
        dataPoints = 4;
        break;
      case '3months':
        categories = ['Jan', 'Feb', 'Mar']; // Mock months
        dataPoints = 3;
        break;
      case '1year':
        categories = ['Q1', 'Q2', 'Q3', 'Q4'];
        dataPoints = 4;
        break;
      default:
        categories = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        dataPoints = 7;
    }

    const newVal1 = Array.from({ length: dataPoints }, () => Math.floor(Math.random() * 40) + 40);
    const newVal2 = Array.from({ length: dataPoints }, () => Math.floor(Math.random() * 20) + 5);

    window.attendanceChart.updateOptions({
      xaxis: {
        categories: categories
      }
    });

    window.attendanceChart.updateSeries([{
      name: 'Present',
      data: newVal1
    }, {
      name: 'Absent',
      data: newVal2
    }]);
  }
}
