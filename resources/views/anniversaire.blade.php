@extends('layouts.app')

@section('content')
    @php
        use Carbon\Carbon;
        $authUser = auth()->user();
    @endphp
    <main style="min-height:100vh;" class="main" id="main">
        <section id="birthday-container">
            <h1>Joyeux anniversaire !</h1>
            <canvas id="birthday"></canvas>
        </section>

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
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-sm border border-secondary rounded-3">
                        <div class="card-header bg-primary text-white text-center py-3 rounded-top">
                            <h4 class="m-0 font-weight-bold">
                                Joyeux anniversaire ! -
                                <?php
                                setlocale(LC_TIME, 'fr_FR', 'fra');
                                $jours = ['Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi', 'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'];
                                $mois = ['January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 'April' => 'Avril', 'May' => 'Mai', 'June' => 'Juin', 'July' => 'Juillet', 'August' => 'Août', 'September' => 'Septembre', 'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'];
                                
                                $date = new DateTime();
                                $jour_fr = $jours[$date->format('l')];
                                $mois_fr = $mois[$date->format('F')];
                                echo "$jour_fr " . $date->format('j') . " $mois_fr " . $date->format('Y');
                                ?>
                            </h4>
                        </div>

                        <div class="card-body">
                            <p class="text-center">
                                Vous pouvez envoyer un petit message à nos membres qui fêtent leurs anniversaires
                                aujourd’hui en cliquant sur leurs noms. À vous de jouer :
                            </p>
                            <ul class="list-unstyled">
                                @foreach ($usersbirth as $user)
                                    @php
                                        $age = Carbon::parse($user->birthdate)->diffInYears(Carbon::now());
                                    @endphp
                                    <li class="mb-2">
                                        <button class="btn btn-outline-primary w-100 text-start rounded"
                                            data-bs-toggle="modal" data-bs-target="#emailModal"
                                            data-user-name="{{ $user->lastname }} {{ $user->name }}"
                                            data-user-id="{{ $user->user_id }}">
                                            Écrivez à : {{ $user->name }} {{ $user->lastname }} qui vient d'avoir ses
                                            <b>{{ $age }} ans</b>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                            <p class="text-center mt-3">🎉 La Gym Concordia vous souhaite un joyeux anniversaire ! 🎉</p>
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
                                <div class='d-flex justify-content-center align-items-center'>
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
    @import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro');

    main button {
        background-color: transparent;
        color: black;
    }

    main .container {
        padding: 3rem 0;
    }

    main section#birthday-container {
        -webkit-box-shadow: 0px 9px 15px -7px #000000;
        box-shadow: 0px 9px 15px -7px #000000;
        padding: 0;
        position: relative;
        height: 350px;
        background: url('/assets/images/birthday-background.jpg') no-repeat center center;
        background-size: cover;
    }

    main section#birthday-container::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1;
    }

    main section#birthday-container h1 {
        position: absolute;
        text-align: center;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #fff;
        font-family: "Source Sans Pro";
        font-size: 5em;
        font-weight: 900;
        -webkit-user-select: none;
        user-select: none;
        z-index: 3;
    }

    main section#birthday-container canvas {
        display: block;
        position: absolute;
        object-fit: contain;
        background-color: transparent;
        z-index: 2;
    }

    @media (max-width: 1024px) {
        main section#birthday-container h1 {
            font-size: 4em;
        }
    }

    @media (max-width: 768px) {
        main section#birthday-container h1 {
            font-size: 3em;
        }

        main section#birthday-container {
            height: 300px;
        }
    }

    @media (max-width: 480px) {
        main section#birthday-container h1 {
            font-size: 2em;
        }

        main section#birthday-container {
            height: 250px;
        }
    }

    @media only screen and (max-width: 500px) {
        .g-recaptcha {
            transform: scale(0.85);
        }
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var emailModal = document.getElementById('emailModal');

        emailModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // Bouton qui a déclenché le modal
            var recipientId = button.getAttribute('data-user-id');
            var recipientName = button.getAttribute('data-user-name');

            // Met à jour les champs du modal
            document.getElementById('recipient_id').value = recipientId;
            document.getElementById('recipient_name').value = recipientName;
        });

        birthday();
    });


    /*
    Copyright (c) 2025 by Patrick Stillhart (https://codepen.io/arcs/pen/XKKYZW)

    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
     */
    function birthday() {
        // helper functions
        const PI2 = Math.PI * 2
        const random = (min, max) => Math.random() * (max - min + 1) + min | 0
        const timestamp = _ => new Date().getTime()

        // container
        class Birthday {
            constructor() {
                this.resize()

                // create a lovely place to store the firework
                this.fireworks = []
                this.counter = 0
            }

            resize() {
                this.width = canvas.width = window.innerWidth
                let center = this.width / 2 | 0
                this.spawnA = center - center / 4 | 0
                this.spawnB = center + center / 4 | 0

                this.height = canvas.height = window.innerHeight
                this.spawnC = this.height * .025
                this.spawnD = this.height * .35
            }

            onClick(evt) {
                let x = evt.clientX || evt.touches && evt.touches[0].pageX
                let y = evt.clientY || evt.touches && evt.touches[0].pageY

                let count = random(3, 5)
                for (let i = 0; i < count; i++) this.fireworks.push(new Firework(
                    random(this.spawnA, this.spawnB),
                    this.height,
                    x,
                    y,
                    random(0, 260),
                    random(30, 110)))

                this.counter = -1

            }

            update(delta) {
                ctx.globalCompositeOperation = 'hard-light'
                ctx.clearRect(0, 0, canvas.width, canvas.height)

                ctx.globalCompositeOperation = 'lighter'
                for (let firework of this.fireworks) firework.update(delta)

                // if enough time passed... create new new firework
                this.counter += delta * 3 // each second
                if (this.counter >= 1) {
                    this.fireworks.push(new Firework(
                        random(this.spawnA, this.spawnB),
                        this.height,
                        random(0, this.width),
                        random(this.spawnC, this.spawnD),
                        random(0, 360),
                        random(30, 110)))
                    this.counter = 0
                }

                // remove the dead fireworks
                if (this.fireworks.length > 1000) this.fireworks = this.fireworks.filter(firework => !firework.dead)

            }
        }

        class Firework {
            constructor(x, y, targetX, targetY, shade, offsprings) {
                this.dead = false
                this.offsprings = offsprings * 1.25

                this.x = x
                this.y = y
                this.targetX = targetX
                this.targetY = targetY

                this.shade = shade
                this.history = []
            }
            update(delta) {
                if (this.dead) return

                let xDiff = this.targetX - this.x
                let yDiff = this.targetY - this.y
                if (Math.abs(xDiff) > 3 || Math.abs(yDiff) > 3) { // is still moving
                    this.x += xDiff * 2 * delta
                    this.y += yDiff * 2 * delta

                    this.history.push({
                        x: this.x,
                        y: this.y
                    })

                    if (this.history.length > 20) this.history.shift()

                } else {
                    if (this.offsprings && !this.madeChilds) {

                        let babies = this.offsprings / 2
                        for (let i = 0; i < babies; i++) {
                            let targetX = this.x + this.offsprings * Math.cos(PI2 * i / babies) | 0
                            let targetY = this.y + this.offsprings * Math.sin(PI2 * i / babies) | 0

                            birthday.fireworks.push(new Firework(this.x, this.y, targetX, targetY, this.shade, 0))

                        }

                    }
                    this.madeChilds = true
                    this.history.shift()
                }

                if (this.history.length === 0) this.dead = true
                else if (this.offsprings) {
                    for (let i = 0; this.history.length > i; i++) {
                        let point = this.history[i]
                        ctx.beginPath()
                        ctx.fillStyle = 'hsl(' + this.shade + ',100%,' + i + '%)'
                        ctx.arc(point.x, point.y, 1.25, 0, PI2, false)
                        ctx.fill()
                    }
                } else {
                    ctx.beginPath()
                    ctx.fillStyle = 'hsl(' + this.shade + ',100%,50%)'
                    ctx.arc(this.x, this.y, 1.25, 0, PI2, false)
                    ctx.fill()
                }

            }
        }

        let canvas = document.getElementById('birthday')
        let ctx = canvas.getContext('2d')

        let then = timestamp()

        let birthday = new Birthday
        window.onresize = () => birthday.resize()
        document.onclick = evt => birthday.onClick(evt)
        document.ontouchstart = evt => birthday.onClick(evt)

        ;
        (function loop() {
            requestAnimationFrame(loop)

            let now = timestamp()
            let delta = now - then

            then = now
            birthday.update(delta / 1000)


        })()
    }
</script>
