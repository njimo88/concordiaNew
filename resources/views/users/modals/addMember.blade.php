
<div class="container">
  <div style="box-shadow:none;
  border:0 none;
  outline:0;" class="m-0 my-5 col-12 sign-up-form row justify-content-center ">
    <!-- Left (Form Image) -->

    <!-- Right (Form Content) -->
    <form class="form-content col-lg-11 justify-content-center" method="POST" action="{{ route('users.addMember') }}">
      <!-- Form Heading -->
      @csrf
      <div class="form-heading">
        <img src="{{asset('assets\images\LogoHB.png')}}" alt="" />
        <h1>Creation d'un compte parent</h1>
      </div>

      <!-- Input Wrap -->
      <div class="input-wrap">

        <!-- Name & Lastname -->
        <div class="row">
          <div class="col-sm input">
            <input type="text" id="nameMem" placeholder="Nom" class=" @error('nameMem') is-invalid @enderror" name="nameMem" value="{{ old('nameMem') }}" required autocomplete="nameMem" autofocus />
            @error('nameMem')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror  
          </div>
          <div class="col-sm input">
            <input type="text" id="lastnameMem" placeholder="Prénom" class=" @error('lastnameMem') is-invalid @enderror" name="lastnameMem" value="{{ old('lastnameMem') }}" required autocomplete="lastnameMem" autofocus />
            @error('lastnameMem')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror   
          </div>
        </div>

        <!-- Email -->
        <div class="input">
          <input type="email" id="emailMem" placeholder="Adresse Mail" class=" @error('emailMem') is-invalid @enderror" name="emailMem" value="{{ old('emailMem') }}" required autocomplete="emailMem" />
          @error('emailMem')
              <span class="text-danger" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>

        <!-- Paswword and confirmation -->
        <div class="row">
          <div class="col-sm input">
            <input type="password" id="passwordMem_update" placeholder="Mot de passe" class=" @error('passwordMem') is-invalid @enderror" name="passwordMem"  autocomplete="new-password" />
            @error('passwordMem')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="col-sm input">
            <input type="password" id="passwordMem-confirm" placeholder="Confirmer le mot de passe"  name="passwordMem_confirmation"  autocomplete="new-password" />
          </div>
        </div>

        <!-- Phone & Profession -->
        <div class="row">
          <div class="col-sm input">
            <input type="text" id="professionMem" placeholder="Profession" class=" @error('professionMem') is-invalid @enderror" name="professionMem" value="{{ old('professionMem') }}" required autocomplete="professionMem" autofocus />
            @error('professionMem')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror 
         </div>
         <div class="col-sm input">
            <input type="text" id="phoneMem" placeholder="Numéro de téléphone" class=" @error('phoneMem') is-invalid @enderror" name="phoneMem" value="{{ old('phoneMem') }}" required autocomplete="phoneMem" autofocus />
            @error('phoneMem')
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
              <input class="form-check-input" type="radio" name="genderMem" id="maleMem" value="male" {{ old('genderMem') == 'male' ? 'checked' : '' }}>
              <label class="form-check-label" for="male">Homme</label>
          </div>
          <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="genderMem" id="femaleMem" value="female" {{ old('genderMem') == 'female' ? 'checked' : '' }}>
              <label class="form-check-label" for="female">Femme</label>
          </div>
            @error('genderMem')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Birthdate-->
        <div class="input-group date" id='datetimepicker2'>
          <label style="margin-right:10px" for="birthdateMem">La Date de naissance</label><i class="ri-calendar-2-line"></i>
          <input class="datepicker" data-date-format="mm/dd/yyyy" type="date" name="birthdateMem" id="birthdateMem" class="" value="{{ old('birthdateMem') }}">
          
          @error('birthdateMem')
              <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>

        <!-- Nationality-->
        <div class="form-group ">
          <label for="nationalityMem" class="col-md-4 col-form-label text-md-right">Nationalité</label>
          <div class="col-md-6">
            <select data-flag="true" id="nationalityMem" data-default="FR" class="selectpicker countrypicker @error('nationalityMem') is-invalid @enderror" name="nationalityMem" value="{{ old('nationalityMem') }}" required autocomplete="nationalityMem" autofocus>
            </select>
            @error('nationalityMem')
                <span class="text-alert" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
        </div>

        <div class="input">
          <input type="text" id="addressMem" placeholder="Adresse" class=" @error('addressMem') is-invalid @enderror" name="addressMem" value="{{ old('addressMem') }}" required autocomplete="addressMem" autofocus />
          @error('addressMem')
              <span class="text-danger" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror  
        </div>


        <div class="row">
          <div class="col-sm input">
            <input type="text" id="zipMem" placeholder="Code Postal" class=" @error('zipMem') is-invalid @enderror" name="zipMem" value="{{ old('zipMem') }}" required autocomplete="zipMem" autofocus />
            @error('zip')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror 
          </div>
          <div class="col-sm input">
            <input type="text" id="cityMem" placeholder="Ville" class=" @error('cityMem') is-invalid @enderror" name="cityMem" value="{{ old('cityMem') }}" required autocomplete="cityMem" autofocus />
            @error('city')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror   
          </div>
          <div class="form-group ">
            <label for="countryMem" class="col-md-4 col-form-label text-md-right">Pays</label>
            <div class="col-md-6">
              <select data-flag="true" id="countryMem" data-default="FR" class="selectpicker countrypicker @error('countryMem') is-invalid @enderror" name="countryMem" value="{{ old('countryMem') }}" required autocomplete="countryMem" autofocus>
              </select>
              @error('countryMem')
                  <span class="text-alert" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
          </div>
       </div>
        
          <!-- submit button -->
        <div class="input-wrap">
          <button type="submit" >{{ __('Enregistrer') }}</button>
        </div>

      </div>
    </form>
  </div>
</div>


