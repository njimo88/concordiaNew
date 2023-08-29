<style>
    .slice-footer {
    background-color: #63c3d1 !important;   
     color: #482683;
    padding: 20px 0;
}
.btn-danger {
    color: #fff;
    background-color: #482683 !important;
    border-color: #482683 !important;
    border-radius: 50rem;
}
.btn-danger:hover {
    color: #fff !important;
    background-color: #63c3d1 !important;
    border-color: #482683 !important;
}
.text-white {
    --bs-text-opacity: 1;
    color: rgba(var(--bs-white-rgb), var(--bs-text-opacity)) !important;
}

/* Not sure what styles you wanted for the 'element' class as it's left blank. If you have more information, please provide. */
.element {

}

.bg-primary {
    --bs-bg-opacity: 1;
    
}

.text-danger {
    --bs-text-opacity: 1;
    color: #482683 !important;
}



.footer-logo {
    max-width: 150px;
    margin: 0 auto;
    display: block;
}



table {
    width: 100%;
}

table td {
    padding: 5px;
}

.d-block.m-2 {
    margin: 5px;
}

.d-block.m-2 i {
    font-size: 24px;
}




</style>

<section class="container-fluid bg-primary text-danger p-4 slice-footer reveal reveal-visible">
    <div class="container-xxl">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="row row-infos">
                    <div class="col-12 pt-3 pb-4">
                        <a href="https://www.concordia.fr/">
                            <img src="{{ asset('assets\images\LogoHB.png') }}" alt="Logo Gym Concordia" class="img-fluid footer-logo">
                        </a>
                    </div>

                    <div class="col-12 col-md-5 pt-1">
                        <div class="content">
                            <h5><span style="color: #482683;"><strong>Concordia Siège National</strong></span></h5>
                            <hr>
                            <table class="alignleft" style="width: 100%; border-collapse: collapse;">
                                <tbody>
                                    <tr style="height: 30px;">
                                        <td style="width: 16.2162%;"><img decoding="async" loading="lazy" class="aligncenter wp-image-267" src="https://www.concordia.fr/wp-content/uploads/2022/02/phone.png" alt="" width="26" height="26"></td>
                                        <td style="width: 83.5351%;"><span style="color: #482683;"><a style="color: #482683;" href="tel:0805659999">0 805 65 99 99</a></span></td>
                                    </tr>
                                    <tr style="height: 30px;">
                                        <td style="width: 16.2162%;"><i style="color: #482683;font-size: 30px;" class="fa-solid fa-envelope" ></i></td>
                                        <td style="width: 83.5351%;"><span style="color: #482683;"><a style="color: #482683;" href="mailto:bureau@gym-concordia.com">bureau@gym-concordia.com</a></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-12 col-md-7">
                        <div class="d-flex flex-wrap justify-content-end pt-3 pt-md-0">
                            <a href="https://www.concordia.fr/contact/" class="btn btn-danger text-white px-4 m-1" data-toggle="modal" data-target="#contactModal">Contact</a>
                        </div>

                        <div class="d-flex flex-wrap justify-content-center justify-content-md-end pt-3">
                            <a href="https://www.facebook.com/GymConcordia" target="_blank" style="color: #482683;" class="d-block m-2"><i class="bi bi-facebook"></i></a>
                            <a href="https://www.instagram.com/gym_concordia" target="_blank" style="color: #482683;" class="d-block m-2"><i class="bi bi-instagram"></i></a>
                            <a href="https://www.youtube.com/user/GymConcordia" target="_blank"style="color: #482683;" class="d-block m-2"><i class="bi bi-youtube"></i></a>
                            <a href="https://www.tiktok.com/discover/gym-concordia-schiltigheim?lang=fr"style="color: #482683;" target="_blank" class="d-block m-2"><i class="bi bi-tiktok"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-10 pt-3 pt-md-0">
                <div class="content text-center text-md-end">
                    <p><span style="color: #482683;">Réalisation : <a style="color: #482683;"  target="_blank" rel="noopener">Révélations-Communication</a></span><br>
                        <span style="color: #482683;"><a style="color: #482683;" href="{{route('index_mentions_legales')}}" target="_blank">Mentions légales</a> | <a style="color: #482683;" href="{{route('index_politique')}}" target="_blank" rel="noopener">Politique de confidentialité</a></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

