<div class="modal fade" id="editCategoryModal{{ $category->id }}" data-bs-focus="false" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">Modifier la Catégorie</h5>
                <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulaire de modification de la catégorie -->
                <form class="editCategoryForm" id="editCategoryForm{{ $category->id }}" action="{{ route('gestion.category.edit', $category->id) }}" method="POST">
                    @csrf
                
                    <!-- Titre -->
                    <div class="mb-3">
                        <label for="name-edit-{{ $category->id }}" class="form-label">Titre de la catégorie</label>
                        <input 
                            type="text" 
                            class="form-control @error('name-edit-' . $category->id) is-invalid @enderror" 
                            id="name-edit-{{ $category->id }}" 
                            name="name-edit-{{ $category->id }}" 
                            value="{{ old('name-edit-' . $category->id, $category->name) }}" 
                            required>
                        <div class="invalid-feedback"></div>
                    </div>
                
                    <!-- Couleur de la ligne -->
                    <div class="mb-3">
                        <label for="color-edit-{{ $category->id }}" class="form-label">Couleur de la catégorie</label>
                        <input 
                            type="color" 
                            class="form-control @error('name-edit-' . $category->id) is-invalid @enderror" 
                            id="color-edit-{{ $category->id }}" 
                            name="color-edit-{{ $category->id }}" 
                            value="{{ old('color-edit-' . $category->id, $category->color) }}" 
                            required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-secondary  close-modal" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Modifier la catégorie</button>
                    </div>
                </form>    
                <form action="{{ route('gestion.category.delete', $category->id) }}" class="deleteCategoryForm" method="POST" style="">
                    @csrf
                    <button type="submit" class="btn btn-danger">Supprimer la catégorie</button>
                </form>                 
            </div>
        </div>
    </div>
</div>
