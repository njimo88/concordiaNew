@extends('layouts.app')

@section('content')
<main style="background-image: url('{{asset("/assets/images/background.png")}}');min-height:100vh;" id="main" class="main">

<div class="row" id="corps-pricipal" style="margin: 0 auto; padding:16px; justify-content:center">
    <div class="col-lg-10 border border-dark" style="background-color: white; padding:16px; border-radius:10px">
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
        <div class="col-lg-6" style="text-align: right">
                                                                <p><a href="#" data-toggle="modal" data-target="#genDevisModal" style="background-color: #000dff" class="btn btn-primary">Générer un devis</a></p>
                                    
        </div>
    </div>
    @foreach ($paniers as $paniers)
    @if (!empty($paniers))
        <div style="width: 100%;max-height: 500px; overflow-y: scroll; border-top: solid 1px;" class="border border-dark mb-2">
            <table width="100%">
                <tbody><tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th width="30%" style="text-align: right"></th>
                    <th width="20%" style="text-align: right"></th>
                    <th width="23%" style="text-align: right"></th>
                </tr>
                                                                                                                    
<input type="hidden" name="1[rowid]" value="05b0d08555ab87e2c1da0bbb1012f23d">
                                        <tr style="background-color: #eee">
                        <td width="20%">
                            <i class="left"><b>&nbsp;&nbsp;&nbsp;{{ $users->lastname }} {{ $users->name }}</b></i>                             <br>
                            <br>
                        </td>
                        <!-- <td width="5%" style="text-align: center">
                            <p style="font-size: 10px;">1</p>
                        </td>-->
                        <td width="10%" style="text-align:center"><img width="70px" src="{{ $paniers->image }}"><p style="font-size: 10px;">{{ $paniers->reff }}</p>
                        </td>
                        <td width="30%" style="text-align: center">
                            <p style="font-size: 15px; margin-top: 20px">{{ $paniers->title }}</p>
                        </td>
                        <td style="text-align:center">
                            <p style="font-size: 12px; margin-top: 20px">{{ $paniers->qte }} x <br>{{ $paniers->price }}&nbsp;€</p>
                        </td>
                        <td style="text-align:right">
                            <p style="font-size: 12px; margin-top: 20px">
                                <b>{{ $paniers->price }}&nbsp;€&nbsp;&nbsp;&nbsp;</b>
                            </p>
                        </td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </tr>
                                                </tbody></table>
        </div>
        @else
            <div class="alert alert-info" role="alert">
                Votre panier est vide !
            </div>
        @endif
        @endforeach
                <table width="100%" style="border-radius: 20px">
                                <tbody><tr><td class="right "><strong><span style="font-weight: bold;  margin-left: 10px; font-size: 16px;">
                                Prix Total : ?? € TTC</span></strong></td>
                </tr>
                </tbody></table>
                <hr>
                
                <div class="row">
                    <div class="col-lg-10">
                    </div>
                    <div class="col-lg-2" style="text-align: right;">
                        <input  type="submit" class="btn btn-danger m-2 col-9"  value="Vider le panier">
                        <input  class="btn btn-success m-2 col-9"  type="submit  " value="Payez">
                    </div>
                </div>

    </div>
</div>
</main>
@endsection