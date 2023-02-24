@extends('layouts.template')

@section('content')
<main id="main" class="main">
    <!-- ---- modal list declaration ---- -->
    <div class="modal fade" id="declarationList" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="exampleModalLabel">Liste de vos anciennes déclarations :</h4>
            </div>
            <div style="max-height: 600px !important;" class="modal-body overflow-auto" id="declarationListContainer">
                <!-- content -->
            </div>
          </div>
        </div>
      </div>




    <div class="pagetitle">
       <h1>Gestion des professionnels</h1>
       <nav>
          <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="index.html">Home</a></li>
             <li class="breadcrumb-item">professionnels</li>
             <li class="breadcrumb-item active">gestion</li>
          </ol>
       </nav>
    </div>
    
    <section class="section">    
        <div class="row">
            <div class="col-12 main-datatable">
                <div class="card_body">
                    <div class="row d-flex">
                        <!-- Button trigger modal -->

                          <!-- Modal -->
                          <div class="modal fade " id="addMember" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content ">
                                
                              </div>
                            </div>
                          </div>
                          @if (session('success'))
                                <div class="alert alert-success col-11">
                                    {{ session('success') }}
                                </div>
                          @endif
                    </div>
                    <div class="overflow-x">
                        <table style="width:100%;"  class="table cust-datatable dataTable no-footer">
                            <thead>
                                <th style="min-width:50px;"> <a>Nom</a></th>
                                <th style="min-width:150px;"><a>Prenom</a></th>
                                <th style="min-width:150px;"><a ></a></th>
                                <th style="min-width:100px;"><a ></a></th>
                                <th style="min-width:100px;"><a ></a></th>
                            </thead>                            
                            <tbody>
                                @foreach ($pro as $pros)
                                    <tr> 
                                        <td>{{ $pros->lastname }}</td>
                                        <td style="font-weight : bold;">{{ $pros->firstname }}</td> 
                                        <td><button data-toggle="modal" data-target="#editPro{{ $pros->cle }}" type="button" class="btn btn-warning">modifier</button> @include('admin.modals.editPro')</td>
                                        <td><a type="button" class="btn btn-danger" href="{{ route('Professionnels.declarationHeures',$pros->id_user)}}">Déclarer les heures</a></td>
                                        <td><button data-user-id="{{ $pros->id_user }}" type="button" class="declarationList btn btn-primary">Anciennes déclarations</button></td>
                                        
                                    </tr>  
                                @endforeach
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-start; " class="col-lg-12 float-right">
                <button data-toggle="modal" data-target="#addPro"  type="button" class="mt-4 btn btn-dark">Ajouter un professionnel<i style="font-weight:900;" class="m-2 fa-regular fa-circle-plus"></i></button>
                @include('admin.modals.addPro')
            </div>
        </div>
    </section>
</main>


@endsection
