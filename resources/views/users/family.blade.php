@extends('layouts.app')
@section('content')
<main style="width: 100vw; max-height: 300vh; min-height:100vh; background-image: url('{{asset("/assets/images/background.png")}}'); color:#fff;">
  <div  class="container justify-content-center d-flex">
    
    <div class="col-10 mt-4 mt-lg-0 ">
      @if($errors->any())
    <div class="alert alert-danger mt-3">
      Une erreur est survenue vérifiez les champs et réessayer.
  </div>
  @endif
  
    @if(session()->has('success'))
    <div class="alert alert-success ">
        {{ session()->get('success') }}
    </div>
  @endif
        <div class="row justify-content-center d-flex">
          <div style="border:0.5px solid; border-radius:20px; background : #fff;" class="col-lg-11  text-dark my-4 p-5">
            <h1>Famille de {{ $user->lastname }} {{ $user->name }}</h1>
         </div>
         
     

          <div  class="col-lg-11">
            <div class="user-dashboard-info-box  pl-3 mb-5 ">
              <table class="table manage-candidates-top">
                    <tbody >
                        @foreach ($n_users as $n_users)
                        <!-- details user modal -->
                        <div  style="--bs-modal-width:800px;" class="modal fade " id="detailsUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content ">
                                      <div class="container p-0 m-0" id="detailsUserContainer">
                                        
                                      </div>
                              </div>
                          </div>
                      </div>
                        <!-- Modal -->
                        @include('users.modals.editFamille')
                        <tr style=" background : #fff; border:0.5px solid; border-radius:20px;" class="candidates-list row mt-3 mb-3   ">
                            <td style="border-bottom-width: 0 !important;" class="title col-10 p-4">
                            <div class="thumb">
                              @if($n_users->image)
                                <img class="rounded-circle" src="{{  $n_users->image }}" >
                              @elseif ($n_users->gender == 'male')
                                <img class="rounded-circle" src="{{ asset('assets\images\user.jpg') }}" alt="male">
                              @elseif ($n_users->gender == 'female')
                                <img class="rounded-circle" src="{{ asset('assets\images\femaleuser.png') }}" alt="female">
                              @endif
                            </div>
                            <div class="candidate-list-details">
                                <div class="candidate-list-info">
                                <div class="candidate-list-title">
                                    <h5 class="mb-0">
                                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="EDIT">
                                          <a data-user-id="{{ $n_users->user_id }}" class="detailsUser" type="button" href="#">
                                            {{ $n_users->name }} {{ $n_users->lastname }} 
                                            @if ( $n_users->family_level == "child")
                                              (enfant)
                                            @else 
                                              (parent)
                                            @endif
                                          </a>
                                        </span>
                                    </h5>
                                </div>
                                <div class="candidate-list-option">
                                    <ul class="list-unstyled">
                                    <li><i class="fa-solid fa-cake-candles"></i><?php echo date("d/m/Y", strtotime($n_users->birthdate )); ?></li>
                                    <li><i class="fa-solid fa-envelope"></i>{{ $n_users->email }}</li>
                                    </ul>
                                </div>
                                </div>
                            </div>
                            </td>
                            <td style="border-bottom-width: 0 !important;" class="col-lg-2 ">
                              @if ( $user->family_level == "parent")
                                <ul style="margin-top: 4vh !important; margin-buttom:0 !important" class="list-unstyled  d-flex justify-content-center align-middle">
                                  <a data-toggle="modal" data-target="#editFamille{{ $n_users->user_id }}"  ><img  class="editbtn" src="{{ asset('assets/images/edit.png') }}" alt=""></a>
                                </ul>
                              @endif
                            </td>
                        </tr>   
                        @endforeach
                    </tbody>

              </table>
              <div class="mt-3 pb-5 px-5   ">
                <button data-toggle="modal" data-target="#addMember" type="button" value="{{ $user->family_id }}" class="btn mr-md-2 mb-md-0 mb-2 btn-primary">Ajouter un parent<i class="m-2 fa-regular fa-circle-plus"></i></button>
                <button data-toggle="modal" data-target="#addEnfant" type="button" value="{{ $user->family_id }}" class=" btn mr-md-2 mb-md-0 mb-2 btn-success">Ajouter un enfant<i class="m-2 fa-regular fa-circle-plus"></i></button>
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
    </div>    
</main>  
@endsection