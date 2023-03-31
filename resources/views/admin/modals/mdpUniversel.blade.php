
          <!--Body-->
          <div class="modal-body">
            
            
            <img style="max-width:90px;" class="m-4" src="{{ asset('assets/images/rotate.png') }}"> 
            
    
            <p>Souhaitez vous vraiment réinitialiser le mot de passe de {{ $n_users->lastname }} {{ $n_users->name }}   ? </p>
    
          </div>
    
          <!--Footer-->
          <div class="modal-footer flex-center">

            <form action="{{ route("admin.mdpUniversel", $n_users->user_id) }}"   method="post">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-danger">Oui</button>
            </form>
            
          </div>
        