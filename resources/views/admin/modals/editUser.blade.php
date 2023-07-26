
<div style="max-width: 80vw !important;" class="container rounded bg-white m-0 my-4">
               
                        
                    




                <form class="row"  action="{{ route("admin.editUser", $n_users->user_id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-md-3 border-right">
                        <div class="d-flex flex-column align-items-center text-center p-3 py-2">
                            <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>

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
                    <div class="p-3 pb-5">
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <h4 class="text-right mt-3">Paramètres du Profil</h4>
                            </div>
                            <div class="col-md-4 input mt-2">
                                <div class="labels">
                                    <label for="role">Rôle</label>
                                </div>
                                <select  @if(auth()->user()->role < 90 || Route::currentRouteName() === 'portesOuvertes')
                                    disabled 
                                    @endif 
                                    class="border col-md-12 form-group selectpicker @error('role') is-invalid @enderror" 
                                    name="role" 
                                    id="role" 
                                    autocomplete="role" 
                                    autofocus 
                                    role="listbox" 
                                    data-style='btn-info'>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $n_users->role == $role->id ? 'selected' : '' }} role="option">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                
                               
                                @error('role')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
                                <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
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
                                <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                                readonly @endif class="form-control"  class="datepicker" data-date-format="mm/dd/yyyy" type="date" name="birthdate" id="birthdate" class="" value="{{ $n_users->birthdate }}">
                                @error('birthdate')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 input mt-2">
                                <div class="labels">
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
                                <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
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
                                <select @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                                disabled @endif class="form-select @error('gender') is-invalid @enderror" name="gender" id="gender" autocomplete="gender" autofocus>
                                    <option value="">--Choisir une option--</option>
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
                                <div class="labels">
                                    <label for="country">Pays</label>
                                </div>
                                <select @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
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
                                    <label for="password">Changer MDP</label>
                                  </div>
                                <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id || Route::currentRouteName() != 'portesOuvertes')
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
                                <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id || Route::currentRouteName() != 'portesOuvertes')
                                readonly @endif class="form-control" type="password" id="password-confirm" placeholder=" "  name="password_confirmation"  autocomplete="new-password" />
                            </div>
                            <div class="col-sm-4 input mt-2">
                                <div class="labels">
                                    <label for="crt">Certificat médical</label>
                                  </div> 
                              <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                                readonly @endif type="text" id="crt" placeholder=" " class=" form-control @error('crt') is-invalid @enderror" name="crt" value=""  autocomplete="crt" autofocus />
                              @error('crt')
                                  <span class="text-danger" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                            </div>
                            <div class="col-sm-4 input mt-2">
                                <div class="labels">
                                    <label for="created_at">Date d'inscription</label>
                                  </div> 
                                  <input @if(auth()->user()->role < $n_users->role && auth()->user()->user_id != $n_users->user_id)
                                readonly @endif type="text" id="created_at" placeholder=" " class="form-control @error('created_at') is-invalid @enderror"  value="<?php echo date("d/m/Y", strtotime($n_users->created_at)); ?>" autocomplete="created_at" autofocus readonly>
                              @error('created_at')
                                  <span class="text-danger" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                            </div>
                            <div class="col-sm-4 input mt-2">
                                <div class="labels">
                                    <label for="licenceFFGYM">Licence FFGYM</label>
                                  </div> 
                              <input type="text" id="licenceFFGYM" placeholder=" " class=" form-control @error('licenceFFGYM') is-invalid @enderror" name="licenceFFGYM" value="{{ $n_users->licenceFFGYM }}"  autocomplete="licenceFFGYM" autofocus />
                              @error('licenceFFGYM')
                                  <span class="text-danger" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                            </div>
                        </div>
                        @if(auth()->user()->role >= $n_users->role || auth()->user()->user_id == $n_users->user_id)
                        
                            <div class="mt-3 text-end"><button class="btn btn-dark profile-button" type="submit">Sauver Profil</button></div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
