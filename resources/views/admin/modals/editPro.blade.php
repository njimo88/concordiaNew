<style>

.form-group {
background: #ffffff;
    text-align: left;
}

.card {
    background: #ffffff;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    border: 0;
    margin-bottom: 1rem;
}
.card {
    background: #ffffff;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    border: 0;
    margin-bottom: 1rem;
}
.card h6{
    text-align: left;
}
</style>
<div class="modal fade " id="editPro{{ $pros->cle }}" tabindex="-1" role="dialog" aria-labelledby="editProModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content ">
        <div class="container d-flex justify-content-center">
            <div class="row d-flex justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card h-100 m-2">
                <form  action="{{ route("Professionnels.editPro", $pros->cle) }}" method="post">
                    @csrf
                    @method('PUT')
                <div class="card-body">
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <h6 class="mb-2 text-dark font-weight-bold">Personal Details</h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="lastname">Nom</label>
                                <input type="text" class="form-control" name="lastname" value="{{ $pros->lastname }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="firstname">Prénom</label>
                                <input type="text" class="form-control" name="firstname" value="{{ $pros->firstname }}">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label for="matricule">Matricule</label>
                                <input type="number" step="0.01" class="form-control" name="matricule" value="{{ $pros->matricule }}">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label for="VolumeHebdo">Vol Hebdo</label>
                                <input type="number" step="0.01" class="form-control" name="VolumeHebdo" value="{{ $pros->VolumeHebdo }}">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label for="Groupe">Groupe</label>
                                <input type="number"  class="form-control" name="Groupe" value="{{ $pros->Groupe }}">
                            </div>
                        </div>
                    </div>
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <h6 class="mt-3 mb-2 text-dark font-weight-bold">Les jours de la semaines</h6>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <label for="Lundi">Lundi</label>
                                <input type="number" step="0.01" class="form-control" name="Lundi" value="{{ $pros->Lundi }}">
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <label for="Mardi">Mardi</label>
                                <input type="number" step="0.01" class="form-control" name="Mardi" value="{{ $pros->Mardi }}">
                            </div>
                        </div><div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <label for="Mercredi">Mercredi</label>
                                <input type="number" step="0.01" class="form-control" name="Mercredi" value="{{ $pros->Mercredi }}">
                            </div>
                        </div><div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <label for="Jeudi">Jeudi</label>
                                <input type="number" step="0.01" class="form-control" name="Jeudi" value="{{ $pros->Jeudi }}">
                            </div>
                        </div><div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <label for="Vendredi">Vendredi</label>
                                <input type="number" step="0.01" class="form-control" name="Vendredi" value="{{ $pros->Vendredi }}">
                            </div>
                        </div><div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <label for="Samedi">Samedi</label>
                                <input type="number" step="0.01" class="form-control" name="Samedi" value="{{ $pros->Samedi }}">
                            </div>
                        </div><div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-12">
                            <div class="form-group">
                                <label for="Dimanche">Dimanche</label>
                                <input type="number" step="0.01" class="form-control" name="Dimanche" value="{{ $pros->Dimanche }}">
                            </div>
                        </div>
                    </div>
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <h6 class="mt-3 mb-2 text-dark font-weight-bold">Autres</h6>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label for="Salaire">Salaire</label>
                                <input type="number" step="0.01" class="form-control" name="Salaire" value="{{ $pros->Salaire }}">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label for="Mardi">Prime</label>
                                <input type="number" step="0.01" class="form-control" name="Prime" value="{{ $pros->Prime }}">
                            </div>
                        </div><div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label for="masque">Masqué</label>
                                <input type="number" class="form-control" name="masque" value="{{ $pros->masque }}">
                            </div>
                        </div><div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="Embauche">Date d'embauche</label>
                                <input class="" data-date-format="mm/dd/yyyy" type="date" name="Embauche" name="Embauche" class="" value="{{ $pros->Embauche}}">
                            </div>
                        </div>
                    <div class="row gutters mt-4">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="text-right">
                                <button type="button" id="submit" name="submit" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <button type="submit" id="submit" name="submit" class="btn btn-primary">Sauver</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
