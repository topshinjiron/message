@include('header')

</head>

<body>
  <form action="{{ route('authenticate') }}" method="post">
    @csrf
    <div id="login">
      <h2><img src="https://www.orangeone.jp/wp/wp-content/uploads/2019/08/logo.png"
          class="custom-logo ls-is-cached lazyloaded" alt="OrangeOne株式会社"
          data-src="https://www.orangeone.jp/wp/wp-content/uploads/2019/08/logo.png"
          data-srcset="https://www.orangeone.jp/wp/wp-content/uploads/2019/08/logo.png 1x, https://www.orangeone.jp/wp/wp-content/uploads/2019/08/logo.png 2x"
          loading="lazy"
          srcset="https://www.orangeone.jp/wp/wp-content/uploads/2019/08/logo.png 1x, https://www.orangeone.jp/wp/wp-content/uploads/2019/08/logo.png 2x">
      </h2>
      <table>
        <tr>
          <td><input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
              placeholder="メールアドレス" required></td>
          @if ($errors->has('email'))
          <span class="text-danger">{{ $errors->first('email') }}</span>
          @endif
        </tr>
        <tr>
          <td><input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
              placeholder="パスワード" required></td>
          @if ($errors->has('password'))
          <span class="text-danger">{{ $errors->first('password') }}</span>
          @endif
        </tr>
      </table>
      <div class="submit">
        <button type="submit">ログイン</button>
      </div>
    </div>
  </form>
  </body>
</html>