<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use App\Http\Requests\AddUserform;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\shop_article_1;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Models\statistiques_visites;
use App\Models\MedicalCertificates;

require_once(app_path() . '/fonction.php');





class n_AdminController extends Controller
{



    public function index()

    {

        $determinSecValue = SystemSetting::where('name', 'determin_sec')->first()->value;
        $annee_actu = saison_active(); // recupere l'annee de la saison active
        // recupere l'annee du debut des activites de l'association (au depart 2015)
        $date_de_rentree = DB::table('system')->where('name', 'date_de_rentree')->first('value');
        $annee_creation = $date_de_rentree->value;
        // la difference entre l'annee de la saison actuelle et celle de la saison encore
        $diff_year = $annee_actu - $annee_creation;

        // si l'ecart entre les deux annees est superieures a 10 alors on fait une
        // requete qui va incrementer d'une unite l'annee de creation pour qu'on reste toujours sur 10 ans d'ecart dans la BD 
        if ($diff_year > 10) {
            DB::table('system')->where('name', 'date_de_rentree')->increment('value');
        }

        $visitorCount = statistiques_visites::where('page', '/')
            ->where('annee', now()->year)
            ->value('nbre_visitors');

        if (!$visitorCount) {
            $nbre_visit = 0;
        } else {
            $nbre_visit = $visitorCount;
        }

        $get_stat_pages = statistiques_visites::where('page', '!=', '/')->where('annee', now()->year)->orderBy('nbre_visitors', 'desc')
            ->limit(10)->get();

        $saison_actu = saison_active();

        $shop_article_lesson =  shop_article_1::select('shop_article_1.teacher', 'shop_article.title', 'shop_article_1.id_shop_article', 'shop_article.stock_actuel', 'shop_article.stock_ini')
            ->join('shop_article', 'shop_article.id_shop_article', '=', 'shop_article_1.id_shop_article')->where('saison', $saison_actu)->get();

        return view('admin.index', compact('annee_creation', 'annee_actu', 'nbre_visit', 'get_stat_pages', 'shop_article_lesson', 'determinSecValue'))->with('user', auth()->user());
    }

    public function PortOuvindex()
    {
        return view('admin.portOuv')->with('user', auth()->user());
    }

