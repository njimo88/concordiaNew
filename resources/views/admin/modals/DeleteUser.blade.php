
          <!--Header-->
          <div class="modal-header d-flex justify-content-center">
            <h2>Delete User</h2>
          </div>
    
          <!--Body-->
          <div class="modal-body">
            
            
            <i class=" fa-solid fa-trash fa-4x animated rotateIn mb-4"></i>
            
    
            <p>Si vous supprimez l'utilisateur {{ $n_users->name }} {{ $n_users->lastname }}, il disparaîtra à jamais. Êtes-vous sûr de vouloir continuer ?</p>
    
          </div>
    
          <!--Footer-->
          <div class="modal-footer flex-center">

            <form  action="{{ route("admin.DeleteUser", $n_users->user_id) }}" method="delete">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Yes</button>
            </form>
            
          </div>
        
   

