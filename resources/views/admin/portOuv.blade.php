@extends('layouts.template')

@section('content')
<main class="main" id="main">
    @if (session('success'))
    <div class="alert alert-success col-12">
        {{ session('success') }}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div style="--bs-modal-width: 80vw !important; height: 95vh !important;" class="modal fade " id="editusermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="editusermodalContainer">
                
            </div>
        </div>
           
    </div>
    
    <div class="modal fade " id="Resetpass" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-info" role="document">
            <!--Content-->
            <div class="modal-content text-center" id="ResetpassContainer">
            </div>
            <!--/.Content-->
          </div>
    </div>
    <div class="pagetitle">
        <h1>Liste des utilisateurs</h1>
        <nav>
           <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html">Home</a></li>
              <li class="breadcrumb-item">Utilisateurs</li>
              <li class="breadcrumb-item active">Portes ouvertes</li>
           </ol>
        </nav>
     </div>
     <style>
body {
    font-family: Arial, sans-serif;
}

.display {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    color: #444;
}

.display th, 
.display td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.display th {
    background-color: #6200ee;
    color: white;
}

.display tr:nth-child(even) {
    background-color: #f2f2f2;
}

.display tr:hover {
    background-color: #ddd;
}

.display tr:first-child th:first-child {
    border-top-left-radius: 10px;
}

.display tr:first-child th:last-child {
    border-top-right-radius: 10px;
}

.display tr:last-child td:first-child {
    border-bottom-left-radius: 10px;
}

.display tr:last-child td:last-child {
    border-bottom-right-radius: 10px;
}

.Resetpass {
    cursor: pointer;
    width: 20px;
    transition: transform 0.3s ease;
}

.Resetpass:hover {
    transform: scale(1.2);
}

/* Style for the edit user link */
.user-link {
    cursor: pointer;
    color: #000;
    transition: color 0.3s ease;
}

.user-link:hover {
    color: #4CAF50;
}

/* Style for the edit user icon */
.user-link i {
    padding-right: 5px;
}
</style>
    
<table id="users-table" class="display">
    <thead>
        <tr>
            <th>Nom</th> 
            <th>Nom d'utilisateur</th> 
            <th>Email</th>
            <th>D Naissance</th> 
            <th>Téléphone</th>
            <th>Action</th>
        </tr>
    </thead>
</table>
    

    @push('scripts')
    @endpush
</main>
@endsection
