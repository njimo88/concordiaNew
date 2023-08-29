@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Fil d'Ariane -->
    <div class="breadcrumb">
        <a class="breadcrumb__step breadcrumb__step--active" href="{{ route('shop_categories') }}">Categories</a>
    
        @foreach($breadcrumb as $crumb)
            <a class="breadcrumb__step" href="{{ route('sub_shop_categories', ['id' => $crumb->id_shop_category]) }}">
                {{$crumb->name}}
            </a>
        @endforeach
    </div>

    <h1 class="heading">{{ $info2->name }}</h1>
    
    <div class="box-container">
        @foreach($info as $data)
            @if($data->id_shop_category_parent == $indice)
                <div class="box" onclick="location.href='{{ route('sous_categorie', ['id' => $data->id_shop_category]) }}'">
                    <img src="{{ $data->image }}" alt="{{ $data->name }}" class="img-fluid">
                    <h3>{{ $data->name }}</h3>
                    <a href="{{ route('sub_shop_categories', ['id' => $data->id_shop_category]) }}" class="btn">Explorer</a>
                </div>
            @endif
        @endforeach
    </div>

</div>



<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap');

*{
    font-family: 'Poppins', sans-serif;
    margin:0; padding:0;
    box-sizing: border-box;
    outline: none; border:none;
    text-decoration: none;
    text-transform: capitalize;
    transition: .3s ease;
}

.container{
    background:linear-gradient(45deg, #6C63FF, #83eaf1);  /* Changed the color gradient for a fresher look */
    padding:15px 9%;
    padding-bottom: 100px;
    max-width: 100% !important;  /* Added this to ensure the container is always 100% of the screen width */
    min-height: 100vh;  /* Added this to ensure the container is always 100% of the screen height */
}

.container .heading{
    text-align: center;
    padding-bottom: 25px;  /* Slight adjustment */
    color:#fff;
    text-shadow: 0 5px 10px rgba(0,0,0,.15);
    font-size: 50px;
}

.container .box-container{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
    gap: 25px; 
    justify-content: center;  /* Ajouté pour centrer les éléments horizontalement */
}


.container .box-container .box{
    box-shadow: 0 8px 15px rgba(0,0,0,.12); /* Softened the shadow */
    border-radius: 10px; /* Increased the border-radius for rounder edges */
    background: #fff;
    text-align: center;
    padding:30px 20px;
    cursor: pointer;
    position: relative;
    min-height: 250px;
}

.container .box-container .box img{
    height: 90px;  /* Adjusted the height */
    margin-bottom: 15px;  /* Added a margin for separation */
}

.container .box-container .box h3{
    color:#444;
    font-size: 24px; /* Slightly bigger font-size */
    padding:10px 0;
}

.container .box-container .box p{
    color:#777;
    font-size: 16px; /* Increased font-size for better readability */
    line-height: 1.9; /* Adjusted line height */
    height: 150px;  /* Fixed height to ensure all boxes are of the same height */
    overflow: hidden;  /* To handle overflow from longer descriptions */
}

.container .box-container .box .btn{
    margin-top: 15px;
    display: inline-block;
    background:#6C63FF; /* Changed color to match gradient */
    color:#fff;
    font-size: 17px;
    border-radius: 7px; /* Adjusted border-radius */
    padding: 10px 30px; /* Made the button slightly bigger */
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
}

.container .box-container .box .btn:hover{
    background: #555; /* Darkened on hover */
    letter-spacing: 1px;
}

.container .box-container .box:hover{
    box-shadow: 0 10px 25px rgba(0,0,0,.18);
    transform: translateY(-5px); /* A gentle upward lift on hover */
}

@media (max-width:768px){
    .container{
        padding:20px;
    }
}

</style>

<script>
    const breadcrumbSteps = document.querySelectorAll('.breadcrumb__step');

breadcrumbSteps.forEach((item, index, array) => {
    item.addEventListener('click', () => {
        array.forEach((step, idx) => {
            if (index >= idx) {
                step.classList.add('breadcrumb__step--active');
            } else {
                step.classList.remove('breadcrumb__step--active');
            }
        });
    });
});

</script>
@endsection