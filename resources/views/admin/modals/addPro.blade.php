
<div class="modal fade " id="addPro" tabindex="-1" role="dialog" aria-labelledby="editProModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="container d-flex justify-content-center">
                <div class="row d-flex justify-content-center m-3">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 m-3">
                <div style="transform: none !important;" class="card h-100 m-4 row">
                <form action="{{ route("Professionnels.addPro", $user->user_id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="col-lg-12">
                        <h1 class="fw-bold">Choisisez un User pour être élu comme <span style="font-weight:bold">&#10024; Professionnel &#10024;</span></h1>
                    </div>
                    <div class="col-lg-5 mt-5">
                        <select name="selected_user_id_2" class="selectpicker" data-live-search="true" data-width="auto" data-style="btn-primary" id="user-select">
                            <option selected>Ouvrez ce menu de sélection</option>
                            @foreach($users as $user)
                                <option value="{{ $user->user_id }}">{{ $user->name }} {{ $user->lastname }} (n° {{ $user->user_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 mt-5">
                        <input type="hidden" id="selected_user_id" name="selected_user_id" value="{{ $user->user_id }}">
                        <button type="submit" class="btn btn-dark">Ajouter</button>
                    </div>
                </form>
                </div>
                </div>
                </div>
            </div>
            </div>
        </div>
</div>
