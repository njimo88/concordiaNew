<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\Role;
use App\Models\User;
use PhpOption\None;

class RolesTable extends Component
{
    public $roles;
    public $roleToDeleteId;
    public $roleIdToCreate;
    public $roleNameToCreate;

    // Charger les données des rôles au chargement du composant
    public function mount()
    {
        // Charger les rôles depuis la base de données
        $this->roles = Role::all()->keyBy('id')->toArray();
    }

    // Afficher la vue du composant
    public function render()
    {
        return view('livewire.roles-table');
    }

    public function updated($propertyName)
    {
        if (str_contains($propertyName, 'roles.')) {
            list($dummy, $roleId, $field) = explode('.', $propertyName);
            $value = $this->roles[$roleId][$field];

            if ($field !== 'name' && ($value < 0 || $value > 1)) {
                // Remettre la valeur précédente si elle est hors limites
                session()->flash('error', 'La valeur doit être comprise entre 0 et 1.');
                $this->roles[$roleId][$field] = ''; // Ou une valeur par défaut
                return;
            }

            if ($value === '' || $value === null) {
                Log::info("Modification ignorée pour le champ vide : {$propertyName}");
            } else {
                $this->updateRole($roleId, $field, $value);
            }
        }
    }

    // Mettre à jour un rôle
    public function updateRole($roleId, $field, $value)
    {
        // Ne pas mettre à jour dans la base de données si la valeur est vide
        if ($value === '' || $value === null) {
            Log::info("Valeur vide détectée pour le rôle {$roleId}, champ {$field}. Base de données ignorée.");
            return; // Ne pas continuer
        }

        // Log pour débogage
        Log::info("Mise à jour du rôle: {$roleId}, champ: {$field}, valeur: {$value}");

        // Mise à jour dans la base de données
        Role::where('id', $roleId)->update([$field => $value]);

        // Recharger le rôle associé dans l'état local pour refléter les changements
        $this->roles[$roleId][$field] = $value;

        Log::info("Mise à jour terminée pour le rôle: {$roleId}");
    }

    // Créer un rôle
    public function createRole()
    {
        // Créer le rôle dans la base de données
        $role = new Role;

        $role->id = $this->roleIdToCreate;
        $role->name = $this->roleNameToCreate;

        $role->save();

        // Recharger les rôles après création
        $this->roles = Role::all()->keyBy('id')->toArray();

        // Réinitialiser les champs du formulaire
        $this->roleIdToCreate = '';
        $this->roleNameToCreate = '';

        // Afficher un message de succès après création
        session()->flash('message', 'Rôle créé avec succès!');
    }

    // Supprimer un rôle
    public function deleteRole()
    {
        // Trouver le rôle correspondant
        $role = Role::find($this->roleToDeleteId);

        if (!$role) {
            session()->flash('error', 'Veuillez spécifier un rôle valide.');
            return;
        }

        // Vérifier s'il existe des utilisateurs associés au rôle
        if ($role->users()->count() > 0) {
            session()->flash('error', 'Impossible de supprimer ce rôle, des utilisateurs y sont associés.');
        } else {
            $role->delete();

            // Recharger les rôles après suppression
            $this->roles = Role::all()->keyBy('id')->toArray();

            session()->flash('message', 'Rôle supprimé avec succès!');
        }

        // Réinitialiser les données après suppression
        $this->roleToDeleteId = null;
    }
}
