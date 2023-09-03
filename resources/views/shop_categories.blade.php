@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="heading">Magasinez par Catégories</h1>

    <div class="box-container">
        @foreach($shop_categories as $category)
            <div class="box" onclick="location.href='{{ route('sub_shop_categories', ['id' =>  $category->id_shop_category]) }}'">
                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}">
                <h3>{{ $category->name }}</h3>
                {!! $category->description !!}
                <a href="{{ route('sub_shop_categories', ['id' =>  $category->id_shop_category]) }}" class="btn">Explorer</a>
            </div>
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
    background:linear-gradient(45deg, #6C63FF, #83eaf1);  
    padding:15px 9%;
    padding-bottom: 100px;
    max-width: 100% !important;  
    min-height: 100vh;  
}

.container .heading{
    text-align: center;
    padding-bottom: 25px; 
    color:#fff;
    text-shadow: 0 5px 10px rgba(0,0,0,.15);
    font-size: 50px;
}

.container .box-container{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
    gap:25px; 
}

.container .box-container .box{
    box-shadow: 0 8px 15px rgba(0,0,0,.12); 
    border-radius: 10px; 
    background: #fff;
    text-align: center;
    padding:30px 20px;
    cursor: pointer;
    position: relative;
    min-height: 380px;
}

.container .box-container .box img{
    height: 90px;  
    margin-bottom: 15px; 
}

.container .box-container .box h3 {
  color: #444;
  font-size: 21px;
  padding: 10px 0;
}

.container .box-container .box p {
  color: #777;
  font-size: 16px;
  line-height: 1.9;
  height: 244px;
  overflow: hidden;
}

.container .box-container .box .btn{
    margin-top: 15px;
    display: inline-block;
    background:#6C63FF; 
    color:#fff;
    font-size: 17px;
    border-radius: 7px; 
    padding: 10px 30px; 
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
}

.container .box-container .box .btn:hover{
    background: #555; 
    letter-spacing: 1px;
}

.container .box-container .box:hover{
    box-shadow: 0 10px 25px rgba(0,0,0,.18);
    transform: translateY(-5px); 
}

@media (max-width:768px){
    .container{
        padding:20px;
    }
    .container .box-container .box p{
        height: 191px;
    }
}

</style>
@endsection
