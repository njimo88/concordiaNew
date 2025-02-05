<div class="modal fade" id="editAnimationModal{{ $animation->id }}" data-bs-focus="false" aria-labelledby="editAnimationModalLabel{{ $animation->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAnimationModalLabel{{ $animation->id }}">Modifier l'animation</h5>
                <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Affichage des informations de mise à jour -->
                @if($animation->updated_at && $animation->updated_by)
                    <div class="alert alert-info">
                        <p><strong>Cette animation a été modifiée par :</strong></p>
                        <p>{{ $animation->updater->name }} {{ $animation->updater->lastname }}</p>
                        <p><strong>Le :</strong> {{ \Carbon\Carbon::parse($animation->updated_at)->format('d/m/Y à H:i') }}</p>
                    </div>
                @endif
                
                <!-- Formulaire de modification d'animation -->
                <form class="editAnimationForm" id="editAnimationForm{{ $animation->id }}" action="{{ route('gestion.animations.edit', $animation->id) }}" method="POST">
                    @csrf
                
                    <!-- Titre -->
                    <div class="mb-3">
                        <label for="title-edit-{{ $animation->id }}" class="form-label">Titre de l'animation</label>
                        <input 
                            type="text" 
                            class="form-control @error('title-edit-' . $animation->id) is-invalid @enderror" 
                            id="title-edit-{{ $animation->id }}" 
                            name="title-edit-{{ $animation->id }}" 
                            value="{{ old('title-edit-' . $animation->id, $animation->title) }}" 
                            required>
                        <div class="invalid-feedback"></div>
                    </div>
                
                    <!-- Image -->
                    <div class="mb-3">
                        <label for="image_path-edit-{{ $animation->id }}" class="form-label">Image</label>
                        <input 
                            type="text" 
                            class="form-control @error('image_path-edit-' . $animation->id) is-invalid @enderror" 
                            id="image_path-edit-{{ $animation->id }}" 
                            name="image_path-edit-{{ $animation->id }}" 
                            value="{{ old('image_path-edit-' . $animation->id, $animation->image_path) }}">
                        <div class="invalid-feedback"></div>
                    </div>
                
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description-edit-{{ $animation->id }}" class="form-label">Description</label>
                        <textarea 
                            class="form-control ckeditor-to-transform @error('description-edit-' . $animation->id) is-invalid @enderror" 
                            id="description-edit-{{ $animation->id }}" 
                            name="description-edit-{{ $animation->id }}" 
                            rows="3" 
                            required>{{ old('description-edit-' . $animation->id, $animation->description) }}</textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                
                    <!-- Enseignant -->
                    <div class="mb-3">
                        <label for="teacher_id-edit-{{ $animation->id }}" class="form-label">Enseignant</label>
                        <select 
                            class="form-select @error('teacher_id-edit-' . $animation->id) is-invalid @enderror" 
                            id="teacher_id-edit-{{ $animation->id }}" 
                            name="teacher_id-edit-{{ $animation->id }}" 
                            required>
                            <option value="">Choisir un enseignant</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->user_id }}" {{ old('teacher_id-edit-' . $animation->id, $animation->teacher_id) == $teacher->user_id ? 'selected' : '' }}>
                                    {{ $teacher->name }} {{ $teacher->lastname }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                
                    <!-- Date et heure de début -->
                    <div class="mb-3">
                        <label for="animation_starttime-edit-{{ $animation->id }}" class="form-label">Date et Heure de début</label>
                        <input 
                            type="datetime-local" 
                            class="form-control @error('animation_starttime-edit-' . $animation->id) is-invalid @enderror" 
                            id="animation_starttime-edit-{{ $animation->id }}" 
                            name="animation_starttime-edit-{{ $animation->id }}" 
                            value="{{ old('animation_starttime-edit-' . $animation->id, $animation->animation_starttime) }}" 
                            required>
                        <div class="invalid-feedback"></div>
                    </div>
                
                    <!-- Durée de l'animation -->
                    <div class="mb-3">
                        <label for="duration-edit-{{ $animation->id }}" class="form-label">Durée (en heures)</label>
                        <input 
                            type="time" 
                            class="form-control @error('duration-edit-' . $animation->id) is-invalid @enderror" 
                            id="duration-edit-{{ $animation->id }}" 
                            name="duration-edit-{{ $animation->id }}" 
                            value="{{ old('duration-edit-' . $animation->id, $animation->duration) }}" 
                            required>
                        <div class="invalid-feedback"></div>
                    </div>
                
                    <!-- Nombre maximum de participants -->
                    <div class="mb-3">
                        <label for="max_participants-edit-{{ $animation->id }}" class="form-label">Nombre maximum de participants</label>
                        <input 
                            type="number" 
                            class="form-control @error('max_participants-edit-' . $animation->id) is-invalid @enderror" 
                            id="max_participants-edit-{{ $animation->id }}" 
                            name="max_participants-edit-{{ $animation->id }}" 
                            value="{{ old('max_participants-edit-' . $animation->id, $animation->max_participants) }}" 
                            required>
                        <div class="invalid-feedback"></div>
                    </div>
                
                    <!-- Catégorie -->
                    <div class="mb-3">
                        <label for="category_id-edit-{{ $animation->id }}" class="form-label">Catégorie</label>
                        <select 
                            class="form-select @error('category_id-edit-' . $animation->category_id) is-invalid @enderror" 
                            id="category_id-edit-{{ $animation->id }}" 
                            name="category_id-edit-{{ $animation->id }}" 
                            required>
                            <option value="">Choisir la catégorie</option>
                            @foreach ($categories as $categorie)
                                <option value="{{ $categorie->id }}" {{ old('category_id-edit-' . $animation->id, $animation->category_id) == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Visibilité -->
                    <div class="mb-3">
                        <label for="visibilite-edit-{{ $animation->id }}" class="form-label">Visibilité</label>
                        <select 
                            class="form-control @error('visibilite-edit-' . $animation->id) is-invalid @enderror" 
                            id="visibilite-edit-{{ $animation->id }}" 
                            name="visibilite-edit-{{ $animation->id }}" 
                            required>
                            <option value="0" {{ old('visibilite-edit-' . $animation->id, $animation->visibilite) == 0 ? 'selected' : '' }}>Caché</option>
                            <option value="1" {{ old('visibilite-edit-' . $animation->id, $animation->visibilite) == 1 ? 'selected' : '' }}>Visible</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                
                    <!-- Prix -->
                    <div class="mb-3">
                        <label for="price-edit-{{ $animation->id }}" class="form-label">Prix de l'inscription (€)</label>
                        <input 
                            type="number" 
                            class="form-control @error('price-edit-' . $animation->id) is-invalid @enderror" 
                            id="price-edit-{{ $animation->id }}" 
                            name="price-edit-{{ $animation->id }}" 
                            value="{{ old('price-edit-' . $animation->id, $animation->price) }}">
                        <div class="invalid-feedback"></div>
                    </div>
                
                    <!-- Lieu -->
                    <div class="mb-3">
                        <label for="room_id-edit-{{ $animation->id }}" class="form-label">Lieu de l'animation</label>
                        <select 
                            class="form-control @error('room_id-edit-' . $animation->id) is-invalid @enderror" 
                            id="room_id-edit-{{ $animation->id }}" 
                            name="room_id-edit-{{ $animation->id }}" 
                            required>
                            <option value="">Choisir un lieu</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id_room }}" {{ old('room_id-edit-' . $animation->id, $animation->room_id) == $room->id_room ? 'selected' : '' }}>
                                    {{ $room->name }} - {{ $room->address }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                
                    <div class="d-flex flex-wrap justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-secondary  close-modal" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Modifier l'animation</button>
                    </div>
                </form>    
                <form action="{{ route('gestion.animations.delete', $animation->id) }}" class="deleteAnimationForm" method="POST" style="">
                    @csrf
                    <button type="submit" class="btn btn-danger">Supprimer l'animation</button>
                </form>                 
            </div>
        </div>
    </div>
</div>
