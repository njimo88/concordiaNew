<div class="modal fade" id="createCategoryModal" data-bs-focus="false" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Créer une Catégorie</h5>
                <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulaire de création d'animation -->
                <form id="createCategoryForm" class="createCategoryForm" action="{{ route('gestion.category.create') }}" method="POST">
                    @csrf
                        <!-- Titre -->
                        <div class="mb-3">
                            <label for="name-creation" class="form-label">Titre de la catégorie</label>
                            <input 
                                type="text" 
                                class="form-control @error('name-creation') is-invalid @enderror" 
                                id="name-creation" 
                                name="name-creation" 
                                value="{{ old('name-creation', '') }}" 
                                required>
                            <div class="invalid-feedback"></div>
                        </div>
                    
                        <!-- Couleur de la ligne -->
                        <div class="mb-3">
                            <label for="color-creation" class="form-label">Couleur de la catégorie</label>
                            <input 
                                type="color" 
                                class="form-control @error('color-creation') is-invalid @enderror" 
                                id="color-creation" 
                                name="color-creation" 
                                value="{{ old('color-creation',  '') }}" 
                                required>
                            <div class="invalid-feedback"></div>
                        </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-secondary me-2 close-modal" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Créer la catégorie</button>
                    </div>                    
                </form>
            </div>
        </div>
    </div>
</div>