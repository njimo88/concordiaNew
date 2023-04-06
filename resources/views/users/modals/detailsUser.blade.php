<style>
    .card{
        margin-bottom: 0px !important;
        border: 0.6px solid black;
    }
    h6{
        color: #000;
    }
</style>
<div class="row d-flex justify-content-center align-items-center h-100">
  <div class="col-lg-12">
    <div class="card " style="border-radius: .5rem;">
      <div class="row g-0">
        <div class="col-md-4 gradient-custom text-center text-white"
          style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
            @if($user->image)
            <img class="img-fluid my-5" style="width: 80px;" src="{{  $user->image }}" >
          @elseif ($user->gender == 'male')
            <img class="img-fluid my-5" style="width: 80px;" src="{{ asset('assets\images\user.jpg') }}" alt="male">
          @elseif ($user->gender == 'female')
            <img class="img-fluid my-5" style="width: 80px;" src="{{ asset('assets\images\femaleuser.png') }}" alt="female">
          @endif
          <h5>{{ $user->name }} {{ $user->lastname }} </h5>
          <p>{{ $user->profession }}</p>
        </div>
        <div class="col-md-8">
          <div class="card-body p-4">
            <h6>Information</h6>
            <hr class="mt-0 mb-4 text-dark">
            <div class="row pt-1">
              <div class="col-6 mb-3">
                <h6>Email</h6>
                <p class="text-muted">{{ $user->email }}</p>
              </div>
              <div class="col-6 mb-3">
                <h6>Phone</h6>
                <p class="text-muted">{{ $user->phone }}</p>
              </div>
            </div>
            <h6>Autres</h6>
            <hr class="mt-0 mb-4 text-dark">
            <div class="row pt-1">
              <div class="col-6 mb-3">
                <h6>Age</h6>
                <p class="text-muted">{{ \Carbon\Carbon::parse($user->birthdate)->age }} ans</p>
              </div>
              <div class="col-6 mb-3">
                <h6>Adresse</h6>
                <p class="text-muted">{{ $user->address }} <br>{{ $user->city }} <br>{{ $user->country }}</p>
              </div>
            </div>
            <div class="d-flex justify-content-start">
              <a href="#!"><i class="fab fa-facebook-f fa-lg me-3"></i></a>
              <a href="#!"><i class="fab fa-twitter fa-lg me-3"></i></a>
              <a href="#!"><i class="fab fa-instagram fa-lg"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

