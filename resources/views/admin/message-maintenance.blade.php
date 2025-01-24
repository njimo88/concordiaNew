@extends('layouts.template')

@section('content')
<main class="main" id="main">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h5 class="card-title text-primary">Modifier le message de maintenance</h5>

    <div class="container py-3">
        <div class="card border-0 shadow-lg rounded-lg">
            <div class="card-body bg-light">
                <form id="messageForm" action="{{ route('message.maintenance.edit') }}" method="POST" class="bg-white mb-0">
                    @csrf
                    <div class="form-group">
                        <textarea name="editor1" class="form-control" required>{{ $message->Message }}</textarea>
                    </div>
                    <button type="button" class="btn  btn-success mt-3" data-toggle="modal" data-target="#confirmationModal">
                        Enregistrer
                    </button>

                    <!-- Modal de confirmation -->
                    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Êtes-vous sûr de vouloir enregistrer les modifications ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-primary" id="confirmSubmit">Confirmer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

<script src="https://cdn.ckeditor.com/4.25.0-lts/full/ckeditor.js"></script>