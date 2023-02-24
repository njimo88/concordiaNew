@extends('layouts.app')

@section('content')
<div class="container">
    <div class="sign-up-form row justify-content-center ">

      <!-- Right (Form Content) -->
      <form class="form-content col-lg-10 justify-content-center pt-5" method="POST" action="{{ route('register') }}">
        <!-- Form Heading -->
        @csrf
        <div class="form-heading">
          <a href="{{ route('A_blog') }}"><img  src="{{asset('assets\images\logo.png')}}" alt="" /></a>
          <h1>Creation du compte</h1>
          <p>Veuillez remplir tous les champs obligatoires pour créer votre compte !</p>
        </div>

        <!-- Input Wrap -->
        <div class="input-wrap">

          <!-- Name & Lastname -->
          <div class="row">
            <div class="col-sm input">
              <input type="text" id="name" placeholder=" " class=" @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus />
              @error('name')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
              <div class="label">
                <label for="name">Nom</label>
              </div>   
            </div>
            <div class="col-sm input">
              <input type="text" id="lastname" placeholder=" " class=" @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname" autofocus />
              @error('lastname')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
              <div class="label">
                <label for="lastname">Prénom</label>
              </div>   
            </div>
          </div>

          <!-- Email -->
          <div class="input">
            <input type="email" id="email" placeholder=" " class=" @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" />
            @error('email')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="label">
              <label for="email">Adresse Mail</label>
            </div>
          </div>

          <!-- Paswword and confirmation -->
          <div class="row">
            <div class="col-sm input">
              <input type="password" id="password_update" placeholder=" " class=" @error('password') is-invalid @enderror" name="password"  autocomplete="new-password" />
              @error('password')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
              <div class="label">
                <label for="password">Mot de pass</label>
              </div>
            </div>
            <div class="col-sm input">
              <input type="password" id="password-confirm" placeholder=" "  name="password_confirmation"  autocomplete="new-password" />
              <div class="label">
                <label for="password-confirm">Confirmer le mot de passe</label>
              </div>
            </div>
          </div>

          <!-- Phone & Profession -->
          <div class="row">
            <div class="col-sm input">
              <input type="text" id="profession" placeholder=" " class=" @error('profession') is-invalid @enderror" name="profession" value="{{ old('profession') }}" required autocomplete="profession" autofocus />
              @error('name')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
              <div class="label">
                <label for="profession">Profession</label>
              </div>   
           </div>
           <div class="col-sm input">
              <input type="text" id="phone" placeholder=" " class=" @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus />
              @error('phone')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
              <div class="label">
                <label for="phone">Numéro de téléphone </label>
              </div>   
            </div>
          </div>
         
          <!-- Gender -->
          <div class="form-group">
            <label>Sexe</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                <label class="form-check-label" for="male">Homme</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                <label class="form-check-label" for="female">Femme</label>
            </div>
              @error('gender')
                  <div class="text-danger">{{ $message }}</div>
              @enderror
          </div>

          <!-- Birthdate-->
          <div class="input-group date" id='datetimepicker2'>
            <label style="margin-right:10px" for="birthdate">La Date de naissance</label><i class="ri-calendar-2-line"></i>
            <input class="datepicker" data-date-format="mm/dd/yyyy" type="date" name="birthdate" id="birthdate" class="" value="{{ old('birthdate') }}">
            
            @error('birthdate')
                <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <!-- Nationality-->
          <div class="form-group ">
            <label for="nationality" class="col-md-4 col-form-label text-md-right">Nationalité</label>
            <div class="col-md-6">
              <select data-flag="true" id="nationality" data-default="FR" class="selectpicker countrypicker @error('nationality') is-invalid @enderror" name="nationality" value="{{ old('nationality') }}" required autocomplete="nationality" autofocus>
              </select>
              @error('nationality')
                  <span class="text-alert" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
          </div>

          <div class="input">
            <input type="text" id="address" placeholder=" " class=" @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required autocomplete="address" autofocus />
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
              <input type="text"  placeholder=" " class=" @error('zip') is-invalid @enderror" name="zip" value="{{ old('zip') }}" required autocomplete="zip" autofocus />
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
              <input type="text" id="city" placeholder=" " class=" @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" required autocomplete="city" autofocus />
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
                <select data-flag="true" id="country" data-default="FR" class="selectpicker countrypicker @error('country') is-invalid @enderror" name="country" value="{{ old('country') }}" required autocomplete="country" autofocus>
                </select>
                @error('country')
                    <span class="text-alert" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>
         </div>
          
            <!-- submit button -->
          <div class="input-wrap">
            <button type="submit" >{{ __('Register') }}</button>
          </div>

        </div>
      </form>
    </div>
  </div>
@endsection

