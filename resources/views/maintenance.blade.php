<!doctype html>
<title>Site Maintenance</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<style>
	html,
	body {
		padding: 0;
		margin: 0;
		width: 100%;
		height: 100%;
	}

	* {
		box-sizing: border-box;
	}

	body {
		text-align: center;
		padding: 0;
		background: #324881;
		color: #fff;
		font-family: Open Sans;
	}

	h1 {
		font-size: 50px;
		font-weight: 100;
		text-align: center;
	}

	body {
		font-family: Open Sans;
		font-weight: 100;
		font-size: 20px;
		color: #fff;
		text-align: center;
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-pack: center;
		-ms-flex-pack: center;
		justify-content: center;
		-webkit-box-align: center;
		-ms-flex-align: center;
		align-items: center;
	}

	article {
    display: block;
    width: 887px;
    padding: 50px;
    margin: 0 auto;
}

	a {
		color: #fff;
		font-weight: bold;
	}

	a:hover {
		text-decoration: none;
	}

	svg {
		width: 75px;
		margin-top: 1em;
	}
</style>


<article>
	@if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
    <img style="width: 595px ;display: block;
    margin: 0 auto;" src="{{ asset("assets/images/Logo - Concordia.png") }}" alt="">
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 202.24 202.24">
		<defs>
			<style>
				.cls-1 {
					fill: #fff;
				}
			</style>
		</defs>
		<title>Asset 3</title>
		<g id="Layer_2" data-name="Layer 2">
			<g id="Capa_1" data-name="Capa 1">
				<path class="cls-1" d="M101.12,0A101.12,101.12,0,1,0,202.24,101.12,101.12,101.12,0,0,0,101.12,0ZM159,148.76H43.28a11.57,11.57,0,0,1-10-17.34L91.09,31.16a11.57,11.57,0,0,1,20.06,0L169,131.43a11.57,11.57,0,0,1-10,17.34Z" />
				<path class="cls-1" d="M101.12,36.93h0L43.27,137.21H159L101.13,36.94Zm0,88.7a7.71,7.71,0,1,1,7.71-7.71A7.71,7.71,0,0,1,101.12,125.63Zm7.71-50.13a7.56,7.56,0,0,1-.11,1.3l-3.8,22.49a3.86,3.86,0,0,1-7.61,0l-3.8-22.49a8,8,0,0,1-.11-1.3,7.71,7.71,0,1,1,15.43,0Z" />
			</g>
		</g>
	</svg>
	<h2>Réouverture du site internet <br> le 25 mai 2023 à 10h00</h2>
	<div>
		<p>Désolé pour la gêne occasionnée. <br> Nous effectuons 
			actuellement une maintenance importante. <br> Vous pouvez nous suivre 
			 sur <a target="_blank" href="https://www.facebook.com/GymConcordia/?locale=fr_FR">
				Facebook</a> ou <a target="_blank" href="https://www.instagram.com/gym_concordia/?__coig_restricted=1">
					Instagram</a> </p>
		<p>Nous serons de retour trés vite &mdash; La Gym Concordia</p>
	</div>
	<br>
	<div class="m-3">
		<img style="width: 100%" src="https://www.gym-concordia.com/2023-05-15%20-%20Reinscriptions.jpg" alt="">
	</div>
	
	<footer>
		<p><a style="text-decoration: none" href="#" data-bs-toggle="modal" data-bs-target="#passwordModal">&copy;</a> Gym Concordia - {{ date('Y') }}</p>
	</footer>
	
	
	
	
</article>
<!-- Password Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="passwordModalLabel">Entrer le mot de passe</h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('verify_password') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="password" class="col-form-label">Mot de passe:</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
