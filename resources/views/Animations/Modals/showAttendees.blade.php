<div class="modal fade" id="showAttendeesModal{{ $animation->id }}" data-bs-focus="false" aria-labelledby="showAttendeesModalLabel{{ $animation->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showAttendeesModalLabel{{ $animation->id }}">Participants - {{ $animation->title }}</h5>
                <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Participants -->
                @if($animation->participants->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label"><strong>Participants :</strong></label>
                        <div class="table-responsive">
                            <ul>
                                @foreach ($animation->participants as $participant)
                                    <li>{{ $participant->first_name }} {{ $participant->last_name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="mb-3">
                        <h3>Aucun participant pour l'instant</h3>
                    </div>
                @endif

                <!-- Bouton de fermeture -->
                <div class="d-flex gap-3 justify-content-end">
                    <button type="button" class="btn-secondary close-modal" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</div>
