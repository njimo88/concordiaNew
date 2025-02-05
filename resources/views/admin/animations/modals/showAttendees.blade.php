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
                                <table class="table table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Téléphone</th>
                                            <th>Age</th>
                                            <th>Email</th>
                                            <th>Contact Urgence</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($animation->participants as $participant)
                                            <tr>
                                                <td>{{ $participant->first_name }}</td>
                                                <td>{{ $participant->last_name }}</td>
                                                <td>{{ $participant->phone }}</td>
                                                <td>{{ $participant->age }}</td>
                                                <td>{{ $participant->email }}</td>
                                                <td>{{ $participant->emergency_contact }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
