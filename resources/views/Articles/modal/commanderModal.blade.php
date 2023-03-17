<div class="modal-body p-5">
    <h4><span style="font-weight:bold;color:blue">{{ $shop->title }}</span></h4>
    <img style="max-height: 200px" src="{{ $shop->image }}" alt="">
    <h5>Ce produit a été ajouté a votre Panier !</h5>
    <hr>
    <h5>Avez-vous terminé vos achats ?</h5>
    <div class="btn-group" role="group">
        <form action="{{ route("commander_article", $shop->id_shop_article) }}" method="post">
            @csrf
            @method('PUT')
            <input type="hidden" name="qte" id="qte" value="{{ $qte }}">
            <input type="hidden" name="declinaison" id="declinaison" value="{{ $declinaison }}">
            <input type="hidden" name="selected_user_id" id="selected_user_id" value="{{ $user_id }}">
            <button type="submit" class="mx-1 btn btn-success">Continuer mes achats</button>
        </form>
        <form action="{{ route("Passer_au_paiement", $shop->id_shop_article) }}" method="post">
            @csrf
            @method('PUT')
            <input type="hidden" name="qte" id="qte" value="{{ $qte }}">
            <input type="hidden" name="declinaison" id="declinaison" value="{{ $declinaison }}">
            <input type="hidden" name="selected_user_id" id="selected_user_id" value="{{ $user_id }}">
            <button type="submit" class="mx-1 btn btn-warning">Passer au paiement</button>
        </form>
    </div>
  </div>
