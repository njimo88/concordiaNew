@extends('layouts.app')

@section('content')
<div class="container formProfil  mt-5 mb-5">
    <div class="row">
        <div class="col-md-4 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"><span class="font-weight-bold">Edogaru</span><span class="text-black-50">edogaru@mail.com.my</span><span> </span></div>
        </div>
        <div class="col-md-7 border-right">
            <form  action="{{ route("users.update-profil") }}" method="post">
                @csrf
                @method('PUT')
                <div class="p-3 py-5 ">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Profile Settings</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 input">
                            <input type="text" id="name" placeholder=" " class=" @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}"  autocomplete="name" autofocus />
                            @error('name')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="label">
                              <label for="name">Nom</label>
                            </div>   
                        </div>
                        <div class="col-md-6 input">
                            <input type="text" id="lastname" placeholder=" " class=" @error('lastname') is-invalid @enderror" name="lastname" value="{{ $user->lastname }}"  autocomplete="lastname" autofocus />
                            @error('lastname')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="label">
                              <label for="lastname">Prénom</label>
                            </div>   
                        </div>
                        <div class="input col-md-7">
                            <input type="email" id="email" placeholder=" " class=" @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}"  autocomplete="email" />
                            @error('email')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="label">
                              <label for="email">Adresse Mail</label>
                            </div>
                        </div>
                        <div class="col-md-6 input">
                            <input type="password" id="password" placeholder=" " class=" @error('password') is-invalid @enderror" name="password"  autocomplete="new-password" />
                            @error('password')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="label">
                              <label for="password">Changer  Mot de passe</label>
                            </div>
                        </div>
                        <div class="col-md-6 input">
                            <input type="password" id="password-confirm" placeholder=" "  name="password_confirmation"  autocomplete="new-password" />
                            <div class="label">
                              <label for="password-confirm">Confirmer le mot de passe</label>
                            </div>
                        </div>
                        <div class="col-md-6 input">
                            <input type="text" id="profession" placeholder=" " class=" @error('profession') is-invalid @enderror" name="profession" value="{{ $user->profession }}"  autocomplete="profession" autofocus />
                            @error('name')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="label">
                              <label for="profession">Profession</label>
                            </div>   
                        </div>
                        <div class="col-md-6 input">
                            <input type="text" id="phone" placeholder=" " class=" @error('phone') is-invalid @enderror" name="phone" value="{{ $user->phone }}"  autocomplete="phone" autofocus />
                            @error('phone')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                              <label class="label" for="phone">Numéro de téléphone </label>
                              
                        </div>
                        <div style="margin-top: 20px" class="col-md-6 input-group date" id='datetimepicker2'>
                            <label style="margin-right:10px" for="birthdate">La Date de naissance</label><i class="ri-calendar-2-line"></i>
                            <input style="padding-top: 0px !important" class="datepicker" data-date-format="mm/dd/yyyy" type="date" name="birthdate" id="birthdate" class="" value="{{ $user->birthdate }}">
                            
                            @error('birthdate')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 form-group ">
                            <label for="nationality" class="col-md-4 col-form-label text-md-right">Nationalité</label>
                            <div class="col-md-6">
                              <select data-flag="true" id="nationality"  class="selectpicker countrypicker @error('nationality') is-invalid @enderror" name="nationality" value="$user->nationality"  autofocus>
                              </select>
                              @error('nationality')
                                  <span class="text-alert" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                            </div>
                        </div>
                        <div class="col-md-6 input mt-4">
                            <input type="text" id="address" placeholder=" " class=" @error('address') is-invalid @enderror" name="address" value="{{ $user->address }}"  autocomplete="address" autofocus />
                            @error('address')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="label">
                              <label for="address">Adresse</label>
                            </div>   
                        </div>
                        <div class="row">
                            <div class="col-sm input">
                              <input type="text" id="address" placeholder=" " class=" @error('zip') is-invalid @enderror" name="zip" value="{{ $user->zip}}"  autocomplete="zip" autofocus />
                              @error('zip')
                                  <span class="text-danger" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                              <div class="label">
                                <label for="zip">Code Postal</label>
                              </div>   
                            </div>
                            <div class="col-sm input">
                              <input type="text" id="city" placeholder=" " class=" @error('city') is-invalid @enderror" name="city" value="{{ $user->city}}"  autocomplete="city" autofocus />
                              @error('city')
                                  <span class="text-danger" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                              <div class="label">
                                <label for="city">Ville</label>
                              </div>   
                            </div>
                            <div class="form-group ">
                              <label for="country" class="col-md-4 col-form-label text-md-right">Pays</label>
                              <div class="col-md-6">
                                <select data-flag="true" id="country"  class="selectpicker countrypicker @error('country') is-invalid @enderror" name="country" value="$user->country"  autocomplete="country" autofocus>
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
            </form>
        </div>
        
    </div>
</div>
</div>
</div>
@endsection
