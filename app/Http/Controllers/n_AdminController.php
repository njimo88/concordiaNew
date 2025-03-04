<?php

namespace App\Http\Controllers;

use App\Models\A_Blog_Post;
use App\Models\User;
use App\Models\Role;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use App\Http\Requests\AddUserform;
use App\Models\Carousel;
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
use App\Models\Shop_article;
use Intervention\Image\Facades\Image;

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
        $query = User::query()
            ->with(['adhesions' => function ($query) {
                $query->orderBy('saison', 'desc')->orderBy('shop_article.title', 'asc');
            }])
            ->with(relations: ['familyParents']);

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
        $n_users = User::with(['adhesions' => function ($query) {
            $query->orderBy('saison', 'desc')->orderBy('shop_article.title', 'asc');
        }])
            ->with(relations: ['familyParents'])
            ->find($user_id);

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

    public static function resizeAndSaveImage($file, $fileName, $filePath)
    {
        $directory = public_path($filePath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Redimensionner le certificat médical uniquement si nécessaire
        $image = Image::make($file);

        // Vérifie si la largeur ou la hauteur dépasse 1200 pixels
        if ($image->width() > 1200 || $image->height() > 1200) {
            if ($image->width() > $image->height()) {
                // Redimensionner la largeur à 1200 pixels tout en conservant le ratio
                $image->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                // Redimensionner la hauteur à 1200 pixels tout en conservant le ratio
                $image->resize(null, 1200, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }

        // Sauvegarder le fichier redimensionné ou original si aucun redimensionnement n'est nécessaire
        // $filePath = 'uploads/CertificatsMedicaux/' . $fileName;
        $filePath = $filePath . $fileName;
        $image->save(public_path($filePath), 75);

        // On retourne le chemin
        return $filePath;
    }

    public static function saveCertificateAndUpdateDatabase(Request $request, $user_id, $user, $emissionDate, $resize, $validated = 0)
    {
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

        if (!$resize) {
            //------------------SANS RESIZER------------------
            // Déplacer le fichier vers le dossier public/images/certificatsMedicaux
            $file->move(public_path('/uploads/CertificatsMedicaux'), $fileName);

            // Le chemin du fichier dans le dossier public
            $filePath = 'uploads/CertificatsMedicaux/' . $fileName;
            //------------------SANS RESIZER------------------
        } else {
            //------------------AVEC RESIZER------------------
            $filePath = self::resizeAndSaveImage($file, $fileName, 'uploads/CertificatsMedicaux/');
            //------------------AVEC RESIZER------------------
        }
        // Mettre à jour ou créer un certificat médical
        MedicalCertificates::updateOrCreate(
            ['user_id' => $user->user_id], // Trouver le certificat pour cet utilisateur (s'il existe)
            [
                'file_path' => $filePath, // Chemin du fichier
                'emission_date' => $emissionDate, // Utiliser la date d'expiration fournie ou celle par défaut
                'validated' => $validated // Mettre "validated" à 1 car nous sommes des admins qui modifions ses données
            ]
        );
    }


    public static function saveProfilePictureAndUpdateDatabase(Request $request, $user, $user_id, $resize, $freeze = 0)
    {
        // Gérer l'upload du fichier
        $file = $request->file('profile_image');

        // Obtenir l'extension du fichier
        $extension = $file->getClientOriginalExtension();

        // Générer un nom unique pour le fichier
        $fileName = "$user_id.$extension";

        // Vérifier si un ancien certificat existe pour cet utilisateur
        if ($user->image && file_exists(public_path($user->image))) {
            // dd($user->image);

            // Supprimer l'ancien fichier
            unlink(public_path($user->image));
        }


        // dd($user->image);

        $path = '/uploads/users/';
        if ($freeze) {
            $path = '/uploads/users/frozen/';
        }

        if (!$resize) {
            //------------------SANS RESIZER------------------
            // Déplacer le fichier vers le dossier public/images/certificatsMedicaux
            $file->move(public_path($path), $fileName);

            // Le chemin du fichier dans le dossier public
            $filePath = $path . $fileName;
            //------------------SANS RESIZER------------------
        } else {
            //------------------AVEC RESIZER------------------
            $filePath = self::resizeAndSaveImage($file, $fileName, $path); // FAIRE Pour spécifier le chemin dans la fonction
            //------------------AVEC RESIZER------------------
        }

        // Mettre à jour ou créer un certificat médical
        $user->update([
            'image' => $filePath, // Chemin du fichier
        ]);

        $imageName = pathinfo($user->image, PATHINFO_FILENAME); // Renvoie le nom du fichier sans l'extension "eferandel.png" -> "eferandel"

        // dd($user->image);
        // dd(Shop_article::whereRaw('LOWER(image) LIKE ?', ['%' . strtolower($imageName) . '%'])
        //     ->orWhereRaw('LOWER(image) LIKE ?', ['%' . strtolower($user->name) . '%'])
        //     ->orWhereRaw('LOWER(image) LIKE ?', ['%' . strtolower($user->lastname) . '%'])->get());

        // Mettre à jour les articles qui utilisent cette image
        Shop_article::whereRaw('LOWER(image) LIKE ?', ['%' . strtolower($imageName) . '%'])
            ->orWhereRaw('LOWER(image) LIKE ?', ['%' . strtolower($user->name) . '%'])
            ->orWhereRaw('LOWER(image) LIKE ?', ['%' . strtolower($user->lastname) . '%'])
            ->update(['image' => $filePath]);
    }

    public function editUser(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $validatedData = $request->validate([
            'role' => 'required|int',
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

        ], $messages = [
            'role.required' => 'Le rôle est requis.',
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
        ]);

        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request->password)]);
        } else {
            unset($request['password']);
        }

        // Définir la valeur par défaut pour la date d'expiration (date actuelle)
        $emissionDate = now();

        // Valider la date d'expiration si elle est saisie, qu'il y ait un certificat ou non
        if ($request->input('crt_emission')) {
            $emissionDate = $request->input('crt_emission');

            // Valider la date d'expiration : elle doit être après ou égale à aujourd'hui
            $request->validate([
                'crt_emission' => 'required|date|date_format:Y-m-d',
            ], [
                'crt_emission.required' => 'La date d\'expiration du certificat médical est requise quand un certificat est saisi.',
                'crt_emission.date' => 'La date d\'expiration du certificat médical doit être une date valide.',
            ]);

            // Si une date d'expiration est saisie mais aucun certificat médical n'est téléchargé,
            // on vérifie si l'utilisateur a déjà un certificat existant. Si ce n'est pas le cas,
            // on exige un nouveau certificat.
            if (!$request->hasFile('crt') && (!$user->medicalCertificate || !$user->medicalCertificate->file_path)) {
                $request->validate([
                    'crt' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ], [
                    'crt.required' => 'Le certificat médical est requis quand une date d\'expiration est saisie.',
                    'crt.image' => "Le certificat médical doit être une image.",
                    'crt.mimes' => "Le certificat médical doit être un fichier de type jpeg, png, jpg ou gif.",
                    'crt.max' => "Le certificat médical ne doit pas dépasser 2 Mo.",
                ]);
            }
        }

        // Si un certificat est téléchargé ou existe déjà, la date d'expiration est obligatoire
        if ($request->hasFile('crt') || ($user->medicalCertificate && $user->medicalCertificate->file_path !== "")) {
            // Vérifier que la date d'expiration est bien après aujourd'hui
            $request->validate([
                'crt_emission' => 'required|date|date_format:Y-m-d',
            ], [
                'crt_emission.required' => 'La date d\'expiration du certificat médical est requise quand un certificat est saisi.',
                'crt_emission.date' => 'La date d\'expiration du certificat médical doit être une date valide.',
            ]);
        }

        // Si un certificat médical est téléchargé
        if ($request->hasFile('crt')) {
            // Appeler la méthode de sauvegarde du certificat avec redimensionnement si nécessaire
            $this->saveCertificateAndUpdateDatabase($request, $user_id, $user, $emissionDate, 1, 1);
        } elseif ($request->has('crt_emission')) {
            // Mettre à jour la date d'expiration si elle a été saisie sans certificat
            if ($user->medicalCertificate) {
                $user->medicalCertificate->update([
                    'emission_date' => $emissionDate,
                    'validated' => 1
                ]);
            }
        }

        if ($request->input('crt_delete')) {
            // Vérifier si un certificat médical existe pour cet utilisateur
            if ($user->medicalCertificate && $user->medicalCertificate->file_path) {
                // Vérifier si le fichier existe physiquement sur le serveur
                $filePath = public_path($user->medicalCertificate->file_path);
                if (file_exists($filePath)) {
                    // Supprimer le fichier du serveur
                    unlink($filePath);
                }

                // Supprimer l'entrée dans la base de données
                $user->medicalCertificate->delete();
            }
        }

        $profilePicturePath = 'uploads/users/';
        $frozenPath = $profilePicturePath . 'frozen/';

        // Assurez-vous que les dossiers existent
        if (!is_dir(public_path($profilePicturePath))) {
            mkdir(public_path($profilePicturePath), 0777, true);
        }

        if (!is_dir(public_path($frozenPath))) {
            mkdir(public_path($frozenPath), 0777, true);
        }

        $freeze = 0;
        if ($request->input('freeze_image')) {
            $freeze = 1;
        }

        // Si une photo de profil est téléchargée
        if ($request->hasFile('profile_image')) {
            // Appeler la méthode de sauvegarde du certificat avec redimensionnement si nécessaire
            $this->saveProfilePictureAndUpdateDatabase($request, $user, $user_id, 1, $freeze);
        }
        // Gérer les cas où une nouvelle photo est téléchargée et suppression cochée
        else if ($request->hasFile('profile_image') && $request->input('delete_image')) {
            $this->saveProfilePictureAndUpdateDatabase($request, $user, $user_id, 1, $freeze);
        }
        // Si aucune nouvelle photo n'est téléchargée mais que "freeze" est activé et qu'on a pas coché 'supprimer'
        else if (!$request->hasFile('profile_image') && file_exists(public_path($user->image)) && $freeze && !$request->input('delete_image')) {
            // Extraire le nom du fichier à partir du chemin complet
            $imageName = basename($user->image);

            // Définir les anciens et nouveaux chemins complets
            $oldPath = public_path($profilePicturePath . $imageName);
            $newPath = public_path($frozenPath . $imageName);

            // Vérifier si le fichier existe avant de le déplacer
            if (file_exists($oldPath) && $imageName != '') {
                rename($oldPath, $newPath);

                // Mettre à jour le chemin dans la base de données avec le nouveau chemin
                $user->update(['image' => $frozenPath . $imageName]);
            }
        }
        // Si "freeze_image" est décoché et l'image est dans le dossier "frozen"
        else if (!$freeze && str_contains($user->image ?? '', 'frozen')) {
            // Extraire le nom du fichier
            $imageName = basename($user->image);

            // Définir les anciens et nouveaux chemins complets
            $oldPath = public_path($frozenPath . $imageName);
            $newPath = public_path($profilePicturePath . $imageName);

            // Vérifier si le fichier existe avant de le déplacer
            if (file_exists($oldPath)) {
                rename($oldPath, $newPath);

                // Mettre à jour le chemin dans la base de données avec le nouveau chemin
                $user->update(['image' => $profilePicturePath . $imageName]);
            }
        }
        // Si "delete_image" est activé, supprimer l'image existante
        else if ($request->input('delete_image')) {
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            // Mettre à jour l'utilisateur pour supprimer la référence à l'image
            $user->update(['image' => null]);
        }

        $user->update($request->all());

        return redirect()->back()->with('success', 'Le profil a été modifié avec succès');
    }

    public function editSpecificUser(Request $request, $userId)
    {
        try {
            $validatedData = $request->validate([
                'role' => 'required|int',
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => 'nullable|string',
                'gender' => 'required|string',
                'email' => 'required|email|max:255',
                'profession' => 'nullable|string|max:191',
                'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors()) // On garde les erreurs détaillées
                ->with('error', 'Un problème dans la saisie des données est survenu, vérifier les erreurs en dessous des champs.'); // On ajoute un message global
        }

        // Récupérer l'utilisateur
        $user = User::findOrFail($userId);

        // Définir la valeur par défaut pour la date d'expiration (date actuelle)
        $emissionDate = now();

        // Valider la date d'expiration si elle est saisie, qu'il y ait un certificat ou non
        if ($request->input('crt_emission')) {
            $emissionDate = $request->input('crt_emission');

            // Valider la date d'expiration : elle doit être après ou égale à aujourd'hui
            try {
                $validatedData = $request->validate([
                    'crt_emission' => 'required|date|date_format:Y-m-d',
                ], [
                    'crt_emission.required' => 'La date d\'expiration du certificat médical est requise quand un certificat est saisi.',
                    'crt_emission.date' => 'La date d\'expiration du certificat médical doit être une date valide.',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return redirect()->back()
                    ->withErrors($e->errors()) // On garde les erreurs détaillées
                    ->with('error', 'Un problème dans la saisie des données est survenu, vérifier les erreurs en dessous des champs.'); // On ajoute un message global
            }

            // Si une date d'expiration est saisie mais aucun certificat médical n'est téléchargé,
            // on vérifie si l'utilisateur a déjà un certificat existant. Si ce n'est pas le cas,
            // on exige un nouveau certificat.
            if (!$request->hasFile('crt') && (!$user->medicalCertificate || !$user->medicalCertificate->file_path)) {
                try {
                    $validatedData = $request->validate([
                        'crt' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                    ], [
                        'crt.required' => 'Le certificat médical est requis quand une date d\'expiration est saisie.',
                        'crt.image' => "Le certificat médical doit être une image.",
                        'crt.mimes' => "Le certificat médical doit être un fichier de type jpeg, png, jpg ou gif.",
                        'crt.max' => "Le certificat médical ne doit pas dépasser 2 Mo.",
                    ]);
                } catch (\Illuminate\Validation\ValidationException $e) {
                    return redirect()->back()
                        ->withErrors($e->errors()) // On garde les erreurs détaillées
                        ->with('error', 'Un problème dans la saisie des données est survenu, vérifier les erreurs en dessous des champs.'); // On ajoute un message global
                }
            }
        }

        // Si un certificat est téléchargé ou existe déjà, la date d'expiration est obligatoire
        if ($request->hasFile('crt') || ($user->medicalCertificate && $user->medicalCertificate->file_path !== "")) {
            // Vérifier que la date d'expiration est bien après aujourd'hui
            try {
                $validatedData = $request->validate([
                    'crt_emission' => 'required|date|date_format:Y-m-d',
                ], [
                    'crt_emission.required' => 'La date d\'expiration du certificat médical est requise quand un certificat est saisi.',
                    'crt_emission.date' => 'La date d\'expiration du certificat médical doit être une date valide.',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return redirect()->back()
                    ->withErrors($e->errors()) // On garde les erreurs détaillées
                    ->with('error', 'Un problème dans la saisie des données est survenu, vérifier les erreurs en dessous des champs.'); // On ajoute un message global
            }
        }

        // Si un certificat médical est téléchargé
        if ($request->hasFile('crt')) {
            // Appeler la méthode de sauvegarde du certificat avec redimensionnement si nécessaire
            $this->saveCertificateAndUpdateDatabase($request, $userId, $user, $emissionDate, 1, 1);
        } elseif ($request->has('crt_emission')) {
            // Mettre à jour la date d'expiration si elle a été saisie sans certificat
            if ($user->medicalCertificate) {
                $user->medicalCertificate->update([
                    'emission_date' => $emissionDate,
                    'validated' => 1
                ]);
            }
        }

        if ($request->input('crt_delete')) {
            // Vérifier si un certificat médical existe pour cet utilisateur
            if ($user->medicalCertificate && $user->medicalCertificate->file_path) {
                // Vérifier si le fichier existe physiquement sur le serveur
                $filePath = public_path($user->medicalCertificate->file_path);
                if (file_exists($filePath)) {
                    // Supprimer le fichier du serveur
                    unlink($filePath);
                }

                // Supprimer l'entrée dans la base de données
                $user->medicalCertificate->delete();
            }
        }

        $profilePicturePath = 'uploads/users/';
        $frozenPath = $profilePicturePath . 'frozen/';

        // Assurez-vous que les dossiers existent
        if (!is_dir(public_path($profilePicturePath))) {
            mkdir(public_path($profilePicturePath), 0777, true);
        }

        if (!is_dir(public_path($frozenPath))) {
            mkdir(public_path($frozenPath), 0777, true);
        }

        $freeze = 0;
        if ($request->input('freeze_image')) {
            $freeze = 1;
        }

        // Si une photo de profil est téléchargée
        if ($request->hasFile('profile_image')) {
            // Appeler la méthode de sauvegarde du certificat avec redimensionnement si nécessaire
            $this->saveProfilePictureAndUpdateDatabase($request, $user, $userId, 1, $freeze);
        }
        // Gérer les cas où une nouvelle photo est téléchargée et suppression cochée
        else if ($request->hasFile('profile_image') && $request->input('delete_image')) {
            $this->saveProfilePictureAndUpdateDatabase($request, $user, $userId, 1, $freeze);
        }
        // Si aucune nouvelle photo n'est téléchargée mais que "freeze" est activé
        else if (!$request->hasFile('profile_image') && file_exists(public_path($user->image)) && $freeze && !$request->input('delete_image')) {
            // Extraire le nom du fichier à partir du chemin complet
            $imageName = basename($user->image);

            // Définir les anciens et nouveaux chemins complets
            $oldPath = public_path($profilePicturePath . $imageName);
            $newPath = public_path($frozenPath . $imageName);

            // Vérifier si le fichier existe avant de le déplacer
            if (file_exists($oldPath) && $imageName != '') {
                rename($oldPath, $newPath);

                // Mettre à jour le chemin dans la base de données avec le nouveau chemin
                $user->update(['image' => $frozenPath . $imageName]);
            }
        }
        // Si "freeze_image" est décoché et l'image est dans le dossier "frozen"
        else if (!$freeze && str_contains($user->image ?? '', 'frozen')) {
            // Extraire le nom du fichier
            $imageName = basename($user->image);

            // Définir les anciens et nouveaux chemins complets
            $oldPath = public_path($frozenPath . $imageName);
            $newPath = public_path($profilePicturePath . $imageName);

            // Vérifier si le fichier existe avant de le déplacer
            if (file_exists($oldPath)) {
                rename($oldPath, $newPath);

                // Mettre à jour le chemin dans la base de données avec le nouveau chemin
                $user->update(['image' => $profilePicturePath . $imageName]);
            }
        }
        // Si "delete_image" est activé, supprimer l'image existante
        else if ($request->input('delete_image')) {
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            // Mettre à jour l'utilisateur pour supprimer la référence à l'image
            $user->update(['image' => null]);
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
        $user = User::with(['adhesions' => function ($query) {
            $query->orderBy('saison', 'desc')->orderBy('shop_article.title', 'asc');
        }])
            ->with(relations: ['familyParents'])
            ->find($id);
        $roles = Role::all();

        return view('admin.specificUser', compact('user', 'roles'));
    }

    public function messageGeneral()
    {
        $message = SystemSetting::where('name', '=', 'Message general')->first();
        return view('admin.message-general', compact('message'));
    }

    public function editMessageGeneral(Request $request)
    {
        SystemSetting::where('name', '=', 'Message general')->first()->update(['Message' => $request->input('editor1')]);
        return redirect()->route('message.general')->with('success', 'Message général mis à jour avec succès');
    }

    public function seeMessageMaintenance()
    {
        $message = SystemSetting::where('name', '=', 'maintenance')->first();

        return view('admin.message-maintenance', compact('message'));
    }

    public function editMessageMaintenance(Request $request)
    {
        // dd($request->input('editor1'));

        if (trim($request->input('editor1')) == '' || $request->input('editor1') == null) {
            $message =
                '<h2>Réouverture du site internet au plus vite</h2>
            <div>
                <p>Désolé pour la gêne occasionnée. <br> Nous effectuons 
                    actuellement une maintenance. <br> Vous pouvez nous suivre 
                    sur <a target="_blank" href="https://www.facebook.com/GymConcordia/?locale=fr_FR">
                        Facebook</a> ou <a target="_blank" href="https://www.instagram.com/gym_concordia/?__coig_restricted=1">
                            Instagram</a> </p>
                <p>Nous serons de retour très vite &mdash; La Gym Concordia</p>
            </div>';
        } else {
            $message = $request->input('editor1');
        }

        SystemSetting::where('name', '=', 'maintenance')->first()->update(['Message' => $message]);
        return redirect()->route('message.maintenance.see')->with('success', 'Message général mis à jour avec succès');
    }

    public function editCarroussel()
    {
        $carouselImages = Carousel::where('locked', '=', '0')
            ->orderBy("image_order", "ASC")
            ->get();

        $blogArticles = A_Blog_Post::orderBy('date_post', 'DESC')->get();

        return view('admin.edit-carroussel', compact('carouselImages', 'blogArticles'));
    }

    public function updateCarroussel(Request $request)
    {
        $request->merge([
            'ordered_ids' => is_string($request->input('ordered_ids'))
                ? json_decode($request->input('ordered_ids'), true)
                : $request->input('ordered_ids'),
        ]);

        // Validation des données
        $validatedData = $request->validate([
            'ordered_ids' => 'nullable|array',
            'ordered_ids.*' => 'nullable|exists:carousel,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'links' => 'nullable|array',
            'links.*' => 'nullable|string',
            'actives' => 'nullable|array',
            'actives.*' => 'nullable|integer',
        ]);


        // Mise à jour de l'ordre des images
        if (!empty($request->ordered_ids)) {
            foreach ($request->ordered_ids as $index => $id) {
                $image = Carousel::find($id);
                if ($image) {
                    $position = $index + 1;
                    if (Carousel::where('image_order', '=', $position)) {
                        $position += 1;
                    }

                    $image->image_order = $position;
                    $image->save();
                }
            }
        }

        // Mise à jour des images
        if (!empty($request->images)) {
            foreach ($request->images as $id => $imageFile) {
                $image = Carousel::find($id);
                if ($image && $imageFile) {
                    // Vérifie si le dossier existe, sinon crée-le
                    $directory = public_path('uploads/Slider');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0775, true); // Crée le dossier si nécessaire
                    }

                    // Utilise l'ID comme nom de fichier et déplace l'image dans le dossier public/uploads/Slider
                    $fileName = $id . '.' . $imageFile->getClientOriginalExtension();
                    $path = $directory . '/' . $fileName;

                    // Déplace le fichier
                    $imageFile->move($directory, $fileName);

                    // Met à jour le lien de l'image dans la base de données
                    $image->image_link = 'uploads/Slider/' . $fileName;
                    $image->save();
                }
            }
        }

        // Mise à jour des liens
        if (!empty($request->links)) {
            foreach ($request->links as $id => $link) {
                $image = Carousel::find($id);
                if ($image) {
                    $image->click_link = $link ? parse_url(route('blog', ['id' => $link]), PHP_URL_PATH) : '#';
                    $image->save();
                }
            }
        }

        // Mise à jour des status "active"
        if (!empty($request->actives)) {
            foreach ($request->actives as $id => $active) {
                $image = Carousel::find($id);
                if ($image) {
                    $image->active = $active;
                    $image->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Le carrousel a été mis à jour avec succès.');
    }


    public function addCarroussel(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'new_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'new_click_link' => 'string',
        ]);

        // Récupérer le fichier de l'image
        $imageFile = $request->file('new_image');

        // Dossier de destination pour les images
        $directory = public_path('uploads/Slider');
        if (!file_exists($directory)) {
            mkdir($directory, 0775, true); // Crée le dossier si nécessaire
        }

        $clickLink = $request->new_click_link;

        // Créer un nouvel enregistrement pour l'image dans la base de données
        $image = new Carousel();
        $image->image_link = 'temp';
        $image->click_link = $clickLink ? parse_url(route('blog', ['id' => $clickLink]), PHP_URL_PATH) : '#';
        $image->image_order = Carousel::count() + 1;
        $image->save(); // Sauvegarder pour obtenir l'ID généré

        // Utiliser l'ID de l'image comme nom de fichier
        $fileName = $image->id . '.' . $imageFile->getClientOriginalExtension();
        $path = $directory . '/' . $fileName;

        // Déplacer l'image dans le dossier public/uploads/Slider
        $imageFile->move($directory, $fileName);

        // Mettre à jour le lien de l'image dans la base de données
        $image->image_link = 'uploads/Slider/' . $fileName;
        $image->save();

        return redirect()->back()->with('success', 'Le carrousel a été mis à jour avec succès.');
    }


    public function deleteCarroussel($id)
    {
        // Trouver l'élément du carrousel par ID
        $carouselItem = Carousel::find($id);

        if ($carouselItem) {
            $itemsWithSuperiorOrder = Carousel::where('image_order', '>', $carouselItem->image_order)->get();

            foreach ($itemsWithSuperiorOrder as $item) {
                $itemToUpdate = Carousel::find($item->id);
                $itemToUpdate->image_order -= 1;
                $itemToUpdate->save();
            }
            // Récupérer le chemin du fichier image à supprimer
            $imagePath = public_path($carouselItem->image_link);

            // Vérifier si le fichier existe et le supprimer
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Supprimer l'enregistrement du carrousel de la base de données
            $carouselItem->delete();

            return redirect()->back()->with('success', 'L\'image du carrousel a été supprimée avec succès.');
        }

        return redirect()->back()->with('error', 'L\'élément du carrousel n\'a pas été trouvé.');
    }


    public function editImage()
    {
        return view('admin.edit-image');
    }
}
