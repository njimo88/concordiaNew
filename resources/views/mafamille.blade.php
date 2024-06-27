@extends('layouts.app')

@section('content')
<style>
    .bg-cover, .bg-contain {
  background-position: center 27%;
}
.card-container{
width: 300px;
height: 430px;
background: #FFF;
border: 1px solid #e0e0e0 !important;  
border-radius: 8px !important;  
box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important; 
overflow: hidden;
display: inline-block;
margin: 20px;
}
.upper-container{
height: 150px;
background: #272E5C;
}
.image-container{
background: white;
width: 90px;
height: 88px;
border-radius: 50%;
padding: 5px;
transform: translate(100px,100px);
}
.image-container img{
width: 80px;
height: 80px;
border-radius: 50%;
}
.lower-container{
height: 280px;
background: #FFF;
padding: 20px;
padding-top: 40px;
text-align: center;
}
.lower-container h3, h4 ,h5{
box-sizing: border-box !important;
}
.lower-container h5{
color: #272E5C !important;
opacity: .6 !important;
font-weight: bold !important;
}
.lower-container p{
font-size: 16px !important;
color: gray !important;
margin-bottom: 30px !important;
}
.lower-container .btn{
padding: 12px 20px;
background: #272E5C;
border: none;
color: white;
border-radius: 30px;
font-size: 12px;
text-decoration: none;
font-weight: bold;
transition: all .3s ease-in;
}
.lower-container .btn:hover{
background: transparent;
color: #FFF !important;
border: 2px solid #272E5C;
}

.bg-primary {
  background-color: #272E5C !important;
}

.slick-dots li button::before {
  background: #FFF !important;
}

.slick-dots li.slick-active button::before {
  background: #02158d !important;
}
</style>

@if(session()->has('success'))
<div class="alert alert-success mt-3">
    {{ session()->get('success') }}
</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<section style="background-image: url('{{asset("/assets/images/famillee.jpeg")}}');" class=" bg-light position-relative bg-cover top-banner-page">
    <img loading="lazy" src="{{ asset("/assets/images/gymm.jpg") }}" alt="Blog" title="Blog" class="d-none">
    <div class="dark-overlay"></div>
    <div class="position-absolute bottom-0 start-50 translate-middle-x container-xl z-100">
        <div class="d-flex flex-column justify-content-end">
            <h1 class="h2 fw-black text-white">Ma Famille</h1>
        </div>
    </div>
</section>

<section class="container-fluid  py-4" style="background-color: #f2f2f2 ">
    <div class="container-xxl bg-white  p-4 shadow">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="content py-3">
                    @foreach($n_users as $n_users)
                        <div class="card-container">
                            <div class="upper-container">
                                <div class="image-container">
                                    @if($n_users->image)
                                        <img src="{{ $n_users->image }}" alt="{{ $n_users->name }}" onclick="chooseProfileImage({{ $n_users->user_id }});">
                                    @elseif ($n_users->gender == 'male')
                                        <img src="{{ asset('assets\images\user.jpg') }}" alt="male" onclick="chooseProfileImage({{ $n_users->user_id }});">
                                    @elseif ($n_users->gender == 'female')
                                        <img src="{{ asset('assets\images\femaleuser.png') }}" alt="female" onclick="chooseProfileImage({{ $n_users->user_id }});">
                                    @endif
                                </div>
                            </div>
                            <div class="lower-container">
                                <div>
                                    <h5>{{ $n_users->name }} {{ $n_users->lastname }}</h5>
                                    <h6>
                                        @if ( $n_users->family_level == "child")
                                            Enfant
                                        @else
                                            Parent
                                        @endif
                                        <!-- Gender icon -->
                                        @if ($n_users->gender == 'male')
                                            <i class="fas fa-mars text-primary"></i>
                                        @else
                                            <i class="fas fa-venus text-danger"></i>
                                        @endif
                                        <img src="https://flagcdn.com/w20/{{ strtolower($n_users->nationality) }}.png" alt="Country flag" width="20">
                                    </h6>
                                </div>
                                <div>
                                    <p>
                                        {{ date("d/m/Y", strtotime($n_users->birthdate )) }}<br>
                                        {{ $n_users->email }}<br>
                                    </p>
                                </div>
                                <div>
                                    
                                    <a data-toggle="modal" data-target="#editFamille{{ $n_users->user_id }}" class="btn">Voir Profil</a>
                                </div>
                            </div>
                        </div>
                        @include('users.modals.editFamille')
                        
                        
                        @endforeach
                        <div class="mt-3 pb-5 px-5   ">
                            <button data-toggle="modal" data-target="#addMember" type="button" value="{{ $user->family_id }}" class="btn mr-md-2 mb-md-0 mb-2 btn-primary">Ajouter un parent<i class="m-2 fa-regular fa-circle-plus"></i></button>
                            <button data-toggle="modal" data-target="#addEnfant" type="button" value="{{ $user->family_id }}" class=" btn-rouge mr-md-2 mb-md-0 mb-2 btn-success">Ajouter un enfant<i class="m-2 fa-regular fa-circle-plus"></i></button>
                          </div>
                          <!-- Modal parent-->
                          <div style="--bs-modal-width: 800px; !important" class="modal fade " id="addMember" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content ">
                                @include('users.modals.addMember')
                              </div>
                            </div>
                          </div>
            
                          <!-- Modal enfant-->
                          <div style="--bs-modal-width: 800px; !important" class="modal fade " id="addEnfant" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content ">
                                @include('users.modals.addEnfant')
                              </div>
                            </div>
                          </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('carouselArticles')
@endsection