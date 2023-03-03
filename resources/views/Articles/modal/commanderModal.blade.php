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
            <button type="submit" class="mx-1 btn btn-success">Continuer mes achats</button>
        </form>
            <button type="button" class="mx-1 btn btn-warning">Passer au paiement</button>
    </div>
  </div>
