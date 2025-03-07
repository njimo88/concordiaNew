@extends('layouts.template')

@section('content')
<main class="main" id="main">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>{{ __('Modifier une réduction') }}</h3></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('update.reduction',$shopReduction->id_shop_reduction) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('Code') }}</label>

                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $shopReduction->code) }}" required autocomplete="code" autofocus>

                                @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" required>{{ old('description', $shopReduction->description) }}</textarea>

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="value" class="col-md-4 col-form-label text-md-right">{{ __('Valeur (en €)') }}</label>

                            <div class="col-md-6">
                                <input id="value" type="number" min="0" step="0.01" class="form-control @error('value') is-invalid @enderror" name="value" value="{{ old('value', $shopReduction->value) }}" required>

                                @error('value')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="percentage" class="col-md-4 col-form-label text-md-right">{{ __('Pourcentage') }}</label>

                            <div class="col-md-6">
                                <input id="percentage" type="number" min="0" max="100" step="0.01" class="form-control @error('percentage') is-invalid @enderror" name="percentage" value="{{ old('percentage', $shopReduction->percentage) }}" required>

                                @error('percentage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="startvalidity" class="col-md-4 col-form-label text-md-right">{{ __('Date de début de validité') }}</label>

                            <div class="col-md-6">
                                <input id="startvalidity" type="date" class="form-control @error('startvalidity') is-invalid @enderror" name="startvalidity" value="{{ old('startvalidity', $shopReduction->startvalidity) }}" required autofocus>

                                @error('startvalidity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="endvalidity" class="col-md-4 col-form-label text-md-right">{{ __('Date de fin de validité') }}</label>

                            <div class="col-md-6">
                                <input id="endvalidity" type="date" class="form-control @error('endvalidity') is-invalid @enderror" name="endvalidity" value="{{ old('endvalidity', $shopReduction->endvalidity) }}" required autofocus>

                                @error('endvalidity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="usable" class="col-md-4 col-form-label text-md-right">{{ __('Nombre d\'utilisations limité') }}</label>

                            <div class="col-md-6">
                                <input id="usable" type="number" min="0" class="form-control @error('usable') is-invalid @enderror" name="usable" value="{{ old('usable', $shopReduction->usable) }}" required>

                                @error('usable')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="automatic" class="col-md-4 col-form-label text-md-right">{{ __('Automatique') }}</label>
                      
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="automatic" name="automatic" value="1" {{ old('automatic', $shopReduction->automatic) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="automatic">{{ __('Oui') }}</label>
                                </div>
                            </div>
                        </div>
                      
                        <div class="form-group row">
                            <label for="state" class="col-md-4 col-form-label text-md-right">{{ __('Activation') }}</label>
                      
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="state" name="state" value="1" {{ old('state', $shopReduction->state) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="state">{{ __('Oui') }}</label>
                                </div>
                            </div>
                        </div>
                          

                        <div class="form-group row mt-4 mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Enregistrer') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <form class="mt-5 col-md-4" action="{{ route('update_liaisons') }}" method="POST">
            @csrf
            <input type="hidden" name="shop_reduction_id" value="{{ $shopReduction->id_shop_reduction }}" >
                <div class="border card-deck" style="height: 400px; overflow-y: scroll;">
                    <div id="list-example" class="list-group">
                        <h5 class="p-2">Articles liés</h5>
                        <ul class="list-group">
                            @foreach ($checkedArticles as $shopArticle)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="shop_article[]" value="{{ $shopArticle->id_shop_article }}" checked> {{ $shopArticle->title }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                        <hr>
                        <h5 class="p-2">Articles non liés</h5>
                        <ul class="list-group">
                            @foreach ($uncheckedArticles as $shopArticle)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="shop_article[]" value="{{ $shopArticle->id_shop_article }}"> {{ $shopArticle->title }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            <button type="submit" class="btn btn-primary mt-4">Modifier</button>
        </form>

        <div class="mt-4 card">
            <div class="card-header bg-primary text-white text-center">
                <h4 style="color: black !important">Gestion des utilisateurs et des produits pour la réduction</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="product-dropdown">Sélectionnez un produit</label>
                    <select id="product-dropdown" class="form-control" data-reduction-id="{{ $shopReduction->id_shop_reduction }}">
                        <option value="">Sélectionnez un produit</option>
                        @foreach ($shopArticles as $shopArticle)
                            <option value="{{ $shopArticle->id_shop_article }}">{{ $shopArticle->title }}</option>
                        @endforeach
                    </select>
                </div>
        
                <div class="mt-4 card">
                    <div class="card-header">
                        Utilisateurs liés au produit sélectionné
                    </div>
                    <ul class="list-group list-group-flush" id="linked-users-list"></ul>
                </div>
        
                <div class="mt-4 form-group">
                    <label for="user-search">Rechercher et lier un utilisateur au produit avec cette réduction</label>
                    <input type="text" id="user-search" class="form-control" placeholder="Rechercher un utilisateur">
                    <div id="user-search-results" class="border mt-2 p-3" style="max-height: 200px; overflow-y: scroll; display: none;"></div>
                </div>

                <!-- ajout du champ de saisie du usage_max -->
                
                <div class="form-group row">
                    <label for="usage_max" class="col-md-4 col-form-label text-md-right">{{ __('Nombre d\'utilisations maximum') }}</label>
                    <div class="col-md-6">
                        <input id="usage_max" type="number" min="0" class="form-control @error('usage_max') is-invalid @enderror" name="usage_max" d value="1" required>

                        @error('usage_max')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    
                    </div>
                </div>

                <div class="mt-4">
                    <button id="save-changes" class="btn btn-success">Sauvegarder</button>
                </div>
        
            </div>
        </div>

        
        
        
        <script>
            
            let checkedShopArticles = {!! $checkedArticles !!};
            let uncheckedShopArticles = {!! $uncheckedArticles !!};
            let shopArticles = [];
            checkedShopArticles.forEach((shopArticle) => {
                shopArticles.push(shopArticle.id_shop_article);
            });
        
            $('input[name="shop_article[]"]').change(function() {
                let id = parseInt($(this).val());
                let index = shopArticles.indexOf(id);
        
                if ($(this).is(':checked')) {
                    if (index === -1) {
                        shopArticles.push(id);
                    }
                } else {
                    if (index !== -1) {
                        shopArticles.splice(index, 1);
                    }
                }
            });
        
            $('form').submit(function(event) {
                $('input[name="shop_article[]"]').remove();
                shopArticles.forEach((id) => {
                    $(this).append('<input type="hidden" name="shop_article[]" value="' + id + '">');
                });
            });


        </script>
        
        
        
    </div>
    
</div>





</main>

@endsection 

