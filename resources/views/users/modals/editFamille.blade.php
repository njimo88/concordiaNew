<style>
    .rounded-circle:hover {
        cursor: pointer;
    }
</style>
<div style="--bs-modal-width: 81vw;" class="modal fade " id="editFamille{{ $n_users->user_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content ">
        <div style="max-width: 80vw !important;" class="container rounded bg-white m-0 my-4">
    <form  action="{{ route("users.editFamille", $n_users->user_id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-2">
                @if($n_users->image)
                        <img class="rounded-circle mt-5" width="150px" src="{{  $n_users->image }}" >
                    @elseif ($n_users->gender == 'male')
                        <img class="rounded-circle mt-5" width="150px" src="{{ asset('assets\images\user.jpg') }}" alt="male">
                    @elseif ($n_users->gender == 'female')
                        <img class="rounded-circle mt-5" width="150px" src="{{ asset('assets\images\femaleuser.png') }}" alt="female">
                    @endif
                <span class="text-dark">{{ $n_users->lastname }} {{ $n_users->name }} N°{{ $n_users->user_id }}</span>
            </div>
        </div>
    <div class="col-md-9 border-right">
        <div class="p-3 py-5">
            <div class="row">
                <div class="col-md-6 mt-2">
                    <h4 class="text-right mt-3">Paramètres du Profil</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 input mt-2">
                    <div class="labels">
                        <label for="name">Nom</label>
                    </div> 
                    <input class="form-control" type="text" id="name" placeholder=" " class=" @error('name') is-invalid @enderror" name="name" value="{{ $n_users->name }}"  autocomplete="name" autofocus @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                    readonly @endif />
                    @error('name')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 input mt-2">
                    <div class="labels">
                        <label for="lastname">Prénom</label>
                    </div> 
                    <input class="form-control" type="text" id="lastname" placeholder=" " class=" @error('lastname') is-invalid @enderror" name="lastname" value="{{ $n_users->lastname }}"  autocomplete="lastname" autofocus @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                    readonly @endif/>
                    @error('lastname')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 input mt-2">
                    <div class="labels">
                        <label for="username">username</label>
                    </div> 
                    <input @if(Route::currentRouteName() === 'users.family' || (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id))
                    readonly @endif class="form-control" type="text" id="username" placeholder=" " class=" @error('username') is-invalid @enderror" name="username" value="{{ $n_users->username }}"  autocomplete="username" autofocus />
                    @error('username')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>
            </div>
            <div class="row">
                <div class="col-md-4 input date mt-2" id='datetimepicker2'>
                    <label class="labels" for="birthdate">Naissance</label></i>
                    <input @if(Route::currentRouteName() === 'users.family' || (auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id))
                    readonly @endif class="form-control"  class="datepicker" data-date-format="mm/dd/yyyy" type="date" name="birthdate" id="birthdate" class="" value="{{ $n_users->birthdate }}">
                    @error('birthdate')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 input mt-2">
                    <div style="margin-bottom: 14px;" class="labels">
                        <label for="nationality">Nationalité</label>
                    </div>
                    <select @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                    disabled @endif  data-style="btn-light" id="nationality" data-default="{{ $n_users->nationality }}" value="$n_users->nationality " class="border col-md-12 form-group selectpicker countrypicker @error('nationality') is-invalid @enderror" name="nationality" data-flag="true" ></select>
                    @error('nationality')
                        <span class="text-alert" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 input mt-2">
                    <div class="labels">
                        <label for="profession">Profession</label>
                    </div>
                    <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                    readonly @endif class="form-control" type="text" id="profession" placeholder=" " class=" @error('profession') is-invalid @enderror" name="profession" value="{{ $n_users->profession }}"  autocomplete="profession" autofocus />
                    @error('profession')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="input col-md-4 mt-2">
                    <div class="labels">
                        <label for="email">Adresse Mail</label>
                    </div>
                    <input @if((auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id))
                    readonly @endif class="form-control" type="email" id="email" placeholder=" " class=" @error('email') is-invalid @enderror" name="email" value="{{ $n_users->email }}"  autocomplete="email" />
                    @error('email')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 input mt-2">
                    <div class="labels">
                        <label for="phone">Téléphone </label>
                    </div>
                    <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                    readonly @endif class="form-control"type="text" id="phone" placeholder=" " class=" @error('phone') is-invalid @enderror" name="phone" value="{{ $n_users->phone }}"  autocomplete="phone" autofocus />
                    @error('phone')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 input mt-2">
                    <div class="labels">
                        <label for="gender">Sexe</label>
                    </div>
                    <select style="margin-top: 14px;" @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                    disabled @endif class="form-select @error('gender') is-invalid @enderror" name="gender" id="gender" autocomplete="gender" autofocus>
                        <option value="male" {{ $n_users->gender == 'male' ? 'selected' : '' }}>Homme</option>
                        <option value="female" {{ $n_users->gender == 'female' ? 'selected' : '' }}>Femme</option>
                    </select>
                    @error('gender')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 input mt-2">
                    <div class="labels">
                        <label for="address">Adresse</label>
                    </div> 
                    <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                    readonly @endif type="text" id="address" placeholder=" " class="form-control @error('address') is-invalid @enderror" name="address" value="{{ $n_users->address }}"  autocomplete="address" autofocus />
                    @error('address')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-sm-4 input mt-2">
                    <div class="labels">
                        <label for="city">Ville</label>
                    </div>   
                    <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                    readonly @endif class="form-control" type="text" id="city" placeholder=" " class=" @error('city') is-invalid @enderror" name="city" value="{{ $n_users->city}}"  autocomplete="city" autofocus />
                    @error('city')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-sm-4 input mt-2 ">
                    <div class="labels">
                        <label for="zip">Code Postal</label>
                    </div> 
                    <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                    readonly @endif type="text" id="zip" placeholder=" " class=" form-control @error('zip') is-invalid @enderror" name="zip" value="{{ $n_users->zip}}"  autocomplete="zip" autofocus />
                    @error('zip')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4 input mt-2">
                    <div style="margin-bottom: 14px;" class="labels">
                        <label for="country">Pays</label>
                    </div>
                    <select  @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                    disabled @endif data-style="btn-light" data-flag="true" id="country"  class="col-12 border selectpicker countrypicker @error('country') is-invalid @enderror" name="country" data-default="{{ $n_users->country }}"  autocomplete="country" autofocus>
                    </select>
                    @error('country')
                        <span class="text-alert" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4 input mt-2">
                    <div class="labels">
                        <label for="crt">Certificat Médical</label>
                    </div>
                    @if($n_users->medicalCertificate && $n_users->medicalCertificate->file_path)
                        <img src="{{ asset($user->medicalCertificate->file_path) }}" alt="Certificat Médical" class="rounded mx-auto d-block" style="max-height: 150px;">
                    @endif
                    <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                    readonly @endif type="file" id="crt" name="crt" class="form-control" accept="image/*" class=" @error('crt') is-invalid @enderror" name="crt" />
                    @error('crt')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4 input mt-2">
                    <div class="labels">
                        <label for="crt">Date Expiration Certificat</label>
                    </div>
                    <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                    readonly @endif type="date" id="crt_expiration" name="crt_expiration" class="form-control" class=" @error('crt') is-invalid @enderror" name="crt_expiration" value="{{ $n_users->medicalCertificate->expiration_date ?? '' }}" />
                    @error('crt_expiration')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4 input mt-2">
                    <div class="labels">
                        <label for="password">Changer MDP</label>
                    </div>
                    <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                    readonly @endif class="form-control" type="password" id="password" placeholder=" " class=" @error('password') is-invalid @enderror" name="password"  autocomplete="new-password" />
                    @error('password')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4 input mt-2">
                    <div class="labels">
                        <label for="password-confirm">Confirmer MDP</label>
                    </div>
                    <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                    readonly @endif class="form-control" type="password" id="password-confirm" placeholder=" "  name="password_confirmation"  autocomplete="new-password" />
                </div>
            </div>
            @if(auth()->user()->role >= $n_users->role || auth()->user()->user_id == $n_users->user_id)
                <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="submit">Sauver Profil</button></div>
            @endif
        </div>
    </div>
</div>
</form>
</div>

        </div>
        </div>
    </div>
