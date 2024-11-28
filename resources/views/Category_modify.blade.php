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
    

            <div class="divo">

                    <form  action="{{ route('saveEditedCategory') }}" method="POST">
                                   
                                    {{csrf_field()}}
                       
                            <label for="fname">ID de la catégorie {{$info->id_shop_category}}</label>
                            <input class="form-control" hidden type="text"  id="fname" name="id" placeholder="ID de la catégorie.." value="{{$info->id_shop_category}}">
                                        
                            <label for="fname">Nom</label>
                            <input type="text" id="fname" name="nom" placeholder="le nom de la catégorie.." value="{{$info->name}}">
                                @error('nom')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            <label for="lname">Image</label>
                            <input type="text" id="lname" name="image" placeholder="le chemin de l'image.." value="{{$info->image}}">
                                @error('image')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            <label for="lname">Description</label>
                            <input hidden type="text" id="lname" name="description11" placeholder="la description.." value="{{$info->description}}">
                            <textarea name="editor1" id="ckeditor" class="form-control" required >{{$info->description}}</textarea>
                                @error('description')
                                 <span style="color: red;">{{ $message }}</span>
                                @enderror
                            <label for="">Action</label>
                            <select id="" name="action">
                                <option value="new_cat">Créer une nouvelle catégorie</option>
                                @foreach($Allshop_category as $dt)
                                
                                    @if ($info->id_shop_category_parent == $dt->id_shop_category)
                                        <option value="{{ $dt->id_shop_category  }}" selected>
                                            {{ $parrent_Category->id_shop_category}}-{{$parrent_Category->name }}
                                        </option>
                                    @else
                                        <option value="{{ $dt->id_shop_category}}" >
                                            {{ $dt->id_shop_category}}-{{$dt->name}} 
                                        </option>
                                    @endif
                                
                                @endforeach
                                
                            
                             </select>
                             <br>
                            
                            <label class="form-check-label" for="flexCheckDefault">
                                Active ?
                            </label>
                            <input class="form-check-input" type="checkbox" value="{{1}}" {{ $info->active=="1"? 'checked':'' }} name="active" id="flexCheckDefault">

                            <input type="submit" class="btn btn-success" value="Valider">
                            <a href="{{ route('A_Categorie') }}" class="btn btn-secondary btn-sm" >annuler</a>
                        
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