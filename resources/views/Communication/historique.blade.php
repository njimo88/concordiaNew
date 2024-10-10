@extends('layouts.template')
@section('content')
<style>
    body {
        background-color: #f4f5f7;
    }

    #mailHistoryTable {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ddd;
        margin: 20px 0;
    }

    #mailHistoryTable th, #mailHistoryTable td {
        text-align: left;
        padding: 8px;
    }

    #mailHistoryTable tr {
        cursor: pointer;
        transition: all 0.3s;
    }

    #mailHistoryTable tr:hover {
        background-color: #c9bce0;
    }

    #mailHistoryTable th {
        background-color: #482683;
        color: white;
    }

    .modal-header {
        background-color: #482683;
        color: white;
    }

    .btn-primary {
        background-color: #482683;
        border-color: #482683;
    }

    .btn-primary:hover {
        background-color: #172881;
        border-color: #172881;
    }
</style>

<main class="main" id="main">
<table id="mailHistoryTable" class="display">
    <thead>
        <tr>
            <th>Expéditeur</th>
            <th>Titre</th>
            <th>Date/Heure</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mailHistories as $mail)
        <tr data-mail-id="{{ $mail->id }}">

            <td>{{ $mail->senderFullName }}</td> 
            <td>{{ $mail->title }}</td>
            <td>{{ \Carbon\Carbon::parse($mail->date)->format('d/m/Y H:i') }}</td>
                <div class="modal" id="mailModal{{ $mail->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ $mail->title }}</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                {!! $mail->message !!}
                                @if(auth()->user()->role >= 90)
                                    @php
                                        $recipients = json_decode($mail->id_user_destinataires, true);
                                    @endphp
                            
                                    <strong>Destinataires :</strong>
                                    @if(count($recipients) > 0)
                                        <ul>
                                            @foreach($recipients as $recipient)
                                                <li>{{ $recipient }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>Aucun destinataire disponible.</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        </tr>
        @endforeach
    </tbody>
</table>
</main>

@endsection