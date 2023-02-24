<style>
    .rounded-circle:hover {
        cursor: pointer;
    }
</style>
<div style="--bs-modal-width: 800px;" class="modal fade " id="editFamille{{ $n_users->user_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content ">
        <div class="container formProfil  mt-5 mb-5">
            <div class="row">
                <form  action="{{ route("users.editFamille", $n_users->user_id) }}" method="post">
                <div class="col-md-4 border-right">
                    
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        @if($n_users->image)
                                <img name="image"  id="existing-image"  style="max-height: 150px" class="rounded-circle" src="{{  $n_users->image }}" >
                              @elseif ($n_users->gender == 'male')
                                <img  name="image"  id="existing-image"  style="max-height: 150px" class="rounded-circle" src="{{ asset('assets\images\user.jpg') }}" alt="male">
                              @elseif ($n_users->gender == 'female')
                                <img  name="image"  id="existing-image"  style="max-height: 150px" class="rounded-circle" src="{{ asset('assets\images\femaleuser.png') }}" alt="female">
                        @endif
                        @error('image')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                        <span class="font-weight-bold">Edogaru</span><span class="text-black-50">{{ $n_users->email }}</span>
                    </div>
                </div>
                <div class="col-md-7 border-right">
                    
                        @csrf
                        @method('PUT')
                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right text-dark">Profile Settings</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6 input mt-2">
                                    <input type="text" id="name" placeholder=" " class=" @error('name') is-invalid @enderror" name="name" value="{{ $n_users->name }}"  autocomplete="name" autofocus />
                                    @error('name')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="label">
                                      <label for="name">Nom</label>
                                    </div>   
                                </div>
                                <div class="col-md-6 input mt-2">
                                    <input type="text" id="lastname" placeholder=" " class=" @error('lastname') is-invalid @enderror" name="lastname" value="{{ $n_users->lastname }}"  autocomplete="lastname" autofocus />
                                    @error('lastname')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="label">
                                      <label for="lastname">Prénom</label>
                                    </div>   
                                </div>
                                <div class="input col-md-7 mt-2">
                                    <input type="email" id="email" placeholder=" " class=" @error('email') is-invalid @enderror" name="email" value="{{ $n_users->email }}"  autocomplete="email" />
                                    @error('email')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="label ">
                                      <label for="email">Adresse Mail</label>
                                    </div>
                                </div>
                                <div class="col-md-6 input mt-2">
                                    <input type="password" id="password" placeholder=" " class=" @error('password') is-invalid @enderror" name="password"  autocomplete="new-password" />
                                    @error('password')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="label">
                                      <label for="password">Changer  Mot de pass</label>
                                    </div>
                                </div>
                                <div class="col-md-6 input mt-2">
                                    <input type="password" id="password-confirm" placeholder=" "  name="password_confirmation"  autocomplete="new-password" />
                                    <div class="label">
                                      <label for="password-confirm">Confirmer le mot de passe</label>
                                    </div>
                                </div>
                                <div class="col-md-6 input mt-2">
                                    <input type="text" id="profession" placeholder=" " class=" @error('profession') is-invalid @enderror" name="profession" value="{{ $n_users->profession }}"  autocomplete="profession" autofocus />
                                    @error('profession')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="label">
                                      <label for="profession">Profession</label>
                                    </div>   
                                </div>
                                <div class="col-md-6 input mt-2">
                                    <input type="text" id="phone" placeholder=" " class=" @error('phone') is-invalid @enderror" name="phone" value="{{ $n_users->phone }}"  autocomplete="phone" autofocus />
                                    @error('phone')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    
                                      <label class="label" for="phone">Numéro de téléphone </label>
                                      
                                </div>
                                <div style="margin-top: 20px" class="col-md-6 input-group date" id='datetimepicker2'>
                                    <label style="margin-right:10px" for="birthdate">La Date de naissance</label><i class="ri-calendar-2-line"></i>
                                    <input style="padding-top: 0px !important" class="datepicker" data-date-format="mm/dd/yyyy" type="date" name="birthdate" id="birthdate" class="" value="{{ $n_users->birthdate }}">
                                    
                                    @error('birthdate')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 form-group ">
                                    <label for="nationality" class="col-md-4 col-form-label text-md-right">Nationalité</label>
                                    <div class="col-md-6">
                                        <select id="nationality" data-default="{{ $n_users->nationality }}" value="$n_users->nationality " class="selectpicker countrypicker @error('nationality') is-invalid @enderror" name="nationality" data-flag="true" ></select>
                                      @error('nationality')
                                          <span class="text-alert" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                      @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 input mt-2">
                                    <input type="text" id="address" placeholder=" " class=" @error('address') is-invalid @enderror" name="address" value="{{ $n_users->address }}"  autocomplete="address" autofocus />
                                    @error('address')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="label">
                                      <label for="address">Adresse</label>
                                    </div>   
                                </div>
                                <div class="row mt-4">
                                    <div class="col-sm input mt-2 px-0">
                                      <input type="text" id="address" placeholder=" " class=" @error('zip') is-invalid @enderror" name="zip" value="{{ $n_users->zip}}"  autocomplete="zip" autofocus />
                                      @error('zip')
                                          <span class="text-danger" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                      @enderror
                                      <div class="label">
                                        <label for="zip">Code Postal</label>
                                      </div>   
                                    </div>
                                    <div class="col-sm input mt-2">
                                      <input type="text" id="city" placeholder=" " class=" @error('city') is-invalid @enderror" name="city" value="{{ $n_users->city}}"  autocomplete="city" autofocus />
                                      @error('city')
                                          <span class="text-danger" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                      @enderror
                                      <div class="label">
                                        <label for="city">Ville</label>
                                      </div>   
                                    </div>
                                    <div class="form-group px-0">
                                      <label for="country" class="col-md-4 col-form-label text-md-right">Pays</label>
                                      <div class="col-md-6">
                                        <select data-flag="true" id="country"  class="selectpicker countrypicker @error('country') is-invalid @enderror" name="country" data-default="{{ $n_users->country }}"  autocomplete="country" autofocus>
                                        </select>
                                        @error('country')
                                            <span class="text-alert" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                      </div>
                                    </div>
                                 </div>
        
                            </div>
                          
                            <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="submit">Save Profile</button></div>
                        </div>
                    
                </div>
            </form>
            </div>
        </div>
        </div>
        </div>
    </div>
    </div>
</div>
