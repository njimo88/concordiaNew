@extends('layouts.template')

@section('content')
<main id="main" class="main">
    <div style="--bs-modal-width: 80vw !important; height: 95vh !important;" class="modal fade " id="editusermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="editusermodalContainer">
                
            </div>
        </div>
           
    </div>
            
    

    <div class="modal fade " id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-info" role="document">
            <!--Content-->
            <div class="modal-content text-center" id="deleteUserContainer">

            </div>
            <!--/.Content-->
          </div>
    </div>

    <div class="modal fade " id="Resetpass" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-info" role="document">
            <!--Content-->
            <div class="modal-content text-center" id="ResetpassContainer">
            </div>
            <!--/.Content-->
          </div>
    </div>
       


    <!-- ---- modal famille facture ---- -->
    <div style="--bs-modal-width: 80vw !important; height: 95vh !important; overflow-y: auto;" class="modal fade " id="factureFamille" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
              <!--Body-->
                <section class="section">
                    <div class="row">
                        <div class="col-12 main-datatable" style="padding-right: calc(var(--bs-gutter-x) * .0) ; padding-left: calc(var(--bs-gutter-x) * .0);">
                            <div class="card_body">
                                <div class="row d-flex">
                                    <!-- Button trigger modal -->
                                    <div class="col-12 add_flex justify-content-center mt-4">
                                        <div class="text-center pt-3 pb-2">
                                            <img style="width: 100px" src="{{ asset('assets\images\family.png') }}"
                                                alt="Check" width="60">
                                            <h2 class="my-4">Factures Famille</h2>
                                            </div>
                                    </div>
                                    <div  class="row modal-body overflow-x" id="familyBillsContainer">
                                        <!-- content -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
           </div>
      </div>
    </div>

    <!-- ---- modal famille mem ---- -->
    <div style="--bs-modal-width: 80vw !important; height: 95vh !important; overflow-y: auto;" class="modal fade " id="familymem" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
              <!--Body-->
                <section class="section">
                    <div class="row">
                        <div class="col-12 main-datatable" style="padding-right: calc(var(--bs-gutter-x) * .0) ; padding-left: calc(var(--bs-gutter-x) * .0);">
                            <div class="card_body">
                                <div class="row d-flex">
                                    <!-- Button trigger modal -->
                                    <div class="col-12 add_flex justify-content-center mt-4">
                                        <div class="text-center pt-3 pb-2">
                                            <img style="width: 100px" src="{{ asset('assets\images\family.png') }}"
                                                alt="Check" width="60">
                                            <h2 class="my-4">Membres de la famille </h2>
                                            </div>
                                    </div>
                                    <div   id="familymemContainer">
                                        <!-- content -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
           </div>
      </div>
    </div>



    <div class="pagetitle">
       <h1>Liste des utilisateurs</h1>
       <nav>
          <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="index.html">Home</a></li>
             <li class="breadcrumb-item">Utilisateurs</li>
             <li class="breadcrumb-item active">Membres</li>
          </ol>
       </nav>
    </div>
    <section class="section">
        
        <div class="row">
            <div class="col-12 main-datatable">
                <div class="card_body">
                    <div class="row">
                        @if (session('success'))
                                <div class="alert alert-success col-12">
                                    {{ session('success') }}
                                </div>
                        @endif
                        <!-- Button trigger modal -->
                        <div class="col-6 form-group mt-3 ">
                            <button data-toggle="modal" data-target="#addMember" type="button"  class="m-0 user-link btn btn-primary">Ajouter un user <i class="mx-2 fa-solid fa-plus"></i></button>
                        </div>
                          <!-- Modal -->
                          <div class="modal fade " id="addMember" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered " role="document">
                              <div class="modal-content ">
                                @include('admin.modals.addUser')
                              </div>
                            </div>
                          </div>
                    </div>
                    <div class="overflow-x">
                        <table style="width:100%;" id="myTableMembers" class="table cust-datatable dataTable no-footer border">
                            <thead>
                                <tr>
                                    <th class="border">username</th>
                                    <th class="border">Nom Prénom</th>
                                    <th class="border">Téléphone</th>
                                    <th class="border">D Naissance</th>
                                    <th class="border">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                               
                                @foreach ($n_users as $n_users)
                                    <tr>
                                        <td style="width:200px">{{ $n_users->username }}</td>
                                        <td style="font-weight : bold;width:200px;">
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Editer le profil">
                                                <a data-user-id="{{ $n_users->user_id }}"  type="button" class="editusermodal user-link a text-black "  href="#">{{ $n_users->name }} {{ $n_users->lastname }}</a>
                                            </span>
                                        </td>
                                        <!-- Modal -->
                                                                             
                                        <td>{{ $n_users->phone }}</td>
                                        <td>@if($n_users->birthdate)
                                                <?php echo date("d/m/Y", strtotime($n_users->birthdate)); ?>
                                            @else
                                                $n_users->birthdate
                                            @endif
                                        </td>
                                        <td>   
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="DELETE">
                                                <img data-user-id="{{ $n_users->user_id }}" class="deleteUser editbtn2 mx-2" src="{{ asset('assets/images/delete.png') }}" alt="">
                                            </span>
                                            
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Facture Famille">
                                            <img  data-family-id="{{ $n_users->family_id }}"  type="button" class="familybill editbtn2 mx-2" src="{{ asset('assets/images/icon.png') }}"> 
                                            </span>
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Réinitialiser le mot de passe">
                                                <img data-user-id="{{ $n_users->user_id }}" class="Resetpass editbtn2 mx-2" src="{{ asset('assets/images/rotate.png') }}"> 
                                            </span>
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Les membres de ma famille">
                                                <img data-user-id="{{ $n_users->family_id }}"  type="button" class="familymem editbtn2 mx-2" src="{{ asset('assets/images/familyy.png') }}"> 
                                            </span>
                                        </td>
                                        
                                    </tr>
                                @endforeach
                    
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

   </section>
  
@endsection


