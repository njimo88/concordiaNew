@extends('layouts.template')

@section('content')

<main id="main" class="main">
    <!-- Affichage du message de succès -->
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Bouton "Créer Catégorie Animation" en haut -->
    <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal" data-backdrop="static">Créer Catégorie</button>
    </div>

    <!-- Liste des catégories -->
    <div id="categories" class="content d-flex flex-wrap gap-3 justify-content-center">
        @if ($categories->isNotEmpty())
            @foreach ($categories as $category)
                <div class="card category-card p-3 d-flex align-items-center border border-2 border-secondary rounded-4" style="width: 250px; background-color: {{ $category->color }}; color: {{ $category->text_color }};">
                    <div class="category-header d-flex justify-content-center gap-3 align-items-center w-100">
                        <span class="category-badge" style="background-color: {{ $category->color }};"></span>
                        <h5 class="category-name m-0">{{ $category->name }}</h5>
                        <button class="btn border border-2 border-dark btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
    
                @include("admin.animations.modals.editCategorie", ['category' => $category])
            @endforeach
        @else
            <div>Aucune catégorie détectée</div>
        @endif
    </div>
    
    @include("admin.animations.modals.creerCategorie")
</main>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
    function handleFormSubmission(form, typeAction) {
        $(form).on('submit', function(event) {
            event.preventDefault();

            let form = $(this);

            let swalOptions = {
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Annuler"
            };

            if (typeAction === "create") {
                swalOptions.title = "Confirmer la création";
                swalOptions.text = "Voulez-vous vraiment créer cette catégorie ?";
                swalOptions.confirmButtonText = "Oui, créer !";
            } else if (typeAction === "edit") {
                swalOptions.title = "Confirmer la modification";
                swalOptions.text = "Voulez-vous vraiment modifier cette catégorie ?";
                swalOptions.confirmButtonText = "Oui, modifier !";
            } else {
                swalOptions.title = "Confirmer la suppression";
                swalOptions.text = "Voulez-vous vraiment supprimer cette catégorie ? CETTE ACTION EST IRREVERSIBLE";
                swalOptions.confirmButtonText = "Oui, supprimer !";
            }

            // Afficher la fenêtre de confirmation
            Swal.fire(swalOptions).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData(form[0]);

                    // Réinitialiser les erreurs précédentes
                    $('input, select, textarea').removeClass('is-invalid');
                    $('input, select, textarea').next('.invalid-feedback').text('');

                    // Soumettre le formulaire via AJAX
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(response) {
                            if (response.success) {
                                window.location.replace(response.redirect_url);
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    $('#' + key).addClass('is-invalid');
                                    if (key.includes('description')) {
                                        $('#cke_' + key).next('.invalid-feedback').text(value[0]);
                                    } else {
                                        $('#' + key).next('.invalid-feedback').text(value[0]);
                                    }
                                });
                            }
                        }
                    });
                }
            });
        });
    }

    // Appliquer la fonction à chaque type de formulaire
    handleFormSubmission('#createCategoryForm', 'create');
    handleFormSubmission('.editCategoryForm', 'edit');
    handleFormSubmission('.deleteCategoryForm', 'delete');
});
</script>
<style>
.category-card {
    border-radius: 15px;
}

.category-badge {
    width: 20px; 
    height: 20px; 
    border-radius: 15px;
}

div.invalid-feedback{
    display: block
}

.content {
    display: none;
}

.content.active {
    display: block;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}
</style>