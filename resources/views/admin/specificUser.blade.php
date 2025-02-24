@extends('layouts.template')

@section('content')
    @php
        $isNotAuthorized =
            auth()->user()->roles->id < $user->role || auth()->user()->roles->supprimer_edit_ajout_user == 0;
        $widthOfDiv = 6;
    @endphp

    <main id="main" class="main container py-4">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.editSpecificUser', $user->user_id) }}" method="post" class="row"
            enctype="multipart/form-data">
            @csrf
            <h2>Fiche de membre</h2>
            <!-- Role -->
            <div class="row">
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
            </div>

            <div class="row g-4">
                <!-- Identité -->
                <div class="col-md-5">
                    <div class="card text-white identity-card mb-3 rounded-3">
                        <div class="card-header fw-bold">Identité</div>
                        <div class="card-body">
                            <div class="photo-container d-flex mb-3 gap-2">
                                <div class="photo">
                                    <div>
                                        @if ($user->image)
                                            <img src="{{ asset($user->image) }}" width="150px" alt="Profile Picture">
                                        @elseif ($user->gender == 'male')
                                            <img src="{{ asset('assets/images/user.jpg') }}" width="150px" alt="male">
                                        @elseif ($user->gender == 'female')
                                            <img src="{{ asset('assets/images/femaleuser.png') }}" width="150px"
                                                alt="female">
                                        @endif
                                        <span class="user-id">{{ $user->user_id }}</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 d-flex flex-column gap-2">
                                    <div class="form-group w-100 d-flex flex-column align-items-start align-self-start">
                                        <label for="name" class="w-100">Nom</label>
                                        <input class="form-control w-85 @error('name') is-invalid @enderror" type="text"
                                            id="name" name="name" value="{{ $user->name }}"
                                            {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group w-100 d-flex flex-column align-items-start align-self-start">
                                        <label for="lastname" class="w-100">Prénom</label>
                                        <input class="form-control w-85 @error('lastname') is-invalid @enderror"
                                            type="text" id="lastname" name="lastname" value="{{ $user->lastname }}"
                                            {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                        @error('lastname')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group d-flex align-items-center mb-2 gap-2">
                                <label for="username" class="me-2 w-auto">Username</label>
                                <input class="form-control @error('username') is-invalid @enderror" type="text"
                                    id="username" name="username" value="{{ $user->username }}"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('username')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group d-flex align-items-center mb-2 gap-2">
                                <div class="w-50 pe-2">
                                    <label for="birthdate" class="w-100">Naissance</label>
                                    <input class="form-control w-100 @error('birthdate') is-invalid @enderror"
                                        type="date" name="birthdate" id="birthdate" value="{{ $user->birthdate }}"
                                        {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                    @error('birthdate')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="w-50 ps-2">
                                    <label for="profession" class="w-100">Profession</label>
                                    <input class="form-control w-100 @error('profession') is-invalid @enderror"
                                        type="text" id="profession" name="profession" value="{{ $user->profession }}"
                                        {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                    @error('profession')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group d-flex align-items-center mb-2 gap-2">
                                <div class="w-50 pe-2">
                                    <label for="gender" class="w-100">Genre</label>
                                    <select class="form-select w-100" id="gender" name="gender"
                                        {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'disabled' : '' }}>
                                        <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Homme
                                        </option>
                                        <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Femme
                                        </option>
                                    </select>
                                    @error('gender')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="w-50 ps-2">
                                    <label for="nationality" class="w-100">Nationalité</label>
                                    <select data-style="btn-light" data-default="{{ $user->nationality }}" id="nationality"
                                        name="nationality"
                                        class="w-100 selectpicker countrypicker @error('nationality') is-invalid @enderror"
                                        data-flag="true"
                                        {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'disabled' : '' }}>
                                    </select>
                                    @error('nationality')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coordonnées -->
                <div class="col-md-6">
                    <div class="card text-white coordinates-card mb-3 rounded-3">
                        <div class="card-header fw-bold">Coordonnées</div>
                        <div class="card-body">
                            <div class="form-group d-flex align-items-center mb-2 gap-2">
                                <label class="me-2 w-25" for="address">Adresse</label>
                                <input class="form-control w-75 @error('address') is-invalid @enderror" type="text"
                                    id="address" name="address" value="{{ $user->address }}"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('address')
                                    <div class="text-black">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group d-flex align-items-center mb-2 gap-2">
                                <label class="me-2 w-25" for="email">Email</label>
                                <input class="form-control w-75 @error('email') is-invalid @enderror" type="email"
                                    id="email" name="email" value="{{ $user->email }}"
                                    {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                @error('email')
                                    <div class="text-black">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group d-flex align-items-center mb-2 gap-2">
                                <div class="w-50 pe-2">
                                    <label class="me-2 w-100" for="phone">Téléphone</label>
                                    <input class="form-control w-100 @error('phone') is-invalid @enderror" type="text"
                                        id="phone" name="phone" value="{{ $user->phone }}"
                                        {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                    @error('phone')
                                        <div class="text-black">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="w-50 pe-2">
                                    <label class="me-2 w-100" for="country">Pays</label>
                                    <select @if (auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id) disabled @endif data-style="btn-light"
                                        data-flag="true" id="country"
                                        class="w-100 selectpicker countrypicker @error('country') is-invalid
                                            @enderror"
                                        name="country" data-default="{{ $user->country }}" autocomplete="country">
                                    </select>
                                    @error('country')
                                        <span class="text-black">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group d-flex align-items-center mb-2 gap-2">
                                <div class="w-50 pe-2">
                                    <label class="me-2 w-100" for="zip">Code Postal</label>
                                    <input class="form-control w-100 @error('zip') is-invalid @enderror" type="text"
                                        id="zip" name="zip" value="{{ $user->zip }}"
                                        {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                    @error('zip')
                                        <div class="text-black">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="w-50 pe-2">
                                    <label class="me-2 w-100" for="city">Ville</label>
                                    <input class="form-control w-100 @error('city') is-invalid @enderror" type="text"
                                        id="city" name="city" value="{{ $user->city }}"
                                        {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                    @error('city')
                                        <div class="text-black">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group d-flex align-items-center mb-2 gap-2">
                                <div class="w-50 pe-2">
                                    <label class="me-2 w-100" for="phone">Date d'inscription</label>
                                    <input type="date" id="created_at" placeholder=" " name="created_at"
                                        class="form-control w-100 @error('created_at') is-invalid @enderror"
                                        value="{{ $user->created_at->format('Y-m-d') }}"
                                        {{ $isNotAuthorized ? 'readonly' : '' }}>
                                    @error('created_at')
                                        <span class="text-black">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="w-50 pe-2">
                                    <label class="me-2 w-100" for="phone">N° de licence</label>
                                    <input type="text" id="licenceFFGYM" placeholder=" " name="licenceFFGYM"
                                        class="form-control w-100 @error('licenceFFGYM') is-invalid @enderror"
                                        value="{{ $user->licenceFFGYM }}" {{ $isNotAuthorized ? 'readonly' : '' }}>
                                    @error('licenceFFGYM')
                                        <span class="text-black">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group d-flex align-items-center mb-2 gap-2">
                                <div class="w-50 pe-2">
                                    <label class="me-2 w-100" for="password">Changer MDP</label>
                                    <input class="form-control w-100 @error('password') is-invalid @enderror"
                                        type="password" id="password" name="password"
                                        {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="w-50 pe-2">
                                    <label class="me-2 w-100" for="password_confirmation">Confirmer MDP</label>
                                    <input class="form-control w-100 @error('password_confirmation') is-invalid @enderror"
                                        type="password" id="password_confirmation" name="password_confirmation"
                                        {{ auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id ? 'readonly' : '' }} />
                                    @error('password_confirmation')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @if ($user->certifications->count() >= 1)
                    <!-- Diplômes -->
                    <div class="col-md-5">
                        <div class="card text-black diplomas-card mb-3 rounded-3">
                            <div class="card-header fw-bold">Diplômes</div>
                            <div class="card-body">
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
                                                fill="{{ $certification->level->color }}"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <polygon points="12,2 15,10 24,10 17,15 19,24 12,19 5,24 7,15 0,10 9,10" />
                                            </svg>
                                            <span class="ms-2">
                                                {{ $certification->discipline->name }} {{ $certification->level->name }}
                                                @if ($certification->points)
                                                    ({{ $certification->points }}pts)
                                                @endif
                                            </span>
                                        </div>

                                        <!-- Historique Certifs -->
                                        <div class="col-md-6">
                                            @if ($certification->updated_by)
                                                <p class="mb-0">Validé par {{ $certification->updater->lastname }}
                                                    {{ $certification->updater->name }}
                                                    le
                                                    {{ Carbon\Carbon::parse($certification->exam_date)->format('d/m/Y') }}
                                                </p>
                                            @else
                                                <p class="mb-0">Validé par {{ $certification->creator->lastname }}
                                                    {{ $certification->creator->name }}
                                                    le
                                                    {{ Carbon\Carbon::parse($certification->exam_date)->format('d/m/Y') }}
                                                </p>
                                            @endif
                                        </div>

                                    </div>

                                    <!-- Affichage de la ligne HR si ce n'est pas la dernière certification -->
                                    @if (!$loop->last)
                                        <hr class="mt-2 mb-2"
                                            style="border-top: dotted 5px; background-color: transparent">
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    @php
                        $noDiplomas = true;
                        $widthOfDiv = 5;
                    @endphp
                @endif

                <!-- Famille -->
                <div class="col-md-{{ $widthOfDiv }}">
                    <div class="card text-white parents-card mb-3 rounded-3">
                        <div class="card-header fw-bold">Parents de la famille</div>
                        <div class="card-body">
                            <div class="list-group w-100">
                                @foreach ($user->familyParents as $parent)
                                    <div class="list-group-item bg-dark text-white mb-2 rounded-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1">
                                                    <strong>
                                                        {{ $parent->lastname }} {{ $parent->name }}
                                                    </strong>
                                                </p>
                                                <p class="mb-1">
                                                    <strong>Téléphone :</strong>
                                                    <a href="tel:{{ $parent->phone }}"
                                                        class="text-decoration-none text-white">
                                                        {{ $parent->phone }}
                                                    </a>
                                                </p>
                                                <p class="mb-1"><strong>Email :</strong> {{ $parent->email }}
                                                </p>
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.showSpecificUser', $parent->user_id) }}"
                                                    class="text-white" target="_blank">
                                                    <i class="fas fa-id-card fa-lg"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                @if (!empty($noDiplomas) && $noDiplomas)
                    <!-- Historique des inscriptions -->
                    <div class="col-md-6">
                        <div class="card text-white inscriptions-card mb-3 rounded-3">
                            <div class="card-header fw-bold">Adhésions</div>
                            <div class="card-body">
                                <div>
                                    <div class="form-group d-flex align-items-center mb-2 gap-2">
                                        <label for="crt" class="me-2 w-25">Certif.Médic</label>
                                        @if ($user->medicalCertificate && $user->medicalCertificate->file_path)
                                            <i class="fas fa-file-medical" data-toggle="modal"
                                                data-target="#imageModal{{ $user->user_id }}" data-dismiss="modal"
                                                style="cursor: pointer" title="Certificat médical"></i>
                                        @endif
                                        <input @if (auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id) readonly @endif type="file"
                                            id="crt" name="crt"
                                            class="form-control w-75
                        @error('crt') is-invalid @enderror"
                                            accept="image/*" />
                                        @error('crt')
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>


                                    <div class="form-group d-flex align-items-center mb-2 gap-2">
                                        <div class="w-50 pe-2">
                                            <label for="crt_emission" class="me-2 w-100">Date</label>
                                            <input @if (auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id) readonly @endif type="date"
                                                id="crt_emission" name="crt_emission"
                                                class="form-control w-100 @error('crt_emission') is-invalid @enderror"
                                                value="{{ $user->medicalCertificate->emission_date ?? '' }}" />
                                            @error('crt_emission')
                                                <span class="text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="w-50 pe-2">
                                            <label class="me-2 w-100" for="crt_delete">Supprimer</label>
                                            <input type="checkbox" class="form-check-input p-0 m-0"
                                                style="width:25px; height:25px;" id="crt_delete" name="crt_delete"
                                                value="1" @if (auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id) disabled @endif>
                                        </div>
                                    </div>

                                    @if ($user->adhesions->count() >= 1)
                                        <hr class="mt-2 mb-2"
                                            style="border-top: dotted 5px; background-color: transparent">
                                        <div class="form-group d-flex align-items-center mb-2 gap-2">
                                            <div class="w-100 pe-2">
                                                <h4>Historique des adhésions</h4>
                                                @foreach ($user->adhesions->groupBy('saison') as $saison => $adhesions)
                                                    @php
                                                        $hasAdhesion = $adhesions->contains(
                                                            fn($adhesion) => $adhesion->type_article === 0,
                                                        );

                                                        $filteredAdhesions = $adhesions->reject(
                                                            fn($adhesion) => $adhesion->type_article === 0,
                                                        );
                                                    @endphp
                                                    <h5 class="mt-3">Saison {{ $saison }}-{{ $saison + 1 }}
                                                        @if ($hasAdhesion)
                                                            <img src="{{ asset('img/badge-membre.png') }}" alt=""
                                                                style="width: 55px; height: 45px;">
                                                        @endif
                                                    </h5>
                                                    @foreach ($filteredAdhesions as $adhesion)
                                                        <div class="d-flex justify-content-start gap-2">
                                                            @php
                                                                $status =
                                                                    $adhesion->bill_statut ??
                                                                    ($adhesion->old_bill_status ?? 100);
                                                            @endphp

                                                            <i class="fas fa-file-invoice-dollar"
                                                                style="color: {{ (int) $status !== 100 ? '#e74c3c' : 'grey' }}"></i>

                                                            <img src="{{ $adhesion->image }}"
                                                                style="width: 50px; height: 50px;" alt="adhesion-image">

                                                            <p class="m-0">{{ $adhesion->title }}</p>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                @endif
            </div>

            @if (empty($noDiplomas))
                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="card text-white inscriptions-card mb-3 rounded-3">
                            <div class="card-header fw-bold">Adhésions</div>
                            <div class="card-body">
                                <div>
                                    <div class="form-group d-flex align-items-center mb-2 gap-2">
                                        <label for="crt" class="me-2 w-25">Certif.Médic</label>
                                        @if ($user->medicalCertificate && $user->medicalCertificate->file_path)
                                            <i class="fas fa-file-medical" data-bs-toggle="modal"
                                                data-bs-target="#imageModal" style="cursor: pointer"
                                                title="Certificat médical"></i>
                                        @endif
                                        <input @if (auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id) readonly @endif type="file"
                                            id="crt" name="crt"
                                            class="form-control w-75
                                            @error('crt') is-invalid @enderror"
                                            accept="image/*" />
                                        @error('crt')
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group d-flex align-items-center mb-2 gap-2">
                                        <div class="w-50 pe-2">
                                            <label for="crt_emission" class="me-2 w-100">Date</label>
                                            <input @if (auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id) readonly @endif type="date"
                                                id="crt_emission" name="crt_emission"
                                                class="form-control w-100 @error('crt_emission') is-invalid @enderror"
                                                value="{{ $user->medicalCertificate->emission_date ?? '' }}" />
                                            @error('crt_emission')
                                                <span class="text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="w-50 pe-2">
                                            <label class="me-2 w-100" for="crt_delete">Supprimer</label>
                                            <input type="checkbox" class="form-check-input p-0 m-0"
                                                style="width:25px; height:25px;" id="crt_delete" name="crt_delete"
                                                value="1" @if (auth()->user()->role < $user->role && auth()->user()->user_id != $user->user_id) disabled @endif>
                                        </div>
                                    </div>
                                </div>
                                @if ($user->adhesions->count() >= 1)
                                    <hr class="mt-2 mb-2" style="border-top: dotted 5px; background-color: transparent">
                                    <div class="form-group d-flex align-items-center mb-2 gap-2">
                                        <div class="w-100 pe-2">
                                            <h4>Historique des adhésions</h4>
                                            @foreach ($user->adhesions->groupBy('saison') as $saison => $adhesions)
                                                @php
                                                    $hasAdhesion = $adhesions->contains(
                                                        fn($adhesion) => $adhesion->type_article === 0,
                                                    );

                                                    $filteredAdhesions = $adhesions->reject(
                                                        fn($adhesion) => $adhesion->type_article === 0,
                                                    );
                                                @endphp
                                                <h5 class="mt-3">Saison {{ $saison }}-{{ $saison + 1 }}
                                                    @if ($hasAdhesion)
                                                        <img src="{{ asset('img/badge-membre.png') }}" alt=""
                                                            style="width: 55px; height: 45px;">
                                                    @endif
                                                </h5>
                                                @foreach ($filteredAdhesions as $adhesion)
                                                    <div class="d-flex justify-content-start gap-2">
                                                        @php
                                                            $status =
                                                                $adhesion->bill_statut ??
                                                                ($adhesion->old_bill_status ?? 100);
                                                        @endphp

                                                        <i class="fas fa-file-invoice-dollar"
                                                            style="color: {{ (int) $status !== 100 ? '#e74c3c' : 'grey' }}"></i>

                                                        <img src="{{ $adhesion->image }}"
                                                            style="width: 50px; height: 50px;" alt="adhesion-image">

                                                        <p class="m-0">{{ $adhesion->title }}</p>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (!$isNotAuthorized)
                <div class="col-md-10 text-center mt-4">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#confirmModal">Enregistrer</button>
                </div>
            @endif

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
                                class="rounded mx-auto d-block zoomed-in w-100" alt="Certificat Médical">
                        @else
                            <div>Aucun certificat trouvé</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

<style>
    body {
        overflow-x: hidden;
    }

    main .identity-card {
        background-color: rgba(0, 94, 216, 0.8)
    }

    main .coordinates-card {
        background-color: rgba(225, 54, 54, 0.8)
    }

    main .diplomas-card {
        background-color: rgba(255, 242, 0, 0.684)
    }

    main .parents-card {
        background-color: rgba(166, 0, 255, 0.571)
    }

    main .inscriptions-card {
        background-color: rgba(50, 152, 32, 0.678)
    }

    main .card-header {
        background-color: transparent !important;
        font-weight: bold;
        padding: 0;
        font-size: 1.25rem;
        margin-bottom: 10px;
    }

    main .card {
        box-shadow: -1px 6px 15px -6px #000000;
    }

    main .card label {
        font-style: italic;
        color: unset;
    }

    main .form-control,
    main .form-select {
        margin: 0;
    }

    main button .btn.dropdown-toggle.btn-light {
        padding-right: 20px
    }

    .main .photo {
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

    .main .photo::after {
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

    .main .photo img {
        width: 120px;
        height: 120px;
        object-fit: cover;
    }

    .main .photo .user-id {
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
</style>
