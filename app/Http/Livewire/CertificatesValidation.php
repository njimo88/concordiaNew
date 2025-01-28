<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MedicalCertificates;
use Carbon\Carbon;


class CertificatesValidation extends Component
{
    public $certificatesToValidate = [];
    public $emissionDates = [];

    public function mount()
    {
        $this->certificatesToValidate = MedicalCertificates::where('validated', 0)->get()->keyBy('id');

        // Crée une clé pour chaque certificat avec son expiration_date
        foreach ($this->certificatesToValidate as $certificate) {
            $this->emissionDates[$certificate->id] = $certificate->emission_date;
        }
    }

    // public function updateExpiration($certificateId)
    // {
    //     // Récupère la nouvelle date d'expiration du tableau de dates
    //     $newExpirationDate = $this->emissionDates[$certificateId];

    //     // Vérifie que la nouvelle date d'expiration est supérieure ou égale à aujourd'hui
    //     $today = Carbon::today(); // Récupère la date d'aujourd'hui

    //     if (Carbon::parse($newExpirationDate)->lt($today)) {
    //         // Si la date d'expiration est inférieure à aujourd'hui, renvoie un message d'erreur
    //         session()->flash('error', 'La date d\'expiration doit être supérieure ou égale à aujourd\'hui.');
    //         return;
    //     }

    //     // Mise à jour de la date d'expiration
    //     $certificate = MedicalCertificates::find($certificateId);
    //     $certificate->expiration_date = $newExpirationDate;
    //     $certificate->validated = 1;
    //     $certificate->save();

    //     // Rafraîchit la liste des certificats à valider
    //     $this->certificatesToValidate = MedicalCertificates::where('validated', 0)
    //         ->get()
    //         ->keyBy('id');

    //     session()->flash('message', 'Date d\'expiration mise à jour et certificat passé en "valide"');
    // }

    public function validateCertificate($certificateId)
    {
        // Récupère la nouvelle date d'expiration du tableau de dates
        $newEmissionDate = $this->emissionDates[$certificateId];

        if ($newEmissionDate == "" || $newEmissionDate == null) {
            session()->flash('error', 'La date d\'emission du certicat ne peut pas être nulle.');
            return;
        }

        // Marque le certificat comme validé
        $certificate = MedicalCertificates::find($certificateId);
        $certificate->validated = 1;
        $certificate->emission_date = $newEmissionDate;
        $certificate->save();

        // Rafraîchit la liste des certificats à valider
        $this->certificatesToValidate = MedicalCertificates::where('validated', 0)
            ->get()
            ->keyBy('id');

        session()->flash('message', 'Certificat validé');
    }

    public function render()
    {
        return view('livewire.certificates-validation');
    }
}
