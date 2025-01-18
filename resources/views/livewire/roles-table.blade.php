<div>
    <!-- Afficher le message de succès -->
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <!-- Afficher le message d'erreur -->
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1>Gestion des rôles</h1>
    <hr>
    <div class="row">
        <div class="col-md-4">
        <button type="button" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#newRoleModal">Créer un nouveau type d'utilisateurs</button>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
        <button type="button" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#confirmationModal">Supprimer un rôle</button>
    </div>

    <div class="col-md-12">
    <br>

    <table class="table table-hover">
        <thead style="color:black, background-color:black">
            <tr>
                <th scope="col">Voir</th>
                @foreach (array_keys(reset($roles)) as $field)
                    @if(!in_array($field, ['created_at', 'updated_at']))
                        <th scope="col">{{ $field }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $roleId => $role)
            <tr>
                <!-- Colonne pour le bouton "Voir les Users" -->
                <td class="input-container" style="text-align: center;">
                    <button 
                        class="btn btn-info" 
                        data-bs-toggle="modal" 
                        data-bs-target="#usersModal{{ $roleId }}"
                        class="bouton-voir"
                    >
                        <i class="fas fa-eye"></i> 
                    </button>
                </td>
                @foreach($role as $field => $value)
                    @if(!in_array($field, ['created_at', 'updated_at']))
                        <td class="input-container" style="text-align: center;">
                            @if($field == "id")
                                <input 
                                    type="number" 
                                    disabled="true"
                                    value={{ $role['id'] }}
                                    class="form-control small-input"
                                    style="display:block; margin:0 auto;"
                                >
                            @elseif($field !== "name")
                                <input 
                                    type="number" 
                                    min="0" 
                                    max="1" 
                                    wire:model.debounce.500ms="roles.{{ $roleId }}.{{ $field }}" 
                                    class="form-control small-input @if($roles[$roleId][$field] === '') is-invalid @endif"
                                    style="display:block; margin:0 auto;"
                                >
                                @if($roles[$roleId][$field] === '')
                                    <span class="text-danger">Ce champ ne peut pas être vide.</span>
                                @endif
                            @else
                                <input 
                                    type="text" 
                                    wire:model.debounce.500ms="roles.{{ $roleId }}.{{ $field }}" 
                                    class="form-control myinput"
                                    style="display:block; margin:0 auto;"
                                >
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
            <div class="modal fade" id="usersModal{{ $roleId }}" tabindex="-1" aria-labelledby="usersModalLabel{{ $roleId }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="usersModalLabel{{ $roleId }}">
                                Utilisateurs du rôle <strong>{{ $role['name'] }}</strong> 
                                (@php
                                    $count = \App\Models\User::where('role', $roleId)->count();
                                    echo $count;
                                @endphp utilisateurs)
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if($count > 0)
                                <ul>
                                    @foreach(\App\Models\User::where('role', $roleId)->get() as $user)
                                        <li>{{ $user->name }} {{ $user->lastname }} ({{ $user->email }})</li>
                                    @endforeach
                                </ul>
                            @else
                                <h4>Aucun utilisateur de ce rôle</h4>
                            @endif
                        </div>                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>            
        @endforeach        
        </tbody>
    </table>

    <!-- Modal ajout rôle -->
    <div class="modal fade" id="newRoleModal" tabindex="-1" aria-labelledby="newRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newRoleModalLabel">Créer un nouveau rôle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="container">
                        <!-- Formulaire Livewire -->
                        <form wire:submit.prevent="createRole">
                            <div class="input-group mb-3">
                                <input 
                                    type="number" 
                                    wire:model.defer="roleIdToCreate" 
                                    placeholder="ID" 
                                    class="form-control" 
                                    aria-label="ID" 
                                    aria-describedby="button-addon2">
                                <input 
                                    type="text" 
                                    wire:model.defer="roleNameToCreate" 
                                    placeholder="Nom du rôle" 
                                    class="form-control" 
                                    aria-label="Nom du rôle" 
                                    aria-describedby="button-addon2">
                                <button type="submit" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="margin: 15px 0">Valider</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Modal de suppression de rôle -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation de suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="container">
                        <!-- Formulaire Livewire -->
                        <form wire:submit.prevent="deleteRole">
                            <div class="mb-3">
                                <label for="roleSelect" class="form-label">Choisissez le rôle à supprimer :</label>
                                <select 
                                    id="roleSelect" 
                                    class="form-select" 
                                    aria-label="Choisissez un rôle"
                                    name="roleToDeleteId"
                                    wire:model.defer="roleToDeleteId">
                                    <option value="">-- Sélectionnez un rôle --</option>
                                    @foreach($roles as $roleId => $role)
                                        <option value="{{ $roleId }}">{{ $role['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger" data-bs-dismiss="modal" style="margin: 15px 0">Valider</button>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div>
    </div>    
</div>

<style>
    table {
        border-collapse: collapse;
        border-spacing: 0;
        display:block;
        overflow:auto;
        height:600px;
        width:100%;
        border: 1px solid #ddd;
    }
    
    .small-input {
        width: 50px;
    }
    
    .myinput {  
        width: 170px;
        border: none;
        border-bottom: 1px solid #181824;
        border-radius: 0%;
        box-sizing: border-box;
        font-weight: 300;
        margin: 15px 0;
        padding: 8px;
        transition: 300ms;
        font-size: 14px;
    }

    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        -moz-appearance: textfield;
    }

    .del-button{
        width: 100%;
    }

    .bouton-voir {
        height: 100%
    }
</style>