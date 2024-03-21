@include('header')
</head>

<body>
  <div id="load">
    <div class="loader-wrap">
      <div class="loader"></div>
    </div>
  </div>
  <main>
    <div id="result">
      <h2>メッセージ履歴一覧</h2>
      <div id="data">
        <div id="setting">
          <div class="sort-box">
            <input type="text" class="sort" placeholder="テキスト検索..">
          </div>
          <div class="btn-box">
            <p class="new"><a data-target="new" class="modal-open">メッセージ新規作成</a></p>
            <button type="button" id="export">
              <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                style="fill: rgba(15, 51, 75, 1);transform: ;msFilter:;">
                <path d="M11 16h2V7h3l-4-5-4 5h3z"></path>
                <path
                  d="M5 22h14c1.103 0 2-.897 2-2v-9c0-1.103-.897-2-2-2h-4v2h4v9H5v-9h4V9H5c-1.103 0-2 .897-2 2v9c0 1.103.897 2 2 2z">
                </path>
              </svg>
            </button>
            <div id="logout">
              <form action="{{route('logout')}}" method="GET">
                @csrf
                <button class="logout" type="submit">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                    <path d="m2 12 5 4v-3h9v-2H7V8z"></path>
                    <path
                      d="M13.001 2.999a8.938 8.938 0 0 0-6.364 2.637L8.051 7.05c1.322-1.322 3.08-2.051 4.95-2.051s3.628.729 4.95 2.051 2.051 3.08 2.051 4.95-.729 3.628-2.051 4.95-3.08 2.051-4.95 2.051-3.628-.729-4.95-2.051l-1.414 1.414c1.699 1.7 3.959 2.637 6.364 2.637s4.665-.937 6.364-2.637c1.7-1.699 2.637-3.959 2.637-6.364s-.937-4.665-2.637-6.364a8.938 8.938 0 0 0-6.364-2.637z">
                    </path>
                  </svg>
                </button>
              </form>
            </div>
          </div>
        </div>
        <p class="hit"></p>
        <table id="data-table">
          <thead>
            <tr>
              <th>ステータス</th>
              <th>登録日時</th>
              <th>最終更新日時</th>
              <th>表示開始日時</th>
              <th>表示終了日時</th>
              <th>メッセージ</th>
              <th>実行者</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <p class="nothing">表示する内容がございません</p>
        <div id="pager">
          <a href="" class="prev">&lt;</a><span>1</span><a href="">2</a><a href="">3</a><a href="">4</a><a
            href="">5</a><a href="" class="next">&gt;</a>
        </div>
      </div>
  </main>

  <!--MODAL -->
  <div id="new" class="modal-content">
    <form action="{{ route('save') }}" method="post">
      @csrf
      <div class="textarea">
        <dl>
          <dt>メッセージ</dt>
          <dd>
            <textarea placeholder="メッセージ入力" name="message" class="messageField" oninput="checkInputValues()"></textarea>
          </dd>
        </dl>
        <dl>
          <dt>実行者</dt>
          <dd>
            <input type="text" name="writer" class="writerField" oninput="checkInputValues()">
          </dd>
        </dl>
        <ul>
          <li>
            <dl>
              <dt>表示開始</dt>
              <dd>
                <input type="datetime-local" value="" name="startDate" class="startField" oninput="checkInputValues()">
              </dd>
            </dl>
          </li>
          <li>
            <dl>
              <dt>表示終了</dt>
              <dd>
                <input type="datetime-local" value="" name="endDate" class="endField" oninput="checkInputValues()">
              </dd>
            </dl>
          </li>
        </ul>
        <div class="submit">
          <button type="button" class="save" onclick="Save(this)">登録</button>
        </div>
      </div>
      <p class="closemodal">
        <a class="modal-close">×
        </a>
      </p>
    </form>
  </div>

  <script>
  var messages = @json($all_messages);
  var dataForId = @json($ID_Maker);
  var changeFlag = 1;

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

  function checkInputValues() {
    const message = document.querySelector('.messageField').value.trim();
    const writer = document.querySelector('.writerField').value.trim();
    const startDate = document.querySelector('.startField').value.trim();
    const endDate = document.querySelector('.endField').value.trim();

    const submitButton = $('.save');

    createValidation();
    if (message !== '' && writer !== '' && startDate !== '' && endDate !== '') {
      submitButton.disabled = false;
    } else {
      submitButton.disabled = true;
    }
  }

  function showTable(visibleRowCount=0) {
    var table = $('#data-table tbody');
    for (var i = 0; i < messages.length; i++) {
      var row = $('<tr>');

      const current = new Date();
      const start = new Date(messages[i]['started_at']);
      const end = new Date(messages[i]['finished_at']);

      if (current < start && start < end) {
        row.append(
          '<td class="status"><span style="border: 1 px solid #ec941c; background: #ec941c; color: #FFF;">予約中</span></td>'
        )
      } else if ((start <= current) && (current <= end)) {
        row.append(
          '<td class="status"><span style="border:1px solid blue; background:blue; color: #FFF;">公開中</span></td>');
      } else if (end < current) {
        row.append(
          '<td class="status"><span style="border:1px solid gray; background:gray; color: #FFF;">公開終了</span></td>');
      } else {
        row.append('?????');
      }

      row.append('<td><input type="datetime-local" value="' + messages[i]['created_at'] +
        '" name="create" readonly required></td>');

      row.append('<td><input type="datetime-local" value="' + messages[i]['updated_at'] +
        '" name="update" readonly required></td>');

      row.append('<td><input type="datetime-local" value="' + messages[i]['started_at'] +
        '" name="start" readonly required></td>');

      row.append('<td><input type="datetime-local" value="' + messages[i]['finished_at'] +
        '" name="finish" readonly required></td>');

      row.append(
        '<td class="limit"><textarea style="height: 3em; overflow: hidden; resize:none" name="message" readonly>' +
        messages[i]['text'] + '</textarea></td>');

      row.append('<td><input type="text" value="' + messages[i]['updated_by'] + '" name="writer" readonly></td>');

      row.append('<input type="hidden" name="id" value="' + messages[i]['id'] + '">');

      row.append(
        '<td><div class="btn-area"><button type="button" class="edit">編集</button><button type="button" class="complete">完了</button><button type="button" class="delete">削除</button></div></td>'
      );


      row.append('</tr>')

      table.append(row);
    }

    $('.edit').on('click', function() {
      var $parentRow = $(this).closest('tr');
      $parentRow.find('textarea').removeAttr('readonly');
      $parentRow.find('input[type=datetime-local]').removeAttr('readonly');
      $parentRow.find('input[type=text').removeAttr('readonly');
      $parentRow.find('.edit').hide();
      $parentRow.find('.complete').show();

      const $textareaEls = $("textarea");
      $textareaEls.each(function() {
        $(this).css("height", this.scrollHeight + "px");
        $(this).on("input", setTextareaHeight);
      });

      function setTextareaHeight() {
        $(this).css("height", "auto");
        $(this).css("height", this.scrollHeight + "px");
      }

    });

    $('.complete').on('click', function() {
      var $parentRow = $(this).closest('tr');
      $parentRow.find('textarea').attr('readonly', 'readonly');
      $parentRow.find('input[type=datetime-local]').attr('readonly', 'readonly');
      $parentRow.find('input[type=text]').attr('readonly', 'readonly');
      $parentRow.find('.edit').show();
      $parentRow.find('.complete').hide();

      var dataToSend = {};

      $(this).closest('tr').find('input, textarea').each(function() {
        var name = $(this).attr('name');
        var value = $(this).val();
        dataToSend[name] = value;
      });

      console.log(dataToSend, "dataToSend");

      const date = new Date();
      const options = {
        timeZone: 'Asia/Tokyo',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
      };

      var editData = {
        created_at: dataToSend['create'],
        deleted_at: null,
        finished_at: dataToSend['finish'],
        id: parseInt(dataToSend['id']),
        started_at: dataToSend['start'],
        text: dataToSend['message'],
        updated_at: date.toLocaleString('ja-JP', options).toString().replace(/\//, '-').replace('/', '-'),
        updated_by: dataToSend['writer'],
      };

      console.log(editData, "editData");
      $.ajax({
        url: '/edit',
        type: 'POST',
        contentType: 'application/json',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: JSON.stringify(dataToSend),
        success: function() {
          console.log('Server response success');
          const index = messages.findIndex(item => item.id === editData.id);
          if (index !== -1) {
            messages[index] = editData;
            dataForId[index] = editData;
          }
          messages.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
          dataForId.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
          console.log(messages);
          $('#data-table tbody').empty();
          showTable();
          updatePagination();
          // window.location.reload(true);
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);
        }
      });
    });

    $('.delete').on('click', function() {
      var $parentRow = $(this).closest('tr');
      if (confirm('本当に削除しますか？')) {
        $parentRow.remove();
        if ($('table tbody tr').length === 0) {
          $('.nothing').show();
        }

        var dataToSend = {};

        $(this).closest('tr').find('input, textarea').each(function() {
          var name = $(this).attr('name');
          var value = $(this).val();
          dataToSend[name] = value;
        });

        console.log(dataToSend, "delete");

        $.ajax({
          url: '/delete',
          type: 'POST',
          contentType: 'application/json',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: JSON.stringify(dataToSend),
          success: function(response) {
            console.log('Server response Success');
            console.log(dataToSend.id, "dataToSend-Id");
            let index = messages.findIndex(item => item.id == dataToSend.id);
            if (index !== -1) {
              messages.splice(index, 1);
            }
            console.log(index, "deleteID");
            messages.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
            console.log(messages, "messages-Delete");
            $('#data-table tbody').empty();
            showTable();
            // window.location.reload(true);
          },
          error: function(xhr, status, error) {
            console.error('Error:', error);
          }
        });
      }
    });

    $('.sort').on('input', function() {
      var searchText = $(this).val().toLowerCase();

      if (searchText === '') {
        currentPage = 1;
        changeFlag = 1;
        //updatePagination();
        displayPage(currentPage);
        $('.nothing').hide();
        $('.hit').text('');
        console.log('Search text is empty');
        //$('#data-table tbody tr').removeClass('hide');
        return;
      }

      var total = $('#data-table tbody tr').length;

      $('#data-table tbody tr').each(function() {
        var rowText = $(this).find('textarea').text().toLowerCase();
        console.log(rowText);
        // Check text content in td and input values
        if (rowText.includes(searchText)) {
          $(this).removeClass('hide');
        } else {
          $(this).addClass('hide');
        }
      });

      // Show or hide the "nothing" message based on the search result
      var visibleRowCount = $('#data-table tbody tr:visible').length;

      if (visibleRowCount > 0) {
        $('.nothing').hide();
        $('.hit').text(visibleRowCount + "件ヒットしました");
        changeFlag = 0;
        //showTable(visibleRowCount);
      } else {
        $('.nothing').show();
        $('.hit').text('');
      }
    });

    if (changeFlag == 1) {
      const itemsPerPage = 50;
      const totalItems = messages.length;
      let currentPage = 1;

      function displayPage(page) {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const messagesShow = document.querySelectorAll('#data-table tbody tr');

        messagesShow.forEach((message, index) => {
          if (index >= start && index < end) {
            $(message).removeClass('hide');
          } else {
            $(message).addClass('hide');
          }
        });
      }

      function updatePagination() {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const pager = document.getElementById('pager');
        pager.innerHTML = '';

        const prevButton = document.createElement('a');
        prevButton.textContent = '<';
        prevButton.href = '#';
        prevButton.addEventListener('click', () => {
          currentPage = Math.max(currentPage - 1, 1);
          displayPage(currentPage);
        });
        pager.appendChild(prevButton);

        for (let i = 1; i <= totalPages; i++) {
          const pageLink = document.createElement('a');
          pageLink.textContent = i;
          pageLink.href = '#';
          pageLink.addEventListener('click', () => {
            currentPage = i;
            displayPage(currentPage);
          });
          pager.appendChild(pageLink);
        }

        const nextButton = document.createElement('a');
        nextButton.textContent = '>';
        nextButton.href = '#';
        nextButton.addEventListener('click', () => {
          currentPage = Math.min(currentPage + 1, totalPages);
          displayPage(currentPage);
        });
        pager.appendChild(nextButton);

        displayPage(currentPage);
      }

      updatePagination();
    } else {
      const itemsPerPage = 50;
      const totalItems = visibleRowCount;
      let currentPage = 1;

      function displayPage(page) {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const messagesShow_ = document.querySelectorAll('#data-table tbody tr');
        const messagesShow = Array.from(messagesShow_).filter(row => {
          return window.getComputedStyle(row).display !== 'none';
        });

        messagesShow.forEach((message, index) => {
          if (index >= start && index < end) {
            $(message).removeClass('hide');
          } else {
            $(message).addClass('hide');
          }
        });
      }

      function updatePagination() {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const pager = document.getElementById('pager');
        pager.innerHTML = '';

        const prevButton = document.createElement('a');
        prevButton.textContent = '<';
        prevButton.href = '#';
        prevButton.addEventListener('click', () => {
          currentPage = Math.max(currentPage - 1, 1);
          displayPage(currentPage);
        });
        pager.appendChild(prevButton);

        for (let i = 1; i <= totalPages; i++) {
          const pageLink = document.createElement('a');
          pageLink.textContent = i;
          pageLink.href = '#';
          pageLink.addEventListener('click', () => {
            currentPage = i;
            displayPage(currentPage);
          });
          pager.appendChild(pageLink);
        }

        const nextButton = document.createElement('a');
        nextButton.textContent = '>';
        nextButton.href = '#';
        nextButton.addEventListener('click', () => {
          currentPage = Math.min(currentPage + 1, totalPages);
          displayPage(currentPage);
        });
        pager.appendChild(nextButton);

        displayPage(currentPage);
      }
      updatePagination();
    }
  }

  showTable();

  function Save(button) {

    var form = $(button).closest('form');
    var isValid = true;
    createValidation();
    checkInputValues();

    form.find('input[type="text"], textarea, input[type="datetime-local"]').each(function() {
      if ($(this).val() === '') {
        isValid = false;
        return false; // Exit the loop early if any field is empty
      }
    });

    if (isValid) {
      const date = new Date();
      const options = {
        timeZone: 'Asia/Tokyo',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
      };
      var formData = {}
      console.log(dataForId, "dataForId-save")
      if (messages.length == 0) {
        formData = {
          created_at: date.toLocaleString('ja-JP', options).toString().replace('T', ' '),
          deleted_at: null,
          finished_at: $('.endField').val().replace('T', ' '),
          id: 1,
          started_at: $('.startField').val().replace('T', ' '),
          text: $('.messageField').val(),
          updated_at: date.toLocaleString('ja-JP', options).toString().replace('T', ' '),
          updated_by: $('.writerField').val(),
        };
      } else {
        formData = {
          created_at: date.toLocaleString('ja-JP', options).toString().replace('T', ' '),
          deleted_at: null,
          finished_at: $('.endField').val().replace('T', ' '),
          // id: Math.max(...$('#data-table tbody input[name="id"]').map(function() {
          //   return parseInt($(this).val()) || 0;
          // })) + 1,
          id: dataForId.reduce((max, obj) => Math.max(max, obj.id), -Infinity) + 1,
          // id: Math.max(...$('#data-table tbody input[name="id"]').map(function() {
          //   return parseInt($(this).val()) || 0;
          // })) + 1,
          started_at: $('.startField').val().replace('T', ' '),
          text: $('.messageField').val(),
          updated_at: date.toLocaleString('ja-JP', options).toString().replace('T', ' '),
          updated_by: $('.writerField').val(),
        };
      }
      console.log(formData, "FormData-save");
      console.log($('input[name="id"]').val(), "ID-VALUES")
      console.log(dataForId.reduce((max, obj) => Math.max(max, obj.id), -Infinity), "IDVALUES-max");

      $.ajax({
        type: "POST",
        url: "{{ route('save') }}",
        data: formData,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function() {
          formData.created_at = formData.created_at.replace(/\//, '-').replace('/', '-');
          formData.updated_at = formData.updated_at.replace(/\//, '-').replace('/', '-');
          messages.push(formData);
          dataForId.push(formData);
          messages.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
          dataForId.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
          $('#data-table tbody').empty();
          showTable();

        },
        error: function(jqXHR, textStatus, errorThrown) {
          // Handle any errors that occur during the AJAX request
          console.error(textStatus, errorThrown);
        }
      });
      $('.modal').hide();
    } else {
      alert('すべての項目を入力してください。');
    }
  }
  </script>

  @include('footer')