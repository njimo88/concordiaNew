<div class="modal fade" id="createAnimationModal" data-bs-focus="false" aria-labelledby="createAnimationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAnimationModalLabel">Créer une Animation</h5>
                <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulaire de création d'animation -->
                <form id="createAnimationForm" class="createAnimationForm" action="{{ route('gestion.animations.create') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="title-creation" class="form-label">Titre de l'animation</label>
                        <input type="text" class="form-control @error('title-creation') is-invalid @enderror" id="title-creation" name="title-creation" value="{{ old('title-creation') }}" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="image_path-creation" class="form-label">Image</label>
                        <input type="text" class="form-control @error('image_path-creation') is-invalid @enderror" id="image_path-creation" name="image_path-creation" value="{{ old('image_path-creation') }}">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="description-creation" class="form-label">Description</label>
                        <textarea class="form-control ckeditor-to-transform @error('description-creation') is-invalid @enderror" id="description-creation" name="description-creation" rows="3" required>{{ old('description-creation') }}</textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="teacher_id-creation" class="form-label">Enseignant</label>
                        <select class="form-select @error('teacher_id-creation') is-invalid @enderror" id="teacher_id-creation" name="teacher_id-creation" required>
                            <option value="">Choisir un enseignant</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->user_id }}">{{ $teacher->name }} {{ $teacher->lastname }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="animation_starttime-creation" class="form-label">Date et Heure de début de l'animation</label>
                        <input type="datetime-local" class="form-control @error('animation_starttime-creation') is-invalid @enderror" id="animation_starttime-creation" name="animation_starttime-creation" value="{{ old('animation_starttime-creation') }}" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="duration-creation" class="form-label">Durée (en heures)</label>
                        <input type="time" class="form-control @error('duration-creation') is-invalid @enderror" id="duration-creation" name="duration-creation" value="{{ old('duration-creation') }}" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="max_participants-creation" class="form-label">Nombre maximum de participants</label>
                        <input type="number" class="form-control @error('max_participants-creation') is-invalid @enderror" id="max_participants-creation" name="max_participants-creation" value="{{ old('max_participants-creation') }}" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="category_id-creation" class="form-label">Catégorie</label>
                        <select class="form-select @error('category_id-creation') is-invalid @enderror" id="category_id-creation" name="category_id-creation" required>
                            <option value="">Choisir une catégorie</option>
                            @foreach ($categories as $categorie)
                                <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="visibilite-creation" class="form-label">Visibilité</label>
                        <select class="form-control @error('visibilite-creation') is-invalid @enderror" id="visibilite-creation" name="visibilite-creation" required>
                            <option value="0" selected>Caché</option>
                            <option value="1">Visible</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="price-creation" class="form-label">Prix de l'inscription (€)</label>
                        <input type="number" class="form-control @error('price-creation') is-invalid @enderror" id="price-creation" name="price-creation" value="{{ old('price-creation') }}">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="room_id-creation" class="form-label">Lieu de l'animation</label>
                        <select class="form-control @error('room_id-creation') is-invalid @enderror" id="room_id-creation" name="room_id-creation" required>
                            <option value="">Choisir un lieu</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id_room }}">{{ $room->name }} - {{ $room->address }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-secondary me-2 close-modal" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Créer l'animation</button>
                    </div>                    
                </form>
            </div>
        </div>
    </div>
</div>