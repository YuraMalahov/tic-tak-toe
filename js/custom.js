
$(document).ready(function () {

  $('body').on('click', '#field td', function () {
    if (isEmpty(this)) {
      var data = getCoordinates(this);

      send(data, function (result) {
        var res = JSON.parse(result);

        drawTable(res.data);

        if (res.winner) {
          winner(res.winner);
        }
      });
    }
  });

  $('#reset').on('click', function () {
    reset();
  });

  init(function (result) {
    drawTable(result);
  });

  function reset() {
    $.ajax({
      url: 'app/',
      type: 'DELETE',
      success: function (result) {
        var res = JSON.parse(result);

        drawTable(res.data);

        if (res.winner) {
          winner(res.winner);
        }
      }
    });
  }

  function winner(name) {
    alert('Winner: ' + name);

    reset();
  }

  function init(cb) {
    $.ajax({
      url: 'app/',
      type: 'GET',
      success: function (result) {
        var res = JSON.parse(result);

        cb(res.data);
      }
    });
  }

  function drawTable(data) {
    var result = '<table>';

    for (var i = 0; i < data.length; i++) {
      result += '<tr data-y="' + i + '">';

      for (var j = 0; j < data[i].length; j++) {
        result += '<td data-x="' + j + '">' + getCellView(data[i][j]) + '</td>';
      }
      result += '</tr>';
    }
    result += '</table>';

    $('#field').empty().append(result);
  }

  function getCellView(value) {
    if (value === 1) {
      return '<img src="images/tic.png">';
    } else if (value === -1) {
      return '<img src="images/tac.png">';
    }

    return '';
  }

  function isEmpty(element) {
    return $(element).children().length === 0 ? true : false;
  }

  function getCoordinates(element) {
    var data = {};

    data.x = $(element).data('x');
    data.y = $(element).closest('tr').data('y');

    return data;
  }

  function send(data, cb) {
    $.ajax({
      url: 'app/',
      type: 'POST',
      data: data,
      success: function (result) {
        cb(result);
      }
    });
  }

});