@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
    html, body {
        background: #f2f2f2;
        font-family: 'Poppins', sans-serif;
    }
    
    .card {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transition: 0.3s;
        border-radius: 5px;
    }
    
    .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }
    
    .card-header {
        font-weight: bold;
        background-color: #ffffff;
        border-bottom: 1px solid #b1c4ff;
    }
    
    .btn-primary {
        background-color: #4a6fb1;
        border-color: #4a6fb1;
    }
    
    .btn-primary:hover {
        background-color: #2e4a8a;
        border-color: #2e4a8a;
    }
    
    .container .form {
        margin: 12% auto 25% auto;
        background: #fff;
        padding: 30px 35px;
        border-radius: 5px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    
    .container .form form .form-control {
        height: 40px;
        font-size: 15px;
    }
    
    .container .form form .button {
    background: #482683;
    color: #fff;
    font-size: 17px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.container .form form .button:hover {
    background: #482683;
}

    
    .container .form form label {
        font-size: 15px;
        font-weight: 500;
        margin-bottom: 8px;
        display: block;
    }
    
    .container .form form .invalid-feedback {
        color: #dc3545;
        font-size: 14px;
    }
    
    .container .form form p {
        font-size: 14px;
    }
</style>

<main class="main" id="main">
    <div class="container">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="{{ route('username.email') }}" method="POST" autocomplete="off">
                    @csrf
                    <h2 class="text-center">Rappel d'identifiant</h2>
                    <div class="form-group">
                        <p class="text-center">Entrez votre adresse e-mail pour réinitialiser votre mot de passe.</p>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group my-3">
                        <button type="submit" class="form-control button">
                            Envoyer l'identifiant
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
