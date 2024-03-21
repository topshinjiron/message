$(function () {

  // スクロールバーの横幅を取得
  $('html').append('<div class="scrollbar" style="overflow:scroll;"></div>');
  var scrollsize = window.innerWidth - $('.scrollbar').prop('clientWidth');
  $('.scrollbar').hide();

  // 「.modal-open」をクリック
  $('.modal-open').click(function () {
    $('.textarea').addClass('open');
    $('#modal-open').css('display', 'block');

    // html、bodyを固定（overflow:hiddenにする）
    $('html, body').addClass('lock');

    // オーバーレイ用の要素を追加
    $('body').append('<div class="modal-overlay"></div>');

    // オーバーレイをフェードイン
    $('.modal-overlay').fadeIn(200);


    // モーダルコンテンツのIDを取得
    var modal = '#' + $(this).attr('data-target');

    // モーダルコンテンツを囲む要素を追加
    $(modal).wrap("<div class='modal-wrap'></div>");

    // モーダルコンテンツを囲む要素を表示
    $('.modal-wrap').show();

    // モーダルコンテンツの表示位置を設定
    modalResize();

    // モーダルコンテンツフェードイン
    $(modal).fadeIn(200);

    // モーダルコンテンツをクリックした時はフェードアウトしない
    $(modal).click(function (e) {
      e.stopPropagation();
    });

    // 「.modal-overlay」あるいは「.modal-close」をクリック
    $('.modal-wrap, .modal-close,button[type=submit].save').off().click(function () {
      // モーダルコンテンツとオーバーレイをフェードアウト
      $('.textarea').removeClass('open');
      $(modal).fadeOut('fast');
      //$(".modal-wrap").hide();
      $(modal).unwrap();
      $('.modal-overlay').fadeOut('fast', function () {
        // html、bodyの固定解除
        $('html, body').removeClass('lock');
        // オーバーレイを削除
        $('.modal-overlay').remove();
        // モーダルコンテンツを囲む要素を削除
        // $(modal).unwrap("<div class='modal-wrap'></div>");
      });
    });

    $('button[type=submit].save,button[type=button].save').off().click(function () {
      if($('.messageField').val() !== '' && $('.writerField').val() !== '' && $('.startDate').val() !== '' && $('.endField').val() !== ''){
      // モーダルコンテンツとオーバーレイをフェードアウト
      $('.textarea').removeClass('open');
      $(modal).fadeOut('fast');
      //$(".modal-wrap").hide();
      $(modal).unwrap();
      $('main').prepend('<div class="alart">登録しました</div>');
      setTimeout(function () {
        $('.alart').fadeOut();
        setTimeout(function () {
          $('.alart').remove();
        }, 1000);
      }, 3000);

      $('.modal-overlay').fadeOut('fast', function () {
        // html、bodyの固定解除
        $('html, body').removeClass('lock');
        // オーバーレイを削除
        $('.modal-overlay').remove();
        // モーダルコンテンツを囲む要素を削除
        // $(modal).unwrap("<div class='modal-wrap'></div>");
      });
    }
    });

    // リサイズしたら表示位置を再取得
    $(window).on('resize', function () {
      modalResize();
    });

    // モーダルコンテンツの表示位置を設定する関数
    function modalResize() {
      // ウィンドウの横幅、高さを取得
      var w = $(window).width();
      var h = $(window).height();

      // モーダルコンテンツの横幅、高さを取得
      var mw = $(modal).outerWidth(true);
      var mh = $(modal).outerHeight(true);

      // モーダルコンテンツの表示位置を設定
      if ((mh > h) && (mw > w)) {
        $(modal).css({
          'left': 0 + 'px',
          'top': 0 + 'px'
        });
      } else if ((mh > h) && (mw < w)) {
        var x = (w - scrollsize - mw) / 2;
        $(modal).css({
          'left': x + 'px',
          'top': 0 + 'px'
        });
      } else if ((mh < h) && (mw > w)) {
        var y = (h - scrollsize - mh) / 2;
        $(modal).css({
          'left': 0 + 'px',
          'top': y + 'px'
        });
      } else {
        var x = (w - mw) / 2;
        var y = (h - mh) / 2;
        $(modal).css({
          'left': x + 'px',
          'top': y + 'px'
        });
      }
    }
  });


  // 公開中の場合
  $("span:contains('公開中')").addClass("now");

  // 予約中の場合
  $("span:contains('予約中')").addClass("reserve");

  // 公開終了の場合
  $("span:contains('公開終了')").addClass("end");

  $('#export').on('click', function () {
    exportToCSV();

  });

  function createValidation() {
    const startDateInput = $('.startField');
    const endDateInput = $('.endField');
  
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    let month = currentDate.getMonth() + 1;
    if (month < 10) {
      month = '0' + month; 
    }
    let day = currentDate.getDate();
    if (day < 10) {
      day = '0' + day; 
    }
    let hours = currentDate.getHours();
    if (hours < 10) {
      hours = '0' + hours; 
    }
    let minutes = currentDate.getMinutes();
    if (minutes < 10) {
      minutes = '0' + minutes; 
    }
  
    const currentDateTimeString = `${year}-${month}-${day}T${hours}:${minutes}`;
    startDateInput.attr('min', currentDateTimeString);
    endDateInput.attr('min', currentDateTimeString);
  }

  function exportToCSV() {
    var csvContent = "\uFEFF"; // BOM for UTF-16 LE
    var table = $('#data-table');

    if (table.find('tr').length === 0) {
      alert('No data available for export.');
      return;
    }

    // Add the table header (<thead>) to the CSV content
    table.find('thead th').each(function () {
      csvContent += $(this).text() + ',';
    });
    csvContent = csvContent.slice(0, -1); // Remove trailing comma
    csvContent += '\n';

    // Add all table rows (<tr>) to the CSV content
    table.find('tbody tr').each(function () {
      var rowData = [];
      $(this).find('td').not(':last-child').each(function () {
        if ($(this).find('input[type="datetime-local"]').length > 0) {
          rowData.push($(this).find('input[type="datetime-local"]').val() || 'No Date');
        } else if ($(this).find('textarea').length > 0) {
          rowData.push($(this).find('textarea').text().trim());
        } else {
          var value = $(this).text().trim();
          if (value === '編集' || value === '完了' || value === '削除') {
            return; // Skip button values
          } else if ($(this).find('input[name="writer"]').length > 0) {
            rowData.push($(this).find('input[name="writer"]').val().trim());
          }else if($(this).find('input[name="message"]').length > 0)
          {
            rowData.push('"'+$(this).find('input[name="writer"]').val().trim()+'"');
            console.log(1);
          } 
          else {
            rowData.push(value);
          }
        }
      });
      
      csvContent += rowData.join(',') + '\n';
      
      console.log(csvContent);
    });

    var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-16' });
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'exported_data.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }

});
