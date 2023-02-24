@extends('layouts.app')

@section('content')
<div class="container">
    <div class="sign-up-form sign-up-formrelogin">
      <!-- Left (Form Image) -->
      <div class="form-image">
        <img src="{{asset('assets\images\marinaeno1200500069.jpg')}}" alt="" />
      </div>
      <!-- Right (Form Content) -->
      <form class="form-content" method="POST" action="{{ route('login') }}">
        <!-- Form Heading -->
        @csrf
        <div class="form-heading">
          <a href="{{ route('A_blog') }}"><img src="{{asset('assets\images\logo.png')}}" alt="" /></a>
          <h1>Creation du compte</h1>
          <p>Veuillez remplir tous les champs obligatoires pour créer votre compte !</p>
        </div>
        <!-- Input Wrap -->
        <div class="input-wrap">
          
          <div class="input">
            <input type="username" id="username" placeholder=" " class=" @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus />
            @error('username')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="label">
              <label for="username">Username</label>
            </div>
          </div>


          <div class="input">
            <input type="password" id="password" placeholder=" " class=" @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" />
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="label">
              <label for="password">Mot de passe</label>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6 offset-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                      Souviens de moi
                    </label>
                </div>
            </div>
        </div>

          <button type="submit" >
            Connexion</button>
        </div>
        @if (Route::has('password.request'))
        <a style="margin-top: 20px" href="{{ route('password.request') }}">
          Vous avez oublié votre mot de passe ?
        </a>
    @endif


      </form>
    </div>
  </div>
@endsection

