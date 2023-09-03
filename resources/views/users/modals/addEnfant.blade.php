
<div class="container">
  <div style="box-shadow:none;
  border:0 none;
  outline:0;" class="m-0 my-5 col-12 sign-up-form row justify-content-center ">
    <!-- Left (Form Image) -->

    <!-- Right (Form Content) -->
    <form class="form-content col-lg-11 justify-content-center" method="POST" action="{{ route('users.addEnfant') }}">
      <!-- Form Heading -->
      @csrf
      <div class="form-heading">
        <img src="{{asset('assets\images\LogoHB.png')}}" alt="" />
        <h1>Creation d'un compte enfant</h1>
      </div>

      <!-- Input Wrap -->
      <div class="input-wrap">

        <!-- Name & Lastname -->
        <div class="row">
          <div class="col-sm input">
            <input style="background-color: antiquewhite;" type="text" id="name" placeholder="Nom" class=" @error('name') is-invalid @enderror" name="name" value=" {{ $user->name }}" required autocomplete="name" autofocus />
            @error('name')
                
            @enderror  
          </div>
          <div class="col-sm input">
            <input type="text" id="lastname" placeholder="Prénom" class=" @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname" autofocus />
            @error('lastname')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
        </div>

        <!-- Email -->
        <div class="input">
          <input style="background-color: antiquewhite;" type="email" id="email" placeholder="Adresse Mail" class=" @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email" />
          @error('email')
              <span class="text-danger" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>

        <!-- Paswword and confirmation -->
        <div class="row">
          <div class="col-sm input">
            <input type="password" id="password_update" placeholder="Mot de passe" class=" @error('password') is-invalid @enderror" name="password"  autocomplete="new-password" />
            @error('password')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="col-sm input">
            <input type="password" id="password-confirm" placeholder="Confirmer le mot de passe"  name="password_confirmation"  autocomplete="new-password" />
          </div>
        </div>

        <!-- Phone & Profession -->
        <div class="row">
          <div class="col-sm input">
            <input type="text" id="profession" placeholder="Classe-Ecole-Etude-Profession" class=" @error('profession') is-invalid @enderror" name="profession" value="{{ old('profession') }}" required autocomplete="profession" autofocus />
            @error('name')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror 
         </div>
         <div class="col-sm input">
            <input style="background-color: antiquewhite;" type="text" id="phone" placeholder="Numéro de téléphone" class=" @error('phone') is-invalid @enderror" name="phone" value="{{ $user->phone }}" required autocomplete="phone" autofocus />
            @error('phone')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror  
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
          <input style="background-color: antiquewhite;" type="text" id="address" placeholder="Adresse" class=" @error('address') is-invalid @enderror" name="address" value="{{ $user->address }}" required autocomplete="address" autofocus />
          @error('address')
              <span class="text-danger" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror  
        </div>


        <div class="row">
          <div class="col-sm input">
            <input style="background-color: antiquewhite;" type="text" id="zip" placeholder="Code Postal" class=" @error('zip') is-invalid @enderror" name="zip" value="{{ $user->zip }}" required autocomplete="zip" autofocus />
            @error('zip')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
    
          </div>
          <div class="col-sm input">
            <input style="background-color: antiquewhite;" type="text" id="city" placeholder="Ville" class=" @error('city') is-invalid @enderror" name="city" value="{{ $user->city }}" required autocomplete="city" autofocus />
            @error('city')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror  
          </div>
          <div class="form-group ">
            <label for="country" class="col-md-4 col-form-label text-md-right">Pays</label>
            <div class="col-md-6">
              <select data-style="btn-warning" data-flag="true" id="country" data-default="FR" class="selectpicker countrypicker @error('country') is-invalid @enderror" name="country" value="{{ old('country') }}" required autocomplete="country" autofocus>
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
        <div class="mt-2 input-wrap">
          <button type="submit" >{{ __('Enregister') }}</button>
        </div>

      </div>
    </form>
  </div>
</div>


