@extends('layouts.app')

@section('content')
    @php
        use Carbon\Carbon;
        $authUser = auth()->user();
    @endphp
    <main style="min-height:100vh; background-image: url('{{ asset('/assets/images/background.png') }}" class="main "
        id="main">

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="container">
            <div class="row ">
                <div style="border: solid !important; border-width: 1px !important; border-color: grey !important; margin-bottom: 10px !important; box-shadow: 3px 3px 3px #5c5c5c !important;"
                    class="card shadow mb-4 mt-4 p-0">
                    <div style="background-color: #A9BCF5 !important"
                        class="card-header py-2  d-flex justify-content-center">
                        <div class="col-9 d-flex align-items-center justify-content-center">
                            <?php
                            setlocale(LC_TIME, 'fr_FR', 'fra');
                            
                            // Tableau pour les jours de la semaine
                            $jours = [
                                'Monday' => 'Lundi',
                                'Tuesday' => 'Mardi',
                                'Wednesday' => 'Mercredi',
                                'Thursday' => 'Jeudi',
                                'Friday' => 'Vendredi',
                                'Saturday' => 'Samedi',
                                'Sunday' => 'Dimanche',
                            ];
                            
                            // Tableau pour les mois
                            $mois = [
                                'January' => 'Janvier',
                                'February' => 'Février',
                                'March' => 'Mars',
                                'April' => 'Avril',
                                'May' => 'Mai',
                                'June' => 'Juin',
                                'July' => 'Juillet',
                                'August' => 'Août',
                                'September' => 'Septembre',
                                'October' => 'Octobre',
                                'November' => 'Novembre',
                                'December' => 'Décembre',
                            ];
                            
                            $date = new DateTime(); // obtenir la date d'aujourd'hui
                            
                            $jour = $date->format('l'); // obtenir le jour de la semaine
                            $mois_num = $date->format('n'); // obtenir le mois
                            $jour_num = $date->format('j'); // obtenir le jour du mois
                            $annee = $date->format('Y'); // obtenir l'année
                            
                            // Convertir le jour de la semaine et le mois en français
                            $jour_fr = $jours[$jour];
                            $mois_fr = $mois[date('F', mktime(0, 0, 0, $mois_num, 10))];
                            
                            ?>

                            <h6 class="m-0 font-weight-bold text-primary">
                                Joyeux anniversaire ! - <?php echo "$jour_fr $jour_num $mois_fr $annee"; ?>
                            </h6>

                        </div>
                    </div>
                    <div style="align-items: center !important; background-color : #FFFFFF !important"
                        class="card-body post-content">
                        @php
                            $date = new DateTime();
                            $dateString = $date->format('Y-m-d');
                            $filename = $dateString . '-birthday.jpg';

                        @endphp
                        <img src="{{ asset('assets/images/' . $filename) }}" class="img-fluid" alt=""
                            data-aos="zoom-out" data-aos-delay="100">
                    </div>
                    <div style="align-items: start !important; background-color : #FFFFFF !important"
                        class="card-body post-content">
                        <p class="mt-2">Vous pouvez envoyer un petit message à nos membres qui fêtent leurs anniversaires
                            aujourd’hui en cliquant sur leurs noms. À vous de jouer :</p>
                        <ul>
                            @foreach ($usersbirth as $user)
                                @php
                                    $age = Carbon::parse($user->birthdate)->diffInYears(Carbon::now());
                                @endphp
                                <li>
                                    <button data-bs-toggle="modal" data-bs-target="#emailModal"
                                        data-user-name="{{ $user->lastname }} {{ $user->name }}"
                                        data-user-email="{{ $user->email }}" data-user-id="{{ $user->user_id }}">
                                        Écrivez à : {{ $user->name }} {{ $user->lastname }} qui vient d'avoir ses
                                        <b>{{ $age }} ans</b>
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <p>La Gym Concordia vous souhaite un joyeux anniversaire!</p>
                    </div>

                </div>
            </div>
        </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="emailModalLabel">Envoyer un message</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('send.birthday.mail') }}" method="POST">
                            @csrf
                            <input type="hidden" name="recipient_id" id="recipient_id">
                            <input type="hidden" name="recipient_email" id="recipient_email">

                            <div class="mb-3">
                                <label for="recipient_name" class="col-form-label">Destinataire :</label>
                                <input type="text" class="form-control" id="recipient_name" name="recipient_name"
                                    readonly>
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                <div class="mb-3 flex-grow-1">
                                    <label for="sender_name" class="col-form-label">Votre nom et prénom :</label>
                                    <input type="text" class="form-control" id="sender_name" name="sender_name"
                                        @if ($authUser) value="{{ $authUser->name }} {{ $authUser->lastname }}" readonly @endif
                                        required>
                                </div>

                                <div class="mb-3 flex-grow-1">
                                    <label for="sender_email" class="col-form-label">Votre mail :</label>
                                    <input type="mail" class="form-control" id="sender_email" name="sender_email"
                                        @if ($authUser && $authUser->email) value="{{ $authUser->email }}" readonly @endif
                                        required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="message_text" class="col-form-label">Message :</label>
                                <textarea class="form-control" name="message" id="message_text" required></textarea>
                            </div>

                            <div class='form-row mt-3'>
                                <div class='form-group col-md-12' style="text-align:-webkit-center">
                                    <div class='g-recaptcha' name="captchaTest"
                                        data-sitekey='6Lf8zLIoAAAAAJtjcI7Xi6Lo5v07zwS6bnmCXS1g'></div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Envoyer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
<style>
    main button {
        background-color: transparent;
        color: black;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var emailModal = document.getElementById('emailModal');

        emailModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // Bouton qui a déclenché le modal
            var recipientId = button.getAttribute('data-user-id');
            var recipientName = button.getAttribute('data-user-name');
            var recipientEmail = button.getAttribute('data-user-email');

            // Met à jour les champs du modal
            document.getElementById('recipient_id').value = recipientId;
            document.getElementById('recipient_name').value = recipientName;
            document.getElementById('recipient_email').value = recipientEmail;
        });
    });
</script>
