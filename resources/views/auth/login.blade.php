@extends('layouts.app')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@100;200;300;400;500;600;700;800;900&display=swap');

:root {
    --primary-color: #482683;
    --secondary-color: #482683;
    --tertiary-color: #0077b6;
    --gray-color: #b0b0b0;
}

* {
    box-sizing: border-box;
    font-family: 'Raleway', sans-serif;
    line-height: 1;
    padding: 0;
    margin: 0;
}
body {
    background-color: #f2f2f2;
}

 .container {
    background-color: #f2f2f2;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.box {
    background-color: #ffffff;
    border-radius: 10px;
    padding: 45px;
    width: 375px;
    max-width: 95%;
    box-shadow: 5px 5px 10px 1px rgba(0, 0, 0, 0.1);
}

@media (max-width: 480px) {
    .box {
        padding: 75px 25px;
    }
}


.box h1 {
    font-size: 35px;
    font-weight: 800;
    text-align: center;
    margin-bottom: 45px;
}

.box form label {
    display: block;
    font-size: 14px;
    margin-bottom: 3px;
    color: #000;
}

.sign-up  {
  font-size: 15px !important;
  font-weight: bold !important;
  color: #0077b6 !important;
}
.box form div {
    display: flex;
    align-items: center;
    border-bottom: 1px solid var(--gray-color);
}

.box form div:hover {
    border-bottom-color: var(--secondary-color);
}

.box form div:first-of-type {
    margin-bottom: 35px;
}

.box form div i {
    font-size: 15px;
    padding-left: 10px;
    color: var(--gray-color);
}

.box form div:hover i {
    color: var(--secondary-color);
}

.box form div input {
    font-size: 12px;
    outline: none;
    border: none;
    padding: 10px;
    min-width: 0;
    flex: 1;
}

.box form div input::placeholder {
    opacity: 1;
    color: var(--gray-color);
    font-size: 12px;
}

.box a {
    color: black;
    text-decoration: none;
    font-size: 12px;
    display: block;
}

.box a:hover {
    color: var(--secondary-color);
}

.box form .forgot {
    margin-top: 15px;
    float: right;
}

.box form input[type="submit"] {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    background-color: var(--secondary-color);
    color: white;
    border: none;
    width: 100%;
    padding: 15px;
    margin-top: 25px;
    border-radius: 250px;
}

.box form input[type="submit"]:hover {
    background-color: var(--tertiary-color);
    cursor: pointer;
}

.box .sign-up {
    text-align: center;
    text-transform: uppercase;
}
</style>
<div class="container">
  <div class="box">
    <a href="{{ route('A_blog') }}">
        <img src="{{ asset('assets/images/LogoHB.png') }}" alt="Logo" style="display: block; margin: 0 auto 45px auto;" width="200px">
    </a>
      <form class="" method="POST" action="{{ route('login') }}">
        @csrf
          <label for="username">Nom d'utilisateur</label>
          <div>
              <i class="fa-solid fa-user"></i>
              <input type="username" id="username" placeholder="Nom d'utilisateur" class="@error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus />
          </div>
          @error('username')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
          @enderror

          <label for="password">Mot de passe</label>
          <div>
              <i class="fa-solid fa-lock"></i>
              <input type="password" id="password" placeholder="Mot de passe" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password" />
          </div>
          @error('password')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
          @enderror

          <div sty style="text-align: center; margin-top: 15px;border: none;">
            <a style="margin: 0 auto;" href="{{ route('password.request') }}">Vous avez oublié votre mot de passe?</a>
          </div>

        <input type="submit" value="Login">
      </form>
      <div style="text-align:center; margin-top: 20px;">
          <a href="{{ route('username.reminder') }}">Identifiant oublié?</a><br>
          <a href="{{ route('register') }}" class="sign-up">Créer un compte ici</a>
      </div>
  </div>
</div>
@endsection