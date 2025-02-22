@extends('layouts.template')

@section('content')
    <main class="main" id="main">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h5 class="card-title text-primary">Modifier l'organisation des images du carrousel</h5>

        <div class="container py-3">
            <div class="row justify-content-center">
                <!-- Liste des images du carrousel -->
                <div class="col-md-8">
                    <form id="carouselForm" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <ul class="list-group" id="items">
                            @foreach ($carouselImages as $image)
                                <li class="carousel-item-container w-100 mb-2" data-id="{{ $image->id }}">
                                    <img src="{{ asset($image->image_link) }}" alt="Carousel Image" class="img-thumbnail"
                                        data-bs-toggle="modal" data-bs-target="#editImageModal{{ $image->id }}"
                                        data-image-id="{{ $image->id }}">

                                    <!-- Modal Edit Image -->
                                    <div class="modal fade" id="editImageModal{{ $image->id }}" tabindex="-1"
                                        aria-labelledby="editImageModalLabel{{ $image->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editImageModalLabel{{ $image->id }}">
                                                        Modifier
                                                        l'image du carrousel</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Image Preview -->
                                                    <div class="mb-3">
                                                        <img src="{{ asset($image->image_link) }}" alt="Image Preview"
                                                            class="img-fluid mb-2">
                                                        <label for="image" class="form-label">Changer l'image</label>
                                                        <input type="file" class="form-control"
                                                            name="image[{{ $image->id }}]" id="image">
                                                    </div>

                                                    <!-- Lien associé à l'image -->
                                                    <div class="mb-3">
                                                        <label for="link" class="form-label">Lien associé à
                                                            l'image</label>
                                                        <input type="url" class="form-control"
                                                            name="link[{{ $image->id }}]" id="link"
                                                            value="{{ $image->click_link }}">
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Validation Button -->
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let carouselContainer = document.getElementById('items');

            new Sortable(carouselContainer, {
                animation: 150,
                ghostClass: 'blue-background-class',
                onEnd: function(evt) {
                    // Mise à jour de l'ordre des images
                    let orderedIds = [];
                    let items = document.querySelectorAll('.carousel-item-container');

                    items.forEach(function(item) {
                        orderedIds.push(item.getAttribute('data-id'));
                    });

                    // Ajout de l'ordre des images au formulaire
                    let orderInput = document.createElement('input');
                    orderInput.type = 'hidden';
                    orderInput.id = 'ordered-ids';
                    orderInput.name = 'ordered-ids';
                    orderInput.value = JSON.stringify(orderedIds);

                    let tempEl = document.querySelector("#ordered-ids")
                    if (tempEl) {
                        tempEl.remove();
                    }

                    document.getElementById('carouselForm').appendChild(orderInput);
                }
            });
        });
    </script>
@endsection

<style>
    .carousel-item-container {
        width: 200px;
        height: 200px;
        margin: 15px;
        position: relative;
        display: inline-block;
    }

    .carousel-item {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .carousel-item:hover {
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
