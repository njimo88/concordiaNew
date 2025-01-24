@extends('layouts.template')

@section('content')

@php
$isNotAuthorized = auth()->user()->roles->id < $user->role || auth()->user()->roles->supprimer_edit_ajout_user == 0;
@endphp

<main id="main" class="main">
@if (session()->has('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="container rounded bg-white my-4 p-4">
    <form action="{{ route('admin.editSpecificUser', $user->user_id) }}" method="post" class="row" enctype="multipart/form-data">
        @csrf

        <!-- Profile Header -->
        <div class="col-md-3 border-right d-flex flex-column align-items-center text-center">
            <div></div> <!-- Div to fill in the space -->
            <div id="user-container">
                @if($user->image)
                        <img class="rounded-circle mt-5" width="150px" src="{{ asset($user->image) }}" >
                    @elseif ($user->gender == 'male')
                        <img class="rounded-circle mt-5" width="150px" src="{{ asset('assets\images\user.jpg') }}" alt="male">
                    @elseif ($user->gender == 'female')
                        <img class="rounded-circle mt-5" width="150px" src="{{ asset('assets\images\femaleuser.png') }}" alt="female">
                @endif
                <span class="text-dark mt-2">{{ $user->lastname }} {{ $user->name }} (N°{{ $user->user_id }})</span>
            </div>  
            {{-- <div>
                @php
                    $isFrozeImage = str_contains($user->image, 'uploads/users/frozen/');
                @endphp
                @if(!$isFrozeImage || (auth()->user()->role >= 90))
                    <input type="file" name="profile_image" accept="image/*" style="margin-bottom: 10px;">
                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" id="delete_image" name="delete_image" value="1">
                        <label class="form-check-label" for="delete_image">Supprimer la photo</label>
                    </div>
                    @if(auth()->user()->role >= 90)
                        <div class="form-check mt-3">
                            <input type="checkbox" class="form-check-input" id="freeze_image" name="freeze_image" value="1" @if(str_contains($user->image ?? '', 'frozen')) checked @endif>
                            <label class="form-check-label" for="freeze_image">Geler la photo</label>
                        </div>
                    @endif
                @endif            
            </div> --}}
            <div></div> <!-- Div to fill in the space -->
        </div>

        <!-- Profile Form -->
        <div class="col-md-9">
            <div class="p-3">

                <!-- Section Title -->
                <h4 class="text-center mb-4">Paramètres du Profil</h4>

                <!-- Form Fields -->
                <div class="row g-3">

                    <!-- Role -->
                    <div class="col-md-4">
                        <label for="role" class="form-label">Rôle</label>
                        <select id="role" name="role" class="form-select" {{ $isNotAuthorized ? 'disabled' : '' }}>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Name -->
                    <div class="col-md-4">
                        <label for="name" class="form-label">Nom</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" 
                                {{ $isNotAuthorized ? 'readonly' : '' }}>
                        @error('name')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Lastname -->
                    <div class="col-md-4">
                        <label for="lastname" class="form-label">Prénom</label>
                        <input type="text" id="lastname" name="lastname" class="form-control" value="{{ $user->lastname }}" 
                        {{ $isNotAuthorized ? 'readonly' : '' }}>
                        @error('lastname')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Username and Gender -->
                    <div class="col-md-4">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" class="form-control" value="{{ $user->username }}" 
                        {{ $isNotAuthorized ? 'readonly' : '' }}>
                        @error('username')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div class="col-md-4">
                        <label for="gender" class="form-label">Sexe</label>
                        <select id="gender" name="gender" class="form-select" {{ $isNotAuthorized ? 'disabled' : '' }}>
                            <option value="">--Choisir une option--</option>
                            <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Homme</option>
                            <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Femme</option>
                        </select>
                        @error('gender')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-4">
                        <label for="email" class="form-label">Adresse Mail</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" {{ $isNotAuthorized ? 'readonly' : '' }}>
                        @error('email')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Profession -->
                    <div class="col-md-4">
                        <label for="profession" class="form-label">Profession</label>
                        <input type="text" id="profession" name="profession" class="form-control" value="{{ $user->profession }}" {{ $isNotAuthorized ? 'readonly' : '' }}>
                        @error('profession')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="col-md-4">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ $user->phone }}" {{ $isNotAuthorized ? 'readonly' : '' }}>
                        @error('phone')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Medical Certificate -->
                    <div class="col-md-4">
                        <label for="crt" class="form-label">Certificat Médical</label>
                        @if($user->medicalCertificate && $user->medicalCertificate->file_path)
                            <!-- Lien pour ouvrir l'image dans un modal -->
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal">
                                <img src="{{ asset($user->medicalCertificate->file_path) }}" alt="Certificat Médical" class="rounded mx-auto d-block" style="max-height: 150px;">
                            </a>
                        @endif
                        <input type="file" id="crt" name="crt" class="form-control" accept="image/*" {{ $isNotAuthorized ? 'disabled' : '' }} capture="environment">
                        @error('crt')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>                    

                    <!-- Medical Certificate Expiration Date -->
                    <div class="col-md-4">
                        <label for="crt_expiration" class="form-label">Date expiration Certificat</label>
                        <input type="date" id="crt_expiration" name="crt_expiration"
                            class="form-control" value="{{ $user->medicalCertificate->expiration_date ?? '' }}" 
                            {{ $isNotAuthorized ? 'disabled' : '' }}>
                        @error('crt_expiration')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Champ de suppression -->
                    <div class="col-sm-4 input mt-2">
                        <div class="labels">
                            <label class="form-check-label" for="crt_delete">Supprimer certificat</label>
                        </div>
                        <div class="form-check mt-3">
                            <input type="checkbox" class="form-check-input" id="crt_delete" name="crt_delete" value="1">
                        </div>
                    </div>

                    <!-- Birthdate -->
                    <div class="col-md-4">
                        <label for="birthdate" class="form-label">Date de Naissance</label>
                        <input type="date" id="birthdate" name="birthdate" class="form-control" value="{{ $user->birthdate }}" {{ $isNotAuthorized ? 'readonly' : '' }}>
                        @error('birthdate')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Nationality -->
                    <div class="col-md-4">
                        <label for="nationality" class="form-label">Nationalité</label>
                        <select data-flag="true" id="nationality" class="selectpicker countrypicker" data-default="{{ $user->nationality }}" name="nationality" value="{{ $user->nationality }}" autocomplete="country" autofocus {{ $isNotAuthorized ? 'disabled' : '' }}>
                        </select>
                        @error('nationality')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="col-md-4">
                        <label for="address" class="form-label">Adresse</label>
                        <input type="text" id="address" name="address" class="form-control" value="{{ $user->address }}" {{ $isNotAuthorized ? 'readonly' : '' }}>
                        @error('address')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- City -->
                    <div class="col-md-4">
                        <label for="city" class="form-label">Ville</label>
                        <input type="text" id="city" name="city" class="form-control" value="{{ $user->city }}" {{ $isNotAuthorized ? 'readonly' : '' }}>
                        @error('city')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Postal Code -->
                    <div class="col-md-4">
                        <label for="zip" class="form-label">Code Postal</label>
                        <input type="text" id="zip" name="zip" class="form-control" value="{{ $user->zip }}" {{ $isNotAuthorized ? 'readonly' : '' }}>
                        @error('zip')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div class="col-md-4">
                        <label for="country" class="form-label">Pays</label>
                        <select data-flag="true" id="country" class="selectpicker countrypicker" name="country" data-default="{{ $user->country }}" value="{{ $user->country }}" required autocomplete="country" autofocus {{ $isNotAuthorized ? 'disabled' : '' }}>
                        </select>
                        @error('country')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Inscription date -->
                    <div class="col-md-4">
                        <label for="created_at">Date d'inscription</label>
                        <input type="date" id="created_at" placeholder=" " name="created_at" class="form-control" value="{{ $user->created_at->format('Y-m-d') }}" {{ $isNotAuthorized ? 'readonly' : '' }}>
                        @error('created_at')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Licence FFGYM -->
                    <div class="col-md-4">
                        <label for="licenceFFGYM">Licence FFGYM</label>
                        <input type="text" id="licenceFFGYM" placeholder=" " name="licenceFFGYM" class="form-control" value="{{ $user->licenceFFGYM }}" {{ $isNotAuthorized ? 'readonly' : '' }}>
                        @error('licenceFFGYM')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="col-md-12 text-center mt-4">
                    @if(!$isNotAuthorized)
                        {{-- <button type="submit" class="btn btn-success">Enregistrer</button> --}}
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal">Enregistrer</button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal de confirmation -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Confirmer les modifications</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir enregistrer les modifications apportées au profil de l'utilisateur ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <!-- Bouton de confirmation -->
                        <button type="submit" class="btn btn-success">Confirmer</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Certificat Médical</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Image qui sera affichée dans le modal -->
                @if($user->medicalCertificate && $user->medicalCertificate->file_path)
                <img src="{{ asset($user->medicalCertificate->file_path) }}" class="rounded mx-auto d-block zoomed-in" alt="Certificat Médical">
                @else
                <div>Aucun certificat trouvé</div>
                @endif
            </div>
        </div>
    </div>
</div>

</main>

<style>
    select {
        margin: 15px 0;
    }

    .countrypicker{
        margin: 7.5px 0;
    }

    #user-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .zoomed-in {
        width: 100%;
        height: 100%;
    }

    @media screen and (max-width: 992px) {
        .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
            width: 145px;
        }
    }
</style>

@endsection