    public function getUsers(Request $request)
    {
        if ($request->ajax()) {

            $cacheKey = 'users:data' . ($request->has('search') ? ':' . $request->get('search')['value'] : '');

            $data = Cache::get($cacheKey);
            $user = Auth::user();
            if (!$data) {
                $query = User::select('user_id', 'email', 'username', 'phone', DB::raw("CONCAT(lastname, ' ', name) as full_name"), 'birthdate');

                if ($request->has('search') && $request->get('search')['value']) {
                    $query->where(function ($query) use ($request) {
                        $query->where('name', 'like', "%" . $request->get('search')['value'] . "%")
                            ->orWhere('email', 'like', "%" . $request->get('search')['value'] . "%")
                            ->orWhere('username', 'like', "%" . $request->get('search')['value'] . "%")
                            ->orWhere('phone', 'like', "%" . $request->get('search')['value'] . "%");
                    });
                }

                $data = $query->get();
                Cache::put($cacheKey, $data, 60);
            }

            return datatables()->of($data)
                ->editColumn('birthdate', function ($user) {
                    return date("Y-m-d", strtotime($user->birthdate));
                })
                ->addColumn('action', function ($user) {
                    return '<span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Réinitialiser le mot de passe">' .
                        '<img data-user-id="' . $user->user_id . '" class="Resetpass editbtn2 mx-2" src="' . asset('assets/images/rotate.png') . '">' .
                        '</span>' .
                        '<a data-user-id="' . $user->user_id . '" type="button" class="editusermodal user-link a text-black" href="#">' .
                        '<i class="fas fa-edit"></i>' . // replace with your own icon
                        '</a>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.portOuv');
    }





    public function members(Request $request)
    {
        $roles = Role::all();
        return view('admin.members', compact('roles'));
    }

    public function search(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && $request->search !== '') {
            $query->where('username', 'like', '%' . $request->search . '%')
                ->orWhere('name', 'like', '%' . $request->search . '%')
                ->orWhere('lastname', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->get();

        return view('admin.members_search_results', compact('users'));
    }

    public function search_pagination(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && $request->search !== '') {
            $query->where('username', 'like', '%' . $request->search . '%')
                ->orWhere('name', 'like', '%' . $request->search . '%')
                ->orWhere('lastname', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->paginate(50);
        }

        $users = $query->get();

        return view('admin.members_search_results', compact('users'));
    }




    public function Editer($user_id)
    {
        $famille = User::where('family_id', $user_id)->get();
        return view('admin.modals.familleMembers', compact('famille'))->with('user', auth()->user());
    }

    public function editUsermodal($user_id)
    {
        $n_users = User::find($user_id);
        $roles = Role::all();
        return view('admin.modals.editUser', compact('n_users', 'roles'))->with('user', auth()->user());
    }

    public function message_general(Request $request)
    {
        $id = $request->input('setting_id');
        $value = $request->input('setting_value');

        $systemSetting = SystemSetting::findOrFail($id);
        $systemSetting->value = $value;
        $systemSetting->save();

        return response()->json(['message' => 'Valeur mise à jour avec succès']);
    }


    public function addUser(AddUserform $request)
    {
        $validateData = $request->validated();
        $addUser = new User();
        $biggest_family_id = User::max('family_id');
        $addUser->username = $validateData['email'];
        $addUser->name = $validateData['name'];
        $addUser->lastname = $validateData['lastname'];
        $addUser->email = $validateData['email'];
        $addUser->password = bcrypt($validateData['password']);
        $addUser->phone = $validateData['phone'];
        $addUser->profession = $validateData['profession'];
        $addUser->gender = $validateData['gender'];
        $addUser->birthdate = $validateData['birthdate'];
        $addUser->nationality = $validateData['nationality'];
        $addUser->address = $validateData['address'];
        $addUser->zip = $validateData['zip'];
        $addUser->city = $validateData['city'];
        $addUser->country = $validateData['country'];
        $addUser->family_id = $biggest_family_id + 1;
        $addUser->role = $validateData['role'];
        $addUser->save();
        return redirect()->route('utilisateurs.members')->with('success', 'Le User a été ajouté avec succès');
    }

    public function mdpUniverselmodal($user_id)
    {
        $n_users = User::find($user_id);

        return view('admin.modals.mdpUniversel', compact('n_users'))->with('user', auth()->user());
    }

    public function editUser(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $validatedData = $request->validate([
            'username' => 'nullable|string|max:255',
            'name' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255'],
            'lastname' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
            'profession' => 'string|max:191',
            'birthdate' => 'required|date|before:today',
            'address' => 'required',
            'zip' => 'required|numeric',
            'city' => 'required',
            'nationality' => 'required',
            'licenceFFGYM' => ['nullable', 'regex:/^\d{5}\.\d{3}\.\d{5}$/'],
            'crt' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

        ], $messages = [
            'username.required' => "Le champ nom d'utilisateur est requis.",
            'username.max' => "Le nom d'utilisateur ne doit pas dépasser 255 caractères.",
            'name.required' => "Le champ nom est requis.",
            'name.alpha' => "Le nom doit être une chaîne de caractères.",
            'lastname.required' => "Le champ prénom est requis.",
            'lastname.alpha' => "Le prénom doit être une chaîne de caractères.",
            'email.required' => 'Le champ :attribute est requis.',
            'email' => "Le format de l'adresse e-mail est invalide.",
            'email.unique' => "L'adresse e-mail est déjà utilisée.",
            'password.required' => "Le champ mot de passe est requis.",
            'password.min' => "Le mot de passe doit contenir au moins 8 caractères.",
            'password.confirmed' => "La confirmation du mot de passe ne correspond pas.",
            'phone.required' => "Le champ numéro de téléphone  est requis.",
            'phone.regex' => "Le format du numéro de téléphone est invalide.",
            'gender.required' => "Le champ sexe est requis.",
            'birthdate.required' => 'La date de naissance est requise',
            'birthdate.date' => 'Format de date non valide',
            'birthdate.before' => 'La date de naissance doit être antérieure à aujourd\'hui',
            'profession.alpha' => "La profession doit être une chaîne de caractères.",
            'address.required' => "Le champ address est requis.",
            'zip.required' => "Le champ code postal est requis.",
            'zip.regex' => "Le code postal doit être au format 12345 ou 12345-1234.",
            'city.required' => "Le champ ville est requis.",
            'city.alpha' => "La ville doit être une chaîne de caractères.",
            'country.required' => "Le champ pays est requis.",
            'licenceFFGYM.required' => "Le champ licence FFGYM est requis.",
            'licenceFFGYM.regex' => "Le format de la licence FFGYM est invalide.",
            'crt.image' => "Le certificat médical doit être une image.",
            'crt.mimes' => "Le certificat médical doit être un fichier de type jpeg, png, jpg ou gif.",
            'crt.max' => "Le certificat médical ne doit pas dépasser 2 Mo.",
        ]);

        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request->password)]);
        } else {
            unset($request['password']);
        }


