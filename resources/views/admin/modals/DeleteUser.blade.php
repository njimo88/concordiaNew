
          <!--Header-->
          <div class="modal-header d-flex justify-content-center">
            <h2>Suppression utilisateur</h2>
          </div>
    
          <!--Body-->
          <div class="modal-body">
            
            
            <i class=" fa-solid fa-trash fa-4x animated rotateIn mb-4"></i>
            
    
            <p>Etes-vous certain de vouloir supprimer l'utilisateur {{ $n_users->name }} {{ $n_users->lastname }} ?</p>
    
          </div>
    
          <!--Footer-->
          <div class="modal-footer flex-center">

            <form  action="{{ route("admin.DeleteUser", $n_users->user_id) }}" method="delete">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Oui</button>
            </form>
            
          </div>
        
   

