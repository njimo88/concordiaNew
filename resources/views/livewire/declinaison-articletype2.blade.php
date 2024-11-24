<div>
           
  <!-- debut templates pour declinison -->

<div class="container mt-4">
    @if (session('success'))
      
        <div class="alert alert-success text-sm">
            <strong>Success!</strong>  {{session('success')}}
        </div>
    @endif
    <div class="form-controle">
        <form id="declinationForm">
            
            <div class="mb-3">
                <input wire:model="declinationName" type="text" class="form-control" id="declinationName" placeholder="Entre Libelle de la déclinaison ">
                @error('declinationName')
                    <small  class="text-danger "> {{$message}} </small>
                @enderror
            </div>
            <div class="mb-3">
               
                <input wire:model="stockInitial" type="text" class="form-control" id="declinationName" placeholder="Enter stock initial">
                @error('stockInitial')
                    <small  class="text-danger "> {{$message}} </small>
                @enderror
            </div>
            <input type="hidden" id="declinationId">
        </form>
    </div>
    <div class="modal-footer">
        <button wire:click.prevent="create" type="button" class="btn btn-success btn-sm" id="saveDeclination">Ajouter déclinaison</button>
    </div>
<br>
    <ul class="list-group" id="declinationList">
        <!-- Example of existing declinations -->
        @foreach ($declinaisons as $item)
            @if ($EditedecID == $item->id)
                <li wire:key="{{$item->id}}" class="list-group-item d-flex justify-content-between align-items-center">
                    <input wire:model="NewdeclinationName" type="text" class="form-control form-control-sm">
                    @error('NewdeclinationName')
                        <small  class="text-danger "> {{$message}} </small>
                    @enderror
                    <label for="NewstockInitial" class=" text-black m-1" >  <small> Stock initial</small></label>
                    <input wire:model="NewstockInitial" type="text" class="form-control  form-control-sm"  >
                    @error('NewstockInitial')   
                        <small  class="text-danger "> {{$message}} </small>
                    @enderror
                    <label for="NewstockActual" class="text-black m-1"> <small> Stock actual</small></label>
                    <input wire:model="NewstockActual" type="text" class=" form-control  form-control-sm"  >
                    @error('NewstockActual')
                        <small  class="text-danger"> {{$message}} </small>
                    @enderror
                    <div>
                        <button wire:click.prevent="SaveEdit({{$item->id}})" class="btn btn-sm btn-success m-1 " >Save</button>
                        <button wire:click.prevent="reload" class="btn btn-sm btn-secondary " >annuler</button>
                    </div>
                </li>        
            @else
                <li wire:key="{{$item->id}}" class="list-group-item d-flex justify-content-between align-items-center">
                    {{$item->libelle}}  ( {{ $item->stock_ini_d }} / {{ $item->stock_actuel_d }} )
                    <div>
                        <button wire:click.prevent="edit({{$item->id}})" class="btn btn-sm btn-primary " >modifier</button>
                        <button wire:click.prevent="delete({{$item->id}})" class="btn btn-sm btn-danger " >supprimer</button>
                    </div>
                </li>
            @endif

            
        @endforeach
       
       
    </ul>


    

    
</div>



  <!-- fin templates pour declinison -->
</div>