        // Si un fichier est téléchargé pour 'crt', valider la date d'expiration
        if ($request->hasFile('crt') || ($user->medicalCertificate && $user->medicalCertificate->file_path !== "")) {
            // Si un certificat est téléchargé, la date d'expiration est obligatoire
            $request->validate([
                'crt_expiration' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            ], [
                'crt_expiration.required' => 'La date d\'expiration du certificat médical est requise quand un certificat est saisi.',
                'crt_expiration.date' => 'La date d\'expiration du certificat médical doit être une date valide.',
                'crt_expiration.after_or_equal' => 'La date d\'expiration du certificat médical doit être après ou aujourd\'hui.',
            ]);
        }

        // Définir la valeur par défaut pour la date d'expiration (date actuelle)
        $expirationDate = now();

        if ($request->input('crt_expiration') !== '') {
            $expirationDate = $request->input('crt_expiration');
        }

        // Si un certificat médical est téléchargé
        if ($request->hasFile('crt')) {
            // Gérer l'upload du fichier
            $file = $request->file('crt');

            // Obtenir l'extension du fichier
            $extension = $file->getClientOriginalExtension();

            // Générer un nom unique pour le fichier
            $fileName = "CertifMedic$user_id.$extension";

            // Vérifier si un ancien certificat existe pour cet utilisateur
            if ($user->medicalCertificate && file_exists(public_path($user->medicalCertificate->file_path))) {
                // Supprimer l'ancien fichier
                unlink(public_path($user->medicalCertificate->file_path));
            }

            // Déplacer le fichier vers le dossier public/images/certificatsMedicaux
            $file->move(public_path('/uploads/certificatsMedicaux'), $fileName);

            // Le chemin du fichier dans le dossier public
            $filePath = 'uploads/CertificatsMedicaux/' . $fileName;

            // Mettre à jour ou créer un certificat médical
            MedicalCertificates::updateOrCreate(
                ['user_id' => $user->user_id], // Trouver le certificat pour cet utilisateur (s'il existe)
                [
                    'file_path' => $filePath, // Chemin du fichier
                    'expiration_date' => $expirationDate, // Utiliser la date d'expiration fournie ou celle par défaut
                ]
            );
        } else if ($user->medicalCertificate && $user->medicalCertificate->file_path !== "") {
            MedicalCertificates::updateOrCreate(
                ['user_id' => $user->user_id], // Trouver le certificat pour cet utilisateur (s'il existe)
                [
                    'expiration_date' => $expirationDate, // Utiliser la date d'expiration fournie ou celle par défaut
                ]
            );
        }

        $user->update($request->all());

