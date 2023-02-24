<div class="modal fade " id="deleteFacture{{ $bills->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content text-center">
          <!--Body-->
          <div class="modal-body">
            
            
            <i class="mt-4 fa-solid fa-trash fa-4x animated rotateIn mb-4"></i>
            
    
            <p>Souhaitez vous vraiment annuler cette facture ? </p>
    
          </div>
    
          <!--Footer-->
          <div class="modal-footer flex-center">

            <form  action="{{ route("paiement.deleteFacture", $bills->id) }}" method="delete">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Yes</button>
            </form>
            
            <a type="button" class="btn btn-outline-success" data-dismiss="modal">No</a>
          </div>
        </div>
        <!--/.Content-->
      </div>
</div>
   

