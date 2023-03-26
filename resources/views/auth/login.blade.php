@extends('layouts.app')

@section('content')
<main class="main" id="main" style="background-image: url('{{asset("/assets/images/background.png")}}'); height:100vh; " >
<div class=" container d-flex justify-content-center" >
    <div class="row mt-5 col-12 col-md-10 col-lg-8 sign-up-form sign-up-formrelogin d-flex justify-content-center">
      <!-- Left (Form Image) -->
      <div class="form-image p-0 col-lg-5">
        <img src="{{asset('assets\images\marinaeno1200500069.jpg')}}" alt="" />
      </div>
      <!-- Right (Form Content) -->
      <form class="form-content col-12 col-lg-7 p-3  justify-content-center" method="POST" action="{{ route('login') }}">
        <!-- Form Heading -->
        @csrf
        <div class="form-heading ">
          <a href="{{ route('A_blog') }}"><img src="{{asset('assets\images\logo.png')}}" alt="" /></a>
          <h1>Connexion</h1>
          <p>&nbsp;</p>
        </div>
        <!-- Input Wrap -->
        <div class="input-wrap  px-5 py-2">
          
          <div class="input">
            <input type="username" id="username" placeholder=" " class=" @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus />
            @error('username')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="label">
              <label for="username">Nom d'utilisateur</label>
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
            <div class="col-lg-6 ">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                      Se souvenir de moi
                    </label>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end">
          <button type="submit" >
            Connexion</button>
        </div>

          
        </div>
        @if (Route::has('password.request'))
        <div class="d-flex justify-content-center my-2">
          <a  href="{{ route('password.request') }}">
            Vous avez oublié votre mot de passe ?
          </a> <br>
          
        </div>
        
        
    @endif


      </form>
    </div>
  </div>
  </main>
  
@endsection

