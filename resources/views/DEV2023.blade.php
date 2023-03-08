@extends('layouts.app')

@section('content')

<?php
use Illuminate\Support\Facades\DB;
use App\Models\Shop_category;
?>
@php
      $categories = Shop_category::all();
    @endphp
 <li class="dropdown"><a href="{{ route('index_categorie') }}"><span><img src="{{ asset("/assets/images/Inscriptions.png") }}" width="24">&nbsp;Achats</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
    <ul>
      <!-- First level dropdown: categories with one digit id -->
      @foreach($categories->filter(function ($category) {
          return strlen($category->id_shop_category) === 1;
      }) as $category)
      <li class="dropdown">
        <a href="{{ route('sous_categorie', ['id' =>  $category->id_shop_category]) }}">
          <span><img src="{{ $category->image }}" >&nbsp;{{ $category->name }}</span>
          
        </a>
        <!-- Second level dropdown: categories with three digits id -->
        <ul>
          @foreach($categories->filter(function ($subCategory) use ($category) {
              return strlen($subCategory->id_shop_category) === 3 && strpos($subCategory->id_shop_category, $category->id_shop_category) === 0;
          }) as $subCategory)
          <li class="dropdown">
            <a href="{{ route('sous_categorie', ['id' =>  $subCategory->id_shop_category]) }}">
              <span><img src="{{ $subCategory->image }}" >&nbsp;{{ $subCategory->name }}</span>
            </a>
            <!-- Third level dropdown: categories with four digits id -->
            <ul>
              @foreach($categories->filter(function ($subSubCategory) use ($subCategory) {
                  return strlen($subSubCategory->id_shop_category) === 4 && strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
              }) as $subSubCategory)
              <li>
                <a href="{{ route('sous_categorie', ['id' =>  $subSubCategory->id_shop_category]) }}">
                  <span><img src="{{ $subSubCategory->image }}" >&nbsp;{{ $subSubCategory->name }}</span>
                </a>
              </li>
              @endforeach
            </ul>
          </li>
          @endforeach
        </ul>
      </li>
      @endforeach
    </ul>
</li>
@endsection