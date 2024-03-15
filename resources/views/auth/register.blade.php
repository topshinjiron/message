@extends('auth.layouts')

@section('content')

<!DOCTYPE html>
<!-- Coding By CodingNepal - codingnepalweb.com -->

<div class="wrapper">
  <h2>会員登録
</h2>
  <form action="{{ route('store') }}" method="post">
    @csrf
    
    <div class="row align-items-center">
      <label for="name" class="col-md-4">メールアドレス </label>
      <div class="col-md-8">
        <div class="input-box">
          <input type="email" placeholder="freshdesk@example.com" class="register form-control @error('email') is-invalid @enderror"
            id="email" name="email" value="" required>

          <!-- @if ($errors->has('email'))
          <span class="text-danger">{{ $errors->first('email') }}</span style="ehi:block">
          @endif -->
        </div>
      </div>
    </div>

    <div class="row align-items-center">
      <label for="name" class="col-md-4">パスワード　</label>
      <div class="col-md-8">
        <div class="input-box">
          <input type="password" placeholder="pQ#rs123" class="register form-control @error('password') is-invalid @enderror"
            id="password" name="password" required>
          <!-- @if ($errors->has('password'))
          <span class="text-danger">{{ $errors->first('password') }}</span>
          @endif -->
        </div>
      </div>
    </div>

    <div class="row align-items-center">
      <label for="name" class="col-md-4">パスワード(確認用)</label>
      <div class="col-md-8">
        <div class="input-box">
          <input type="password" placeholder="" class="register form-control @error('password') is-invalid @enderror"
            id="password_confirmation" name="password_confirmation" required>

        </div>
      </div>
    </div>

    <div class="row align-items-center">
      <label for="name" class="col-md-4">氏名</label>
      <div class="col-md-8">
        <div class="input-box">
          <input type="text" placeholder="木村太郎" class="register form-control @error('name') is-invalid @enderror"
            id="name" name="name" value="" required>
          <!-- @if ($errors->has('name'))
          <span class="text-danger">{{ $errors->first('name') }}</span>
          @endif -->
        </div>
      </div>
    </div>

    <div class="input-box button">
      <input type="Submit" value="今すぐログイン">
    </div>

    <div class="text">
      <h3>アカウントをお持ちですか？ <a href="/login">ログイン</a></h3>
    </div>
  </form>
</div>
</body>

</html>

@endsection