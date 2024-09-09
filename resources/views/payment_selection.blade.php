@extends('layouts.template')

@section('content')
<style>
body {
    background-color: #f8f9fa;
    font-family: 'Arial', sans-serif;
}

.main {
    padding: 50px 0;
}

.card {
    border-radius: 10px;
}

.card-header {
    background-color: #482683;
    color: white;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    padding: 20px;
    font-size: 1.5rem;
}

.card-body {
    padding: 30px;
}

.form-label {
    font-size: 1.2rem;
    color: #333;
}

.form-control {
    font-size: 1rem;
    padding: 10px;
    border-radius: 5px;
}

.btn-primary {
    background-color: #482683;
    border-color: #482683;
    padding: 10px 20px;
    font-size: 1.2rem;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #341a56;
}

.text-center h2 {
    margin-bottom: 20px;
}

.instructions {
    margin-bottom: 30px;
    font-size: 1rem;
    color: #555;
    text-align: center;
}
</style>

<main class="main" id="main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header text-center">
                        <h2>Choisissez votre banque</h2>
                    </div>
                    <div class="card-body">
                        <p class="instructions">
                            Sélectionnez la banque vers laquelle vous souhaitez que les paiements soient dirigés. Assurez-vous de choisir la bonne option avant de continuer.
                        </p>
                        <form action="{{ route('payment.process') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="bank" class="form-label">Banque</label>
                                <select name="bank_id" id="bank" class="form-control">
                                    @foreach($bank_accounts as $account)
                                        <option value="{{ $account->id }}" {{ $account->id == $selected_bank_id ? 'selected' : '' }}>
                                            {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Continuer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
