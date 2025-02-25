@extends('layouts.template')

@section('content')
    <main class="main" id="main">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <h5 class="card-title text-primary">Modifier l'organisation des images du carrousel</h5>
        <a href="{{ route('edit.image') }}" class="btn btn-primary" target="_blank">Importer une image</a>

        <!-- Formulaire d'ajout d'image -->
        <div class="container py-3">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <form action="{{ route('carroussel.add') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="new_image" class="form-label">Nouvelle image</label>
                            <input type="file" class="form-control" name="new_image" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_click_link" class="form-label">Lien de page</label>
                            <select id="new_click_link" name="new_click_link" class="form-control form-select w-100">
                                <option value="" disabled selected>Choisir ou le blog associé sinon vide...</option>
                                @foreach ($blogArticles as $blogArticle)
                                    <option value="{{ $blogArticle->id_blog_post_primaire }}">{{ $blogArticle->titre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#confirmationModal">
                            Ajouter au carousel
                        </button>

                        <!-- Modal de confirmation -->
                        <div class="modal fade" id="confirmationModal" tabindex="-1"
                            aria-labelledby="confirmationModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmationModalLabel">Confirmer l'ajout</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir ajouter cette image au carousel ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">Confirmer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="container py-3">
            <div class="row justify-content-center">
                <!-- Validation Button -->
                <div class="text-center mb-3">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" data-bs-toggle="modal"
                        data-bs-target="#editImageModal">Valider les changements</button>
                </div>

                <!-- Liste des images du carrousel en grille -->
                <div class="col-md-10">
                    <form id="carouselForm" action="{{ route('carroussel.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Modal de confirmation pour modification -->
                        <div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel"
                            aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editImageModalLabel">
                                            Confirmer la modification</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir modifier le carroussel ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">Confirmer</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid-container" id="items">
                            @foreach ($carouselImages as $index => $image)
                                <div class="grid-item p-1 rounded" data-id="{{ $image->id }}"
                                    style="background-color: {{ $image->active ? 'green' : 'red' }}">
                                    <span class="position-indicator">{{ $index + 1 }}</span>

                                    <img src="{{ asset($image->image_link) }}" alt="Carousel Image"
                                        class="img-thumbnail p-0" data-bs-toggle="modal"
                                        data-bs-target="#editImageModal{{ $image->id }}">

                                    <!-- Modal Edit Image -->
                                    <div class="modal fade" id="editImageModal{{ $image->id }}"
                                        data-id="{{ $image->id }}" tabindex="-1"
                                        aria-labelledby="editImageModalLabel{{ $image->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editImageModalLabel{{ $image->id }}">
                                                        Modifier l'image du carrousel</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Image Preview -->
                                                    <div class="mb-3 text-center">
                                                        <img src="{{ asset($image->image_link) }}" alt="Image Preview"
                                                            class="img-fluid mb-2" style="max-width: 80%; height: auto;">
                                                        <label for="images" class="form-label">Changer l'image</label>
                                                        <input type="file" class="form-control"
                                                            name="images[{{ $image->id }}]">
                                                    </div>

                                                    <!-- Lien associé à l'image -->
                                                    <div class="mb-3">
                                                        <label for="links" class="form-label">Lien associé à
                                                            l'image</label>
                                                        <select name="links[{{ $image->id }}]"
                                                            class="form-control form-select-modal w-100">
                                                            <option value="">Aucun blog</option>
                                                            @foreach ($blogArticles as $blogArticle)
                                                                <option value="{{ $blogArticle->id_blog_post_primaire }}"
                                                                    {{ $image->click_link == "/blog/{$blogArticle->id_blog_post_primaire}" ? 'selected' : '' }}>
                                                                    {{ $blogArticle->titre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Visible -->
                                                    <div class="mb-3">
                                                        <label for="actives" class="form-label">Image active</label>
                                                        <input type="hidden" name="actives[{{ $image->id }}]"
                                                            value="0">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="actives[{{ $image->id }}]" value="1"
                                                            @if ($image->active) checked @endif>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Fermer</button>
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteImageModal{{ $image->id }}">Supprimer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal de confirmation pour suppression -->
                                <div class="modal fade" id="deleteImageModal{{ $image->id }}" tabindex="-1"
                                    aria-labelledby="deleteImageModal{{ $image->id }}Label" aria-hidden="true"
                                    data-bs-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteImageModal{{ $image->id }}Label">
                                                    Confirmer la suppression</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir supprimer cette image du carrousel ?
                                            </div>
                                            <div class="modal-footer">
                                                <!-- Suppression de l'image -->
                                                <form action="{{ route('carroussel.delete', $image->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary">Confirmer</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('.form-select').select2({
                width: '100%',
            });

            $('.form-select-modal').each(function() {
                var modalId = $(this).closest('.modal').attr('id');

                $(this).select2({
                    dropdownParent: $('#' + modalId),
                    width: '100%',
                });
            });

            let carouselContainer = document.getElementById('items');

            new Sortable(carouselContainer, {
                animation: 150,
                ghostClass: 'blue-background-class',
                onEnd: function(evt) {
                    let orderedIds = [];
                    let items = document.querySelectorAll('.grid-item');

                    items.forEach(function(item, index) {
                        let positionIndicator = item.querySelector('.position-indicator');
                        positionIndicator.textContent = index + 1;
                        orderedIds.push(item.getAttribute('data-id'));
                    });

                    let orderInput = document.createElement('input');
                    orderInput.type = 'hidden';
                    orderInput.id = 'ordered_ids';
                    orderInput.name = 'ordered_ids';
                    orderInput.value = JSON.stringify(orderedIds);

                    let tempEl = document.querySelector("#ordered_ids")
                    if (tempEl) {
                        tempEl.remove();
                    }

                    document.getElementById('carouselForm').appendChild(orderInput);
                }
            });
        });
    </script>

    <style>
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            justify-content: center;
        }

        .grid-item {
            width: 100%;
            height: auto;
            position: relative;
            text-align: center;
            cursor: grab;
        }

        .grid-item img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .grid-item img:hover {
            transform: scale(1.05);
        }

        .modal-header {
            background-color: #272E5C;
            color: white;
        }

        .modal-body {
            background-color: #f8f9fa;
        }

        .modal-footer {
            background-color: #f2f2f2;
        }

        .blue-background-class {
            background-color: rgba(0, 0, 255, 0.2) !important;
        }
    </style>
@endsection
