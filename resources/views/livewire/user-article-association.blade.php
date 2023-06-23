<div class="container my-5">
    <h1 class="text-center mb-5">Association d'utilisateurs et d'articles</h1>

    <!-- Sélectionner un article -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-dark">Sélectionner un article</div>
                <div class="card-body">
                    <select wire:model="selectedArticle" class="form-control">
                        <option value="">Choisir...</option>
                        @foreach($this->articles as $article)
                            <option value="{{ $article->id_shop_article }}">{{ $article->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Rechercher des utilisateurs et Tous les utilisateurs -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-dark">Rechercher des utilisateurs</div>
                <div class="card-body">
                    <input wire:model="search" type="text" class="form-control" id="search">
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-primary text-dark">Tous les utilisateurs</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($this->users as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $user->lastname }} {{ $user->name }}
                                <button wire:click="addUser({{ $user->user_id }})" class="btn btn-success btn-sm">Ajouter</button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Utilisateurs sélectionnés -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-dark">Utilisateurs sélectionnés</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($this->selectedUsers as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $user->lastname }} {{ $user->name }}
                                <button wire:click="removeUser({{ $user->user_id }})" class="btn btn-danger btn-sm">Retirer</button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        window.livewire.on('userAlreadySelected', function () {
            alert('L\'utilisateur est déjà sélectionné.');
        });
    });
</script>
