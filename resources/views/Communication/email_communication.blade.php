@extends('layouts.template')

@section('content')
 
  <!-- Styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<!-- Or for RTL support -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css">
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

<script src="/path/to/cdn/jquery.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>

  <link href="../css/styleCom.css" rel="stylesheet">









</head>
<body>


<main id="main" class="main">
<div class="container">
@if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            <div class="row pt-5">
                    <div class="col-md-10">
                        
                    </div>
                    <div class="col-md-2">
                           <a href=""><button class="btn btn-warning"> retour</button></a>
                    </div>
            </div>

        

<form  method="POST" action="{{route('traitement')}}" enctype="multipart/form-data" formnovalidate="formnovalidate">
        @csrf
              
                <br>
                <!-- row vert  -->
   <div class="row">
   <div style="height: 250px;  overflow: scroll; ">
              <select multiple data-placeholder="Choix des articles"  id="article" name="article[]" onchange="func(this.value)" >
                @foreach($shop_article as $value)
                <option value="{{$value->id_shop_article}}">{{$value->title}}</option>
               @endforeach
              </select>
              <div class="row pt-5">
         
   </div>
  </div>
  <br>
  
            <br>

            <div class="row pt-5">
                    <div class="col-md-10">
                        
                    </div>
                    <div class="col-md-2">
                           <button type="submit" class="btn btn-warning" >Valider</button>
                    </div>
            </div>

            <br>


       

<!-- row rose -->
  <div class="row" style="background-color:pink; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">

          
          <div class="row">
          
              <div class="col-sm-12">
                        <br>
                
                              <label>Résumé </label>
                                <textarea type="text" name="short_description" class="form-control"></textarea>
                              <label>Description</label>
                                <textarea name="editor1"  id="ckeditor" class="form-control" required></textarea>
    
          
              </div>
          
          
          
          </div>
    
          
      </div>


    </div>
              
    </div>

</form>

<div class="col-md-2">
                           <button  onclick="test()">test</button>
                    </div>


<script>


function test(){

var Tab_articles = document.getElementById('article').selectedOptions;

var filter_tab =  heroes.filter(function(hero) {
    return hero.franchise == “Marvel”;
});



console.log(Tab_articles);

}



</script>


























<script>

function func(selectedValue) {

  $( "select" )
  .change(function () {
    var str = "";
    $( value="{{$value->id_shop_article}}" ).each(function() {
      str += $( this ).text() + " ";
    });
    $( "div" ).text( str );
  })
  .change();
  
  //make the ajax call
  $.ajax({
        url: 'Communication/get_info/'+article, 
        type: 'GET',
        data: 'JSON',
        success: function() {
            console.log("Data sent!");
        }
    });
}
</script>

<script src="//cdn.ckeditor.com/4.20.2/full/ckeditor.js"></script>
<script>
$(document).ready(function () {

var select = $('select[multiple]');
var options = select.find('option');

var div = $('<div />').addClass('selectMultiple');
var active = $('<div />');
var list = $('<ul />');
var placeholder = select.data('placeholder');

var span = $('<span />').text(placeholder).appendTo(active);

options.each(function () {
  var text = $(this).text();
  if ($(this).is(':selected')) {
    active.append($('<a />').html('<em>' + text + '</em><i></i>'));
    span.addClass('hide');
  } else {
    list.append($('<li />').html(text));
  }
});

active.append($('<div />').addClass('arrow'));
div.append(active).append(list);

select.wrap(div);

$(document).on('click', '.selectMultiple ul li', function (e) {
  var select = $(this).parent().parent();
  var li = $(this);
  if (!select.hasClass('clicked')) {
    select.addClass('clicked');
    li.prev().addClass('beforeRemove');
    li.next().addClass('afterRemove');
    li.addClass('remove');
    var a = $('<a />').addClass('notShown').html('<em>' + li.text() + '</em><i></i>').hide().appendTo(select.children('div'));
    a.slideDown(400, function () {
      setTimeout(function () {
        a.addClass('shown');
        select.children('div').children('span').addClass('hide');
        select.find('option:contains(' + li.text() + ')').prop('selected', true);
      }, 500);
    });
    setTimeout(function () {
      if (li.prev().is(':last-child')) {
        li.prev().removeClass('beforeRemove');
      }
      if (li.next().is(':first-child')) {
        li.next().removeClass('afterRemove');
      }
      setTimeout(function () {
        li.prev().removeClass('beforeRemove');
        li.next().removeClass('afterRemove');
      }, 200);

      li.slideUp(400, function () {
        li.remove();
        select.removeClass('clicked');
      });
    }, 600);
  }
});

$(document).on('click', '.selectMultiple > div a', function (e) {
  var select = $(this).parent().parent();
  var self = $(this);
  self.removeClass().addClass('remove');
  select.addClass('open');
  setTimeout(function () {
    self.addClass('disappear');
    setTimeout(function () {
      self.animate({
        width: 0,
        height: 0,
        padding: 0,
        margin: 0
      }, 300, function () {
        var li = $('<li />').text(self.children('em').text()).addClass('notShown').appendTo(select.find('ul'));
        li.slideDown(400, function () {
          li.addClass('show');
          setTimeout(function () {
            select.find('option:contains(' + self.children('em').text() + ')').prop('selected', false);
            if (!select.find('option:selected').length) {
              select.children('div').children('span').removeClass('hide');
            }
            li.removeClass();
          }, 400);
        });
        self.remove();
      })
    }, 300);
  }, 400);
});

$(document).on('click', '.selectMultiple > div .arrow, .selectMultiple > div span', function (e) {
  $(this).parent().parent().toggleClass('open');
});

});
  </script>


</main>


</body>

@endsection


