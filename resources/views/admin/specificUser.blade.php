@extends('layouts.template')

@section('content')
    @php
        $isNotAuthorized =
            auth()->user()->roles->id < $user->role || auth()->user()->roles->supprimer_edit_ajout_user == 0;
    @endphp

    <main id="main" class="main">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="container rounded bg-white my-4 p-4">
            <form action="{{ route('admin.editSpecificUser', $user->user_id) }}" method="post" class="row"
                enctype="multipart/form-data">
                @csrf

                <!-- Profile Form -->
                <div class="container ">
                    <!-- Role -->
                    <div class="col-md-4">
                        <label for="role" class="form-label">Rôle</label>
                        <select id="role" name="role" class="form-select" {{ $isNotAuthorized ? 'disabled' : '' }}>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="" style="font-size: 1.25rem;">Informations personnelles</label>
                    </div>

                    <div class="user-grid">
                        <div class="photo">
                            @if ($user->image)
                                <img src="{{ asset($user->image) }}" width="150px" alt="Profile Picture">
                            @elseif ($user->gender == 'male')
                                <img src="{{ asset('assets/images/user.jpg') }}" width="150px" alt="male">
                            @elseif ($user->gender == 'female')
                                <img src="{{ asset('assets/images/femaleuser.png') }}" width="150px" alt="female">
                            @endif
                            <span class="user-id">{{ $user->user_id }}</span>
                        </div>

                        <div class="name">
                            <div class="form-group">
                                <label for="name">Nom</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text"
                                    id="name" name="name" value="{{ $user->name }}"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="lastname">
                            <div class="form-group">
                                <label for="lastname">Prénom</label>
                                <input class="form-control @error('lastname') is-invalid @enderror" type="text"
                                    id="lastname" name="lastname" value="{{ $user->lastname }}"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('lastname')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="username">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input class="form-control @error('username') is-invalid @enderror" type="text"
                                    id="username" name="username" value="{{ $user->username }}"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('username')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="birthdate">
                            <div class="form-group">
                                <label for="birthdate">Naissance</label>
                                <input class="form-control @error('birthdate') is-invalid @enderror" type="date"
                                    name="birthdate" id="birthdate" value="{{ $user->birthdate }}"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('birthdate')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="profession">
                            <div class="form-group">
                                <label for="profession">Profession</label>
                                <input class="form-control @error('profession') is-invalid @enderror" type="text"
                                    id="profession" name="profession" value="{{ $user->profession }}"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('profession')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="genre">
                            <div class="form-group">
                                <label for="gender">Genre</label>
                                <select class="form-select" id="gender" name="gender"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'disabled' : '' }}>
                                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Homme
                                    </option>
                                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>
                                        Femme
                                    </option>
                                </select>
                                @error('gender')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="nationality">
                            <div class="form-group">
                                <label for="nationality">Nationalité</label>
                                <select @if (auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id) disabled @endif data-style="btn-light"
                                    data-default="{{ $user->nationality }}" id="nationality" name="nationality"
                                    class="col-12 form-group selectpicker countrypicker @error('nationality')
                                        is-invalid @enderror"
                                    data-flag="true">
                                </select>
                                @error('nationality')
                                    <span class="text-alert" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="" style="font-size: 1.25rem;">Coordonnées</label>
                    </div>

                    <div class="row" style="--bs-gutter-x: 0.5rem;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address">Adresse</label>
                                <input class="form-control @error('address') is-invalid @enderror" type="text"
                                    id="address" name="address" value="{{ $user->address }}"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email"
                                    id="email" name="email" value="{{ $user->email }}"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone">Téléphone</label>
                                <input class="form-control @error('phone') is-invalid @enderror" type="text"
                                    id="phone" name="phone" value="{{ $user->phone }}"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row" style="--bs-gutter-x: 0.5rem;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="country">Pays</label>
                                <select @if (auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id) disabled @endif data-style="btn-light"
                                    data-flag="true" id="country"
                                    class="col-12 selectpicker countrypicker @error('country') is-invalid
                                        @enderror"
                                    name="country" data-default="{{ $user->country }}" autocomplete="country">
                                </select>
                                @error('country')
                                    <span class="text-alert" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="zip">Code Postal</label>
                                <input class="form-control @error('zip') is-invalid @enderror" type="text"
                                    id="zip" name="zip" value="{{ $user->zip }}"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('zip')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city">Ville</label>
                                <input class="form-control @error('city') is-invalid @enderror" type="text"
                                    id="city" name="city" value="{{ $user->city }}"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('city')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row" style="--bs-gutter-x: 0.5rem;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password">Changer MDP</label>
                                <input class="form-control @error('password') is-invalid @enderror" type="password"
                                    id="password" name="password"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password_confirmation">Confirmer MDP</label>
                                <input class="form-control @error('password_confirmation') is-invalid @enderror"
                                    type="password" id="password_confirmation" name="password_confirmation"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('password_confirmation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="" style="font-size: 1.25rem;">Certificat Médical</label>
                    </div>

                    <div class="row" style="--bs-gutter-x: 0.5rem;">
                        <div class="col-md-4 input mt-2">
                            <div class="form-group">
                                <label for="crt">Certificat Médical</label>
                            </div>
                            @if ($user->medicalCertificate && $user->medicalCertificate->file_path)
                                <img src="{{ asset($user->medicalCertificate->file_path) }}" alt="Certificat Médical"
                                    class="rounded mx-auto d-block" style="max-height: 150px;">
                            @endif
                            <input @if (auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id) readonly @endif type="file" id="crt"
                                name="crt"
                                class="form-control
                                @error('crt') is-invalid @enderror"
                                accept="image/*" />
                            @error('crt')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4 input mt-2">
                            <div class="form-group">
                                <label for="crt_emission">Date Emission Certificat</label>
                            </div>
                            <input @if (auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id) readonly @endif type="date" id="crt_emission"
                                name="crt_emission" class="form-control @error('crt_emission') is-invalid @enderror"
                                value="{{ $user->medicalCertificate->emission_date ?? '' }}" />
                            @error('crt_emission')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-sm-4 input mt-2">
                            <div class="form-group">
                                <label class="form-check-label" for="crt_delete">Supprimer certificat</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="crt_delete" name="crt_delete"
                                    value="1" @if (auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id) disabled @endif>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="--bs-gutter-x: 0.5rem;">
                        <!-- Inscription date -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="created_at">Date d'inscription</label>
                            </div>
                            <input type="date" id="created_at" placeholder=" " name="created_at"
                                class="form-control @error('created_at') is-invalid @enderror"
                                value="{{ $user->created_at->format('Y-m-d') }}"
                                {{ $isNotAuthorized ? 'readonly' : '' }}>
                            @error('created_at')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Licence FFGYM -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="licenceFFGYM">Licence FFGYM</label>
                            </div>
                            <input type="text" id="licenceFFGYM" placeholder=" " name="licenceFFGYM"
                                class="form-control @error('licenceFFGYM') is-invalid @enderror"
                                value="{{ $user->licenceFFGYM }}" {{ $isNotAuthorized ? 'readonly' : '' }}>
                            @error('licenceFFGYM')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="row align-items-center mb-2">
                        <div class="col-md-12 form-group">
                            <label for="" style="font-size: 1.25rem;">Certifications</label>
                        </div>
                    </div>

                    @if ($user->certifications->isEmpty())
                        <div class="row align-items-center">
                            <div class="col-md-12 mb-2 text-center">
                                Aucune certification pour cette personne.
                            </div>
                        </div>
                    @endif

                    @foreach ($user->certifications as $certification)
                        <div class="row align-items-center">
                            <!-- Affichage Certifs -->
                            <div class="col-md-6 d-flex align-items-center justify-content-start">
                                <svg width="24" height="24" viewBox="0 0 24 24"
                                    fill="{{ $certification->level->color }}" xmlns="http://www.w3.org/2000/svg">
                                    <polygon points="12,2 15,10 24,10 17,15 19,24 12,19 5,24 7,15 0,10 9,10" />
                                </svg>
                                <span class="ms-2">{{ $certification->discipline->name }}
                                    {{ $certification->level->name }} @if ($certification->points)
                                        ({{ $certification->points }}pts)
                                    @endif
                                </span>
                            </div>

                            <!-- Historique Certifs -->
                            <div class="col-md-6">
                                @if ($certification->updated_by)
                                    <p class="mb-0">Validé par {{ $certification->updater->name }}
                                        {{ $certification->updater->lastname }}
                                        le {{ $certification->updated_at->format('d/m/Y') }}
                                    </p>
                                @else
                                    <p class="mb-0">Validé par {{ $certification->creator->name }}
                                        {{ $certification->creator->lastname }}
                                        le {{ $certification->created_at->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>

                        </div>

                        <hr class="mt-2 mb-2" style="border-top: dotted 5px; background-color: transparent">
                    @endforeach

                    <!-- Form Actions -->
                    <div class="col-md-12 text-center mt-4">
                        @if (!$isNotAuthorized)
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#confirmModal">Enregistrer</button>
                        @endif
                    </div>
                </div>

                <!-- Modal de confirmation -->
                <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmModalLabel">Confirmer les modifications</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Êtes-vous sûr de vouloir enregistrer les modifications apportées au profil de
                                    l'utilisateur ?</p>
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
                        @if ($user->medicalCertificate && $user->medicalCertificate->file_path)
                            <img src="{{ asset($user->medicalCertificate->file_path) }}"
                                class="rounded mx-auto d-block zoomed-in" alt="Certificat Médical">
                        @else
                            <div>Aucun certificat trouvé</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
    <style>
        .container {
            border-radius: 10px;
            overflow: hidden;
        }

        .profile-header {
            background-color: #f4f6f8;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .profile-header img {
            border-radius: 50%;
            margin-bottom: 15px;
            width: 120px;
        }

        .profile-header h4 {
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .profile-header p {
            color: #777;
            margin-bottom: 5px;
        }

        .profile-header span {
            font-size: 1rem;
            color: #333;
            margin-bottom: 5px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: bold;
            font-size: 0.9rem;
        }

        .form-control {
            border-radius: 5px;
            box-shadow: none;
        }

        .form-select {
            border-radius: 5px;
        }

        .form-control,
        .form-select {
            width: 100%;
        }

        .btn-save {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            padding: 8px 16px;
            font-size: 1rem;
        }

        .btn-save:hover {
            background-color: #0056b3;
        }

        select,
        .dropdown-toggle {
            margin: 8px 0;
        }

        .container {
            padding: 20px;
        }

        .row {
            gap: 0px;
            margin-bottom: 0px;
        }

        .form-group {
            margin-bottom: 0px;
        }

        .form-check {
            display: flex;
            justify-content: center;
            align-items: center;
            padding-left: 0px;
            margin-bottom: 0px;
            height: 55px;
        }

        input {
            margin: 4px 0;
        }

        .user-grid {
            display: grid;
            grid-template-areas:
                "photo name name lastname username"
                "photo profession birthdate genre nationality";
            grid-template-columns: auto 1fr 1fr 1fr 1fr;
            gap: 10px;
            align-items: center;
        }


        .user-grid .photo {
            grid-area: photo;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            margin-right: 10px;
            width: 120px;
            justify-self: center;
            align-self: center;
        }

        .user-grid .photo::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            clip-path: polygon(0% 100%, 100% 100%, 100% 80%, 0% 80%);
            z-index: 10;
        }

        .user-grid .photo img {
            width: 120px;
            height: 120px;
            object-fit: cover;
        }

        .user-grid .photo .user-id {
            position: absolute;
            bottom: 1px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            padding: 3px 8px;
            font-size: 12px;
            border-radius: 5px;
            z-index: 11;
        }

        .user-grid .name {
            grid-area: name;
        }

        .user-grid .lastname {
            grid-area: lastname;
        }

        .user-grid .username {
            grid-area: username;
        }

        .user-grid .profession {
            grid-area: profession;
        }

        .user-grid .birthdate {
            grid-area: birthdate;
        }

        .user-grid .genre {
            grid-area: genre;
        }

        .user-grid .nationality {
            grid-area: nationality;
        }


        @media (max-width: 768px) {
            .user-grid {
                grid-template-columns: 1fr;
                grid-template-rows: auto;
                grid-template-areas:
                    "photo"
                    "name"
                    "lastname"
                    "username"
                    "profession"
                    "birthdate"
                    "genre"
                    "nationality";
            }

            .user-grid .photo {
                justify-content: center;
                margin-bottom: 10px;
            }
        }
    </style>
@endsection
