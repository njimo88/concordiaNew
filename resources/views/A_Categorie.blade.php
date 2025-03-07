@extends('layouts.template')

@section('content')
<script src="https://cdn.ckeditor.com/4.25.0-lts/full/ckeditor.js"></script>

<main id="main" class="main">
  
        <div class="container">

            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
        

            <h4> Catégories </h4>
<br>
            <div class="row">
                <div class="col dd" id="nestable-wrapper">
                    <ol class="dd-list list-group">
                        @foreach($categories as $k => $category)
                            <li class="dd-item list-group-item" data-id="{{ $category['id_shop_category'] }}" >
                                <div class="dd-handle" >{{ $category['name'] }} <small class="text-secondary">[{{ $category['id_shop_category'] }}]</small></div>
                                <div class="dd-option-handle">
                                    <a href="{{ route('category-edit', [ 'id_shop_category' =>  $category['id_shop_category'] ]) }}" class="btn btn-success btn-sm" >Modifier</a> 
                                    <a href="{{ route('category-remove', [ 'id_shop_category' =>  $category['id_shop_category'] ]) }}" class="btn btn-danger btn-sm" onclick="return confirm('êtes-vous sûr de vouloir supprimer?');">
                                        Supprimer</a> 
                                </div>

                                @if(!empty($category->categories))
                                    @include('A_child_categorie', [ 'category' => $category])
                                @endif
                            </li>
                        @endforeach
                    </ol>
                    <!-- 
                    <div class="row">
                        <form action="{{ route('save-categories') }}" method="post" >
                            @csrf
                            <textarea style="display: none;" name="nested_category_array" id="nestable-output"></textarea>
                            <button  type="submit" class="btn btn-success" style="margin-top: 15px;" >Save category</button>
                        </form>
                    </div>
                    -->
                </div>

                <div class="col dd" id="nestable-wrapper">

            <div class="divo">

                    <form action="{{ route('create-categories') }}" method="POST">
                                   
                                    {{csrf_field()}}
                         
                        <label hidden for="fname">ID de la catégorie</label>
                        <input type="text" hidden id="fname" name="id" placeholder="ID de la catégorie..">
                            
                        <label for="fname">Nom</label>
                        <input type="text" id="fname" name="nom" placeholder="le nom de la catégorie..">
                            @error('nom')
                            <span style="color: red;">{{ $message }}</span>
                            @enderror
                        <label for="lname">Image</label>
                        <input type="text" id="lname" name="image" placeholder="le chemin de l'image..">
                            @error('image')
                            <span style="color: red;">{{ $message }}</span>
                            @enderror
                        <label for="lname">Description</label>
                        <input hidden type="text" id="lname" name="description111111" placeholder="la description..">
                        <textarea name="editor1" id="ckeditor" class="form-control" required ></textarea>
                            @error('description')
                                <span style="color: red;">{{ $message }}</span>
                            @enderror

                        <label for="">Action</label>
                        <select id="" name="action">
                        
                        <option value="new_cat">Créer une nouvelle catégorie</option>

                        @foreach($shop_category as $dt)
                        
                         <option value="{{ $dt->id_shop_category }}">{{ $dt->id_shop_category}} -
                            {{ $dt->name }}</option>
                         
                        @endforeach

                        </select>
                        
                        <input class="form-check-input" type="checkbox" value="{{1}}"  name="active" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            Active ?
                        </label>
                       
                    
                        <input type="submit" value="Valider">
                    </form>
            </div>

            </div>

        </div>
        
<!-- 

<input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
-->

<!--  VERY IMPORTANT (GERE TOUTE LA PARTIE JS pour le Drag and Drop) 

<input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
-->

<script>
        $(document).ready(function()
{
    
    var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };

    // activate Nestable for list 1
    $('#nestable-wrapper').nestable({
        group: 1,
        maxDepth : 10,
    })
    .on('change', updateOutput);

    // output initial serialised data
    updateOutput($('#nestable-wrapper').data('output', $('#nestable-output')));
    
    $('#nestable-menu').on('click', function(e)
    {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });

    
});

</script>

</main>
@endsection