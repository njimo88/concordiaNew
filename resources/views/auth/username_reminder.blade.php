@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #b1c4ff;
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
</style>

<main class="main p-5" id="main " style="background-image: url('{{asset("/assets/images/background.png")}}'); min-height : 100vh;">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Rappel d'identifiant</div>
    
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
    
                        <form class="col-7" method="POST" action="{{ route('username.email') }}">
                            @csrf
    
                            <div class="form-group row">
                                <label for="email" class="mt-2 col-md-4 col-form-label text-md-right">Adresse e-mail</label>
    
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Envoyer l'identifiant
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
