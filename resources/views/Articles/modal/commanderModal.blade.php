<style>
    .btn:hover {
        background-color: #63c3d1 !important;
    }
</style>

<div class="modal-body p-5">
    <h4><span style="font-weight:bold;color:blue">{{ $shop->title }}</span></h4>
    <img class="mb-3" style="max-height: 200px" src="{{ $shop->image }}" alt="">
    <h5>Ce produit a été ajouté a votre Panier !</h5>
    <hr>
    <h5>Avez-vous terminé vos achats ?</h5>
    @if($needMember != 0)
<div class="form-check my-3">
    <input class="form-check-input" type="checkbox" id="needMemberCheck">
    <label style="text-align:start" class="form-check-label" for="needMemberCheck">
       <strong>Important : Adhésion à l'association</strong> 
Vous vous apprêtez à devenir adhérent. Il vous faut pour cela confirmer en cochant la case ci-dessous l'acceptation de nos statuts et de notre règlement intérieur (disponibles en ligne sur notre Site Internet)
    </label>
</div>


@endif

    <div class="btn-group" role="group">
        <form action="{{ route("commander_article", $shop->id_shop_article) }}" method="post">
            @csrf
            @method('PUT')
            <input type="hidden" name="qte" id="qte" value="{{ $qte }}">
            <input type="hidden" name="declinaison" id="declinaison" value="{{ $declinaison }}">
            <input type="hidden" name="selected_user_id" id="selected_user_id" value="{{ $user_id }}">
            @if($needMember != 0)
            <button  type="submit" class="mx-1 btn " id="continueButton" {{$needMember ? 'disabled' : ''}}>Continuer mes achats</button>
            @else
            <button  type="submit" class="mx-1 btn ">Continuer mes achats</button>
            @endif
        </form>
        <form action="{{ route("Passer_au_paiement", $shop->id_shop_article) }}" method="post">
            @csrf
            @method('PUT')
            <input type="hidden" name="qte" id="qte" value="{{ $qte }}">
            <input type="hidden" name="declinaison" id="declinaison" value="{{ $declinaison }}">
            <input type="hidden" name="selected_user_id" id="selected_user_id" value="{{ $user_id }}">
            @if($needMember != 0)
            <button  type="submit" class="mx-1 btn btn-rouge" id="paymentButton" {{$needMember ? 'disabled' : ''}}>Passer au paiement</button>
            @else
            <button  type="submit" class="mx-1 btn btn-rouge">Passer au paiement</button>
            @endif
        </form>
    </div>
  </div>
  <script>
    document.getElementById('needMemberCheck').addEventListener('change', function() {
        document.getElementById('continueButton').disabled = !this.checked;
        document.getElementById('paymentButton').disabled = !this.checked;
    });
</script>
