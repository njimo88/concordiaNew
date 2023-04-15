@extends('layouts.app')

@section('content')
<main class="main" id="main " style="background-image: url('{{asset("/assets/images/background.png")}}'); min-height : 100vh;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div style="margin-top: 5%" class="card">
                <div style="display: none"  class="card-header">{{ __('Réinitialiser le mot de passe') }}</div>
                <div class="text-center mt-4 text-dark font-weight-bold">
                    <h1 class="h2 font-weight-bold">{{ __('Réinitialiser le mot de passe') }}</h1>
                    <p class="lead">
                      Entrez votre adresse e-mail pour réinitialiser votre mot de passe.
                    </p>
                  </div>

              <div class="text-center">
                <img style="width: 50% " src="{{asset('assets\images\password.png')}}" alt="" />
              </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="col-8" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 mt-2 col-form-label text-md-end">{{ __('Adresse e-mail') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class=" @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('réinitialiser') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</main>
@endsection