        return redirect()->back()->with('success', 'Le profil a été modifié avec succès');
    }

    public function editSpecificUser(Request $request, $userId)
    {
        // dd($request->birthdate);
        $request->validate([
            'role' => 'required|int',
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'nullable|string',
            'gender' => 'required|string',
            'email' => 'required|email|max:255',
            'profession' => 'nullable|string|max:191',
            'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
            'crt' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'birthdate' => 'required|date|date_format:Y-m-d|before:today',
            'nationality' => 'required',
            'address' => 'required',
            'city' => 'required',
            'zip' => 'required|numeric',
            'country' => 'required',
            'created_at' => 'required|date|date_format:Y-m-d|before_or_equal:today',
            'licenceFFGYM' => ['nullable', 'regex:/^\d{5}\.\d{3}\.\d{5}$/'],
        ], $messages = [
            'role.required' => 'Le rôle est requis.',
            'name.required' => "Le champ nom est requis.",
            'lastname.required' => "Le champ prénom est requis.",
            'username.string' => "Le nom d'utilisateur doit être une chaîne de caractères.",
            'gender.required' => "Le sexe est requis.",
            'email.required' => 'Le champ email est requis.',
            'email.email' => 'L\'adresse email doit être valide.',
            'profession.string' => "La profession doit être une chaîne de caractères.",
            'phone.required' => "Le champ numéro de téléphone est requis.",
            'phone.regex' => "Le format du numéro de téléphone est invalide. Exemple : 0123456789.",
            'crt.image' => "Le certificat médical doit être une image.",
            'crt.mimes' => "Le certificat médical doit être un fichier de type jpeg, png, jpg ou gif.",
            'crt.max' => "Le certificat médical ne doit pas dépasser 2 Mo.",
            'birthdate.required' => 'La date de naissance est requise.',
            'birthdate.date' => 'Le format de la date de naissance est invalide.',
            'birthdate.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'nationality.required' => "Le champ nationalité est requis.",
            'address.required' => "Le champ adresse est requis.",
            'city.required' => "Le champ ville est requis.",
            'zip.required' => "Le champ code postal est requis.",
            'zip.numeric' => "Le code postal doit être un nombre.",
            'country.required' => "Le pays est requis.",
            'created_at.required' => "La date d'inscription est requise.",
            'licenceFFGYM.regex' => "Le format de la licence FFGYM est invalide. Exemple : 12345.678.12345.",
        ]);

        // Récupérer l'utilisateur
        $user = User::findOrFail($userId);

        // Si un fichier est téléchargé pour 'crt', valider la date d'expiration
        if ($request->hasFile('crt') || ($user->medicalCertificate && $user->medicalCertificate->file_path !== "")) {
            // Si un certificat est téléchargé, la date d'expiration est obligatoire
            $request->validate([
                'crt_expiration' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            ], [
                'crt_expiration.required' => 'La date d\'expiration du certificat médical est requise quand un certificat est saisi.',
                'crt_expiration.date' => 'La date d\'expiration du certificat médical doit être une date valide.',
                'crt_expiration.after_or_equal' => 'La date d\'expiration du certificat médical doit être après ou aujourd\'hui.',
            ]);
        }

        $expirationDate = now();
        // Définir la valeur par défaut pour la date d'expiration (date actuelle)
        if ($request->input('crt_expiration') !== '') {
            $expirationDate = $request->input('crt_expiration');
        }

        // Si un certificat médical est téléchargé
        if ($request->hasFile('crt')) {
            // Gérer l'upload du fichier
            $file = $request->file('crt');

            // Obtenir l'extension du fichier
            $extension = $file->getClientOriginalExtension();

            // Générer un nom unique pour le fichier
            $fileName = "CertifMedic$userId.$extension";

            // Vérifier si un ancien certificat existe pour cet utilisateur
            if ($user->medicalCertificate && file_exists(public_path($user->medicalCertificate->file_path))) {
                // Supprimer l'ancien fichier
                unlink(public_path($user->medicalCertificate->file_path));
            }

            // Déplacer le fichier vers le dossier public/images/certificatsMedicaux
            $file->move(public_path('/uploads/CertificatsMedicaux'), $fileName);

            // Le chemin du fichier dans le dossier public
            $filePath = 'uploads/CertificatsMedicaux/' . $fileName;

            // Mettre à jour ou créer un certificat médical
            MedicalCertificates::updateOrCreate(
                ['user_id' => $user->user_id], // Trouver le certificat pour cet utilisateur (s'il existe)
                [
                    'file_path' => $filePath, // Chemin du fichier
                    'expiration_date' => $expirationDate, // Utiliser la date d'expiration fournie ou celle par défaut
                ]
            );
        } else if ($user->medicalCertificate && $user->medicalCertificate->file_path !== "") {
            MedicalCertificates::updateOrCreate(
                ['user_id' => $user->user_id], // Trouver le certificat pour cet utilisateur (s'il existe)
                [
                    'expiration_date' => $expirationDate, // Utiliser la date d'expiration fournie ou celle par défaut
                ]
            );
        }

        // Mettre à jour les autres informations utilisateur
        $user->update([
            'role' => $request->input('role'),
            'name' => $request->input('name'),
            'lastname' => $request->input('lastname'),
            'username' => $request->input('username'),
            'gender' => $request->input('gender'),
            'email' => $request->input('email'),
            'profession' => $request->input('profession'),
            'phone' => $request->input('phone'),
            'birthdate' => $request->input('birthdate'),
            'nationality' => $request->input('nationality'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'zip' => $request->input('zip'),
            'country' => $request->input('country'),
            'licenceFFGYM' => $request->input('licenceFFGYM'),
            'created_at' => $request->input('created_at'),
        ]);

        return redirect()->route('admin.showSpecificUser', $user->user_id)->with('success', 'Profil mis à jour avec succès');
    }

    public function DeleteUser($id): RedirectResponse
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('utilisateurs.members')->with('success', 'Le profil a été supprimé avec succès');
    }

    public function mdpUniversel($id)
    {
        $user = User::find($id);
        $user->update([
            'password' => bcrypt('concordia')
        ]);
        $email = $user->email;

        Mail::send('emails.mdpUniversel', ['users' => $user, 'email' => $user->email], function (Message $message) use ($email) {
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($email);
            $message->subject('Reinitialisation du Mot de Passe');
        });

        return redirect()->back()->with('success', 'Le mot de passe de ' . $user->lastname . ' ' . $user->name . ' a été réinitialisé avec succès');
    }

    public function familleMembers($user_id)
    {
        $famille = User::where('family_id', $user_id)->get();
        return view('admin.modals.familleMembers', compact('famille'))->with('user', auth()->user());
    }

    public function DeleteUsermodal($id)
    {
        $n_users = User::find($id);
        return view('admin.modals.deleteUser', compact('n_users'));
    }

    public function specificUser($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        return view('admin.modals.specificUser', compact('user', 'roles'));
    }
}
