@extends('layouts.app')

@section('content')
<main style="background-image: url('{{asset("/assets/images/background.png")}}');min-height:100vh;" id="main" class="main">

<div class="row" id="corps-pricipal" style=" padding:16px; justify-content:center">
    <div class="col-lg-10 border border-dark" style="background-color: white; padding:16px; border-radius:10px;margin-top:80px;">
        <div class="row">
            <div class="col-lg-6">
        <h3 style="
  position: relative;
  bottom: -1px;
  padding-right: .5em;
  font-family: DINPro,arial;
  font-size: 25px;
  text-transform: uppercase;
  font-weight: Bold;">Votre panier :</h3>
        </div>
     

    @if (count($paniers) > 0)

        <div class="col-lg-6" style="text-align: right">
                                                                <p><a href="#" data-toggle="modal" data-target="#genDevisModal" style="background-color: #000dff" class="btn btn-primary">Générer un devis</a></p>
                                    
        </div>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <table  class="table table-striped ">
        <tbody >
            @foreach ($paniers as $key => $panier)
            @if ($key == 0 || ($panier->name != $paniers[$key-1]->name || $panier->lastname != $paniers[$key-1]->lastname))
            <tr  class="table-secondary">
                <td width="20%">
                    <h6><b>{{ $panier->lastname }} {{ $panier->name }}</b></h6>
                </td>
                <td colspan="5"></td>
            </tr>
            @endif
            <tr>
                <td width="10%" class="align-middle"><p class="text-muted small">{{ $panier->reff }} @if ($panier->declinaison_libelle != null)
                    [{{ $panier->declinaison_libelle }}]
                @endif</p></td>
                <td width="20%" class="align-middle">
                    <img width="70px" src="{{ $panier->image }}">
                </td>
                <td width="30%" class="align-middle">
                    <h6 class="text-dark">{{ $panier->title }}
                        @if ($panier->declinaison_libelle != null)
                            [{{ $panier->declinaison_libelle }}]
                        @endif
                    </h6>
                </td>
                <td class="align-middle">
                    <p class="text-muted small">{{ $panier->total_qte }} x {{ number_format($panier->price, 2, ',', ' ') }}&nbsp;€</p>
                </td>
                <td class="align-middle">
                    <p><b>{{ number_format($panier->price*$panier->total_qte, 2, ',', ' ') }}&nbsp;€</b></p>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    

                <table width="100%" style="border-radius: 20px">
                                <tbody><tr><td class="right d-flex justify-content-end p-3"><strong><span style="background-color: yellow ; font-weight: bold;font-size: 16px;">
                                Prix Total : {{ number_format($total, 2, ',', ' ') }} € TTC</span></strong></td>
                </tr>
                </tbody></table>
                <hr>
                
                <div class="row">
                    <div class="col-lg-10">
                    </div>
                    <div class="col-lg-2" style="text-align: right;">
                            <a href="{{ route('Vider_panier',auth()->user()->user_id) }}"  class="btn btn-danger m-2 col-9"  >Vider le panier</a>
                            <a href="{{ route('payer_article') }}"  class="btn btn-success m-2 col-9"  >Payer</a>
                    </div>
                </div>
                @else
            <div class=" alert alert-info " role="alert">
                Votre panier est vide !
            </div></div>
        @endif

    </div>
</div>
</main>
@endsection