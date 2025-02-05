<style>
.modal-content {
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
    grid-template:
        "photo name lastname username"auto "photo email birthdate phone"auto / auto 1fr 1fr 1fr;
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

.user-grid .email {
    grid-area: email;
}

.user-grid .birthdate {
    grid-area: birthdate;
}

.user-grid .phone {
    grid-area: phone;
}


.user-grid div {
    display: flex;
    flex-direction: column;
    justify-content: center;
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
            "email"
            "birthdate"
            "phone";
    }

    .user-grid .photo {
        justify-content: center;
        margin-bottom: 10px;
    }
}
</style>

<div class="modal fade" style="--bs-modal-width: 70vw;" id="editFamille{{ $n_users->user_id }}" tabindex="-1"
    role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('users.editFamille', $n_users->user_id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="container p-4">
                    <div class="user-grid">
                        <div class="photo">
                            @if($n_users->image)
                            <img src="{{ asset($n_users->image) }}" width="150px" alt="Profile Picture">
                            @elseif ($n_users->gender == 'male')
                            <img src="{{ asset('assets/images/user.jpg') }}" width="150px" alt="male">
                            @elseif ($n_users->gender == 'female')
                            <img src="{{ asset('assets/images/femaleuser.png') }}" width="150px" alt="female">
                            @endif
                            <span class="user-id">{{ $n_users->user_id }}</span>
                        </div>

                        <div class="name">
                            <div class="form-group">
                                <label for="name">Nom</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                                    name="name" value="{{ $n_users->name }}"
                                    {{ (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id) ? 'readonly' : '' }} />
                                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="lastname">
                            <div class="form-group">
                                <label for="lastname">Prénom</label>
                                <input class="form-control @error('lastname') is-invalid @enderror" type="text"
                                    id="lastname" name="lastname" value="{{ $n_users->lastname }}"
                                    {{ (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id) ? 'readonly' : '' }} />
                                @error('lastname') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="username">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input class="form-control @error('username') is-invalid @enderror" type="text"
                                    id="username" name="username" value="{{ $n_users->username }}"
                                    {{ (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id) ? 'readonly' : '' }} />
                                @error('username') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="birthdate">
                            <div class="form-group">
                                <label for="birthdate">Naissance</label>
                                <input class="form-control @error('birthdate') is-invalid @enderror" type="date"
                                    name="birthdate" id="birthdate" value="{{ $n_users->birthdate }}"
                                    {{ (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id) ? 'readonly' : '' }} />
                                @error('birthdate') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="email">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email" id="email"
                                    name="email" value="{{ $n_users->email }}"
                                    {{ (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id) ? 'readonly' : '' }} />
                                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="phone">
                            <div class="form-group">
                                <label for="phone">Téléphone</label>
                                <input class="form-control @error('phone') is-invalid @enderror" type="text" id="phone"
                                    name="phone" value="{{ $n_users->phone }}"
                                    {{ (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id) ? 'readonly' : '' }} />
                                @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row" style="--bs-gutter-x: 0.5rem;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address">Adresse</label>
                                <input class="form-control @error('address') is-invalid @enderror" type="text"
                                    id="address" name="address" value="{{ $n_users->address }}"
                                    {{ (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id) ? 'readonly' : '' }} />
                                @error('address') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="zip">Code Postal</label>
                                <input class="form-control @error('zip') is-invalid @enderror" type="text" id="zip"
                                    name="zip" value="{{ $n_users->zip }}"
                                    {{ (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id) ? 'readonly' : '' }} />
                                @error('zip') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city">Ville</label>
                                <input class="form-control @error('city') is-invalid @enderror" type="text" id="city"
                                    name="city" value="{{ $n_users->city }}"
                                    {{ (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id) ? 'readonly' : '' }} />
                                @error('city') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row" style="--bs-gutter-x: 0.5rem;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="country">Pays</label>
                                <select @if(auth()->user()->role < $n_users->role && auth()->user()->user_id !=
                                        $n_users->user_id)
                                        disabled
                                        @endif
                                        data-style="btn-light"
                                        data-flag="true"
                                        id="country"
                                        class="col-12 selectpicker countrypicker @error('country') is-invalid @enderror"
                                        name="country"
                                        data-default="{{ $n_users->country }}"
                                        autocomplete="country"
                                        autofocus>
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
                                <label for="nationality">Nationalité</label>
                                <select @if(auth()->user()->role < $n_users->role && auth()->user()->user_id !=
                                        $n_users->user_id)
                                        disabled
                                        @endif
                                        data-style="btn-light"
                                        data-default="{{ $n_users->nationality }}"
                                        id="nationality"
                                        name="nationality"
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

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender">Genre</label>
                                <select class="form-select" id="gender" name="gender"
                                    {{ (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id) ? 'disabled' : '' }}>
                                    <option value="male" {{ $n_users->gender == 'male' ? 'selected' : '' }}>Homme
                                    </option>
                                    <option value="female" {{ $n_users->gender == 'female' ? 'selected' : '' }}>Femme
                                    </option>
                                </select>
                                @error('gender') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row" style="--bs-gutter-x: 0.5rem;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="profession">Profession</label>
                                <input class="form-control @error('profession') is-invalid @enderror" type="text"
                                    id="profession" name="profession" value="{{ $n_users->profession }}"
                                    {{ (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id) ? 'readonly' : '' }} />
                                @error('profession') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password">Changer MDP</label>
                                <input class="form-control @error('password') is-invalid @enderror" type="password"
                                    id="password" name="password"
                                    {{ (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id) ? 'readonly' : '' }} />
                                @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password_confirmation">Confirmer MDP</label>
                                <input class="form-control @error('password_confirmation') is-invalid @enderror"
                                    type="password" id="password_confirmation" name="password_confirmation"
                                    {{ (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id) ? 'readonly' : '' }} />
                                @error('password_confirmation') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row" style="--bs-gutter-x: 0.5rem;">
                        <div class="col-md-4 input mt-2">
                            <div class="form-group">
                                <label for="crt">Certificat Médical</label>
                            </div>
                            @if($n_users->medicalCertificate && $n_users->medicalCertificate->file_path)
                            <img src="{{ asset($n_users->medicalCertificate->file_path) }}" alt="Certificat Médical"
                                class="rounded mx-auto d-block" style="max-height: 150px;">
                            @endif
                            <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id !=
                                $n_users->user_id) readonly @endif type="file" id="crt" name="crt" class="form-control
                                @error('crt') is-invalid @enderror" accept="image/*" />
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
                            <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id !=
                                $n_users->user_id) readonly @endif type="date" id="crt_emission" name="crt_emission"
                                class="form-control @error('crt_emission') is-invalid @enderror"
                                value="{{ $n_users->medicalCertificate->emission_date ?? '' }}" />
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
                                    value="1" @if(auth()->user()->role < $n_users->role && auth()->user()->user_id !=
                                    $n_users->user_id) disabled @endif>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        @if(auth()->user()->role >= $n_users->role || auth()->user()->user_id == $n_users->user_id)
                        <button type="submit" class="btn-save">Sauver Profil</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>