<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animation;
use App\Models\User;
use App\Models\Room;
use App\Models\AnimationsCategories;
use App\Models\AnimationsRegistrations;
use App\Models\EmailQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AnimationsController extends Controller
{
    private function getTextColor($hexColor)
    {
        // Convertir le code hex en RGB
        list($r, $g, $b) = sscanf($hexColor, "#%02x%02x%02x");

        // Calcul de la luminance relative (formule W3C)
        $luminance = (0.2126 * ($r / 255)) + (0.7152 * ($g / 255)) + (0.0722 * ($b / 255));

        // Si la luminance est inférieure à 0.5, utiliser du texte blanc, sinon du texte noir
        return $luminance < 0.5 ? '#FFFFFF' : '#000000';
    }

    public function gestionAnimations()
    {
        // Récupère les animations à venir (starttime + duration > aujourd'hui)
        $animationsAVenir = Animation::whereRaw('DATE_ADD(animation_starttime, INTERVAL duration MINUTE) > ?', [today()])
            ->orderBy('duration', 'DESC')
            ->orderBy('animation_starttime', 'DESC')
            ->get();

        // Récupère les animations passées (starttime + duration < aujourd'hui)
        $animationsPassees = Animation::whereRaw('DATE_ADD(animation_starttime, INTERVAL duration MINUTE) < ?', [today()])
            ->orderBy('animation_starttime', 'DESC')
            ->orderBy('duration', 'DESC')
            ->get();

        // Récupère les enseignants, les salles et les catégories
        $teachers = User::where('role', '>', '29')->get();
        $rooms = Room::where('room_active_animations', '=', '1')->get();
        $categories = AnimationsCategories::get();

        return view('admin.animations.gestionAnimations', compact('animationsAVenir', 'animationsPassees', 'teachers', 'rooms', 'categories'));
    }

    public function createAnimationBackend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title-creation' => 'required|string|max:255',
            'image_path-creation' => 'nullable|string|max:255',
            'description-creation' => 'required|string|max:3000',
            'teacher_id-creation' => 'required|exists:users,user_id',
            'animation_starttime-creation' => 'required|date|after:now',
            'duration-creation' => 'required',
            'max_participants-creation' => 'required|integer|min:1',
            'visibilite-creation' => 'required|boolean',
            'price-creation' => 'nullable|numeric|min:0',
            'room_id-creation' => 'required|exists:rooms,id_room',
            'category_id-creation' => 'required|exists:animations_categories,id',
        ], [
            'title-creation.required' => 'Le titre est obligatoire.',
            'title-creation.string' => 'Le titre doit être une chaîne de caractères.',
            'title-creation.max' => 'Le titre ne doit pas dépasser 255 caractères.',

            'image_path-creation.string' => 'Le chemin de l\'image doit être une chaîne de caractères.',
            'image_path-creation.max' => 'Le chemin de l\'image ne doit pas dépasser 255 caractères.',

            'description-creation.required' => 'La description est obligatoire.',
            'description-creation.string' => 'La description doit être une chaîne de caractères.',
            'description-creation.max' => 'La description ne doit pas dépasser 3000 caractères.',

            'teacher_id-creation.required' => 'L\'enseignant est obligatoire.',
            'teacher_id-creation.exists' => 'L\'enseignant sélectionné n\'existe pas.',

            'animation_starttime-creation.required' => 'La date et l\'heure de début de l\'animation sont obligatoires.',
            'animation_starttime-creation.date' => 'La date et l\'heure de début doivent être au format valide.',
            'animation_starttime-creation.after' => 'La date et l\'heure de début doivent être après maintenant.',

            'duration-creation.required' => 'La durée est obligatoire.',

            'max_participants-creation.required' => 'Le nombre maximum de participants est obligatoire.',
            'max_participants-creation.integer' => 'Le nombre maximum de participants doit être un nombre entier.',
            'max_participants-creation.min' => 'Le nombre maximum de participants doit être au moins 1.',

            'visibilite-creation.required' => 'La visibilité est obligatoire.',
            'visibilite-creation.boolean' => 'La visibilité doit être vraie ou fausse.',

            'price-creation.numeric' => 'Le prix doit être un nombre.',
            'price-creation.min' => 'Le prix ne peut pas être inférieur à 0.',

            'room_id-creation.required' => 'Le lieu est obligatoire.',
            'room_id-creation.exists' => 'Le lieu sélectionné n\'existe pas.',

            'category_id-creation.required' => 'La catégorie est obligatoire.',
            'category_id-creation.exists' => 'La catégorie sélectionnée n\'existe pas.',
        ]);

        // Vérification des erreurs
        if ($validator->fails()) {
            // Si la validation échoue, on retourne les erreurs au format JSON
            return response()->json([
                'error' => true,
                'success' => false,
                'errors' => $validator->errors(),  // Utilise les erreurs générées par Validator
            ], 400);  // 400 pour une mauvaise requête
        }

        // return response()->json([$request->input('duration-creation')], 400);

        // Traitement de la création de l'animation
        $animation = new Animation();
        $animation->title = $request->input('title-creation');
        $animation->image_path = $request->input('image_path-creation');
        $animation->description = $request->input('description-creation');
        $animation->teacher_id = $request->input('teacher_id-creation');
        $animation->animation_starttime = $request->input('animation_starttime-creation');
        $animation->duration = $request->input('duration-creation');
        $animation->max_participants = $request->input('max_participants-creation');
        $animation->visibilite = $request->input('visibilite-creation');
        $animation->price = $request->input('price-creation');
        $animation->room_id = $request->input('room_id-creation');
        $animation->category_id = $request->input('category_id-creation');
        $animation->saison = saison_active();
        $animation->created_by = auth()->id();
        $animation->save();

        Session::flash('success', 'Animation créée avec succès.');
        return response()->json([
            'success' => true,
            'error' => false,
            'redirect_url' => '/admin/gestionAnimations'
        ], 200);
    }

    public function editAnimationBackend(Request $request, $id)
    {
        // Adapter les clés du formulaire pour correspondre aux noms attendus
        $fields = [
            'title' => 'title-edit-' . $id,
            'image_path' => 'image_path-edit-' . $id,
            'description' => 'description-edit-' . $id,
            'teacher_id' => 'teacher_id-edit-' . $id,
            'animation_starttime' => 'animation_starttime-edit-' . $id,
            'duration' => 'duration-edit-' . $id,
            'max_participants' => 'max_participants-edit-' . $id,
            'visibilite' => 'visibilite-edit-' . $id,
            'price' => 'price-edit-' . $id,
            'room_id' => 'room_id-edit-' . $id,
            'category_id' => 'category_id-edit-' . $id,
        ];

        // Valider les données du formulaire
        $validator = Validator::make($request->all(), [
            $fields['title'] => 'required|string|max:255',
            $fields['image_path'] => 'nullable|string|max:255',
            $fields['description'] => 'required|string|max:3000',
            $fields['teacher_id'] => 'required|exists:users,user_id',
            $fields['animation_starttime'] => 'required|date|after:now',
            $fields['duration'] => 'required',
            $fields['max_participants'] => 'required|integer|min:1',
            $fields['visibilite'] => 'required|boolean',
            $fields['price'] => 'nullable|numeric|min:0',
            $fields['room_id'] => 'required|exists:rooms,id_room',
            $fields['category_id'] => 'required|exists:animations_categories,id',
        ], [
            $fields['title'] . '.required' => 'Le titre est obligatoire.',
            $fields['title'] . '.string' => 'Le titre doit être une chaîne de caractères.',
            $fields['title'] . '.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            $fields['image_path'] . '.string' => 'Le chemin de l\'image doit être une chaîne de caractères.',
            $fields['image_path'] . '.max' => 'Le chemin de l\'image ne doit pas dépasser 255 caractères.',
            $fields['description'] . '.required' => 'La description est obligatoire.',
            $fields['description'] . '.string' => 'La description doit être une chaîne de caractères.',
            $fields['description'] . '.max' => 'La description ne doit pas dépasser 3000 caractères.',
            $fields['teacher_id'] . '.required' => 'L\'enseignant est obligatoire.',
            $fields['teacher_id'] . '.exists' => 'L\'enseignant sélectionné n\'existe pas.',
            $fields['animation_starttime'] . '.required' => 'La date et l\'heure de début de l\'animation sont obligatoires.',
            $fields['animation_starttime'] . '.date' => 'La date et l\'heure de début doivent être au format valide.',
            $fields['animation_starttime'] . '.after' => 'La date et l\'heure de début doivent être après maintenant.',
            $fields['duration'] . '.required' => 'La durée est obligatoire.',
            $fields['max_participants'] . '.required' => 'Le nombre maximum de participants est obligatoire.',
            $fields['max_participants'] . '.integer' => 'Le nombre maximum de participants doit être un nombre entier.',
            $fields['max_participants'] . '.min' => 'Le nombre maximum de participants doit être au moins 1.',
            $fields['visibilite'] . '.required' => 'La visibilité est obligatoire.',
            $fields['visibilite'] . '.boolean' => 'La visibilité doit être vraie ou fausse.',
            $fields['price'] . '.numeric' => 'Le prix doit être un nombre.',
            $fields['price'] . '.min' => 'Le prix ne peut pas être inférieur à 0.',
            $fields['room_id'] . '.required' => 'Le lieu est obligatoire.',
            $fields['room_id'] . '.exists' => 'Le lieu sélectionné n\'existe pas.',
            $fields['category_id'] . '.required' => 'La catégorie est obligatoire.',
            $fields['category_id'] . '.exists' => 'La catégorie sélectionnée n\'existe pas.',
        ]);

        // Vérification des erreurs
        if ($validator->fails()) {
            // Si la validation échoue, on retourne les erreurs au format JSON
            return response()->json([
                'error' => true,
                'success' => false,
                'errors' => $validator->errors(),  // Utilise les erreurs générées par Validator
            ], 400);  // 400 pour une mauvaise requête
        }

        // Récupération de l'animation
        $animation = Animation::findOrFail($id);

        // Mise à jour des champs
        $animation->title = $request->input($fields['title']);
        $animation->image_path = $request->input($fields['image_path']);
        $animation->description = $request->input($fields['description']);
        $animation->teacher_id = $request->input($fields['teacher_id']);
        $animation->animation_starttime = $request->input($fields['animation_starttime']);
        $animation->duration = $request->input($fields['duration']);
        $animation->max_participants = $request->input($fields['max_participants']);
        $animation->visibilite = $request->input($fields['visibilite']);
        $animation->price = $request->input($fields['price']);
        $animation->room_id = $request->input($fields['room_id']);
        $animation->category_id = $request->input($fields['category_id']);
        $animation->saison = saison_active();
        $animation->updated_by = auth()->id(); // Utilisateur connecté

        $animation->save();

        Session::flash('success', 'Animation modifiée avec succès.');
        return response()->json([
            'success' => true,
            'error' => false,
            'redirect_url' => '/admin/gestionAnimations'
        ], 200);
    }

    public function deleteAnimationBackend(Request $request, $id)
    {
        // Vérifie si l'animation existe
        $animation = Animation::findOrFail($id);

        // Tentative de suppression
        if ($animation->delete()) {
            Session::flash('success', 'Animation supprimée avec succès.');
            return response()->json([
                'success' => true,
                'error' => false,
                'message' => 'Animation supprimée avec succès.',
                'redirect_url' => '/admin/gestionAnimations'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => 'Erreur lors de la suppression de l\'animation.'
            ], 400);
        }
    }

    public function gestionCategoriesAnimations()
    {
        $categories = AnimationsCategories::get();

        return view('admin.animations.gestionCategories', compact('categories'));
    }

    public function createCategoryBackend(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name-creation' => 'required|string|max:255',
            'color-creation' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ], [
            'name-creation.required' => 'Le nom est obligatoire.',
            'name-creation.string' => 'Le nom doit être une chaîne de caractères.',
            'name-creation.max' => 'Le nom ne doit pas dépasser 255 caractères.',

            'color-creation.regex' => 'La couleur doit être un code hexadécimal valide.',
        ]);

        // Vérification des erreurs
        if ($validator->fails()) {
            // Si la validation échoue, on retourne les erreurs au format JSON
            return response()->json([
                'error' => true,
                'success' => false,
                'errors' => $validator->errors(),  // Utilise les erreurs générées par Validator
            ], 400);  // 400 pour une mauvaise requête
        }


        // Traitement de la création de l'animation
        $category = new AnimationsCategories();
        $category->name = $request->input('name-creation');
        $category->color = $request->input('color-creation');
        $category->text_color = $this->getTextColor($category->color);
        $category->save();

        Session::flash('success', 'Catégorie créée avec succès.');
        return response()->json([
            'success' => true,
            'error' => false,
            'redirect_url' => '/admin/gestionCategoriesAnimations'
        ], 200);
    }

    public function editCategoryBackend(Request $request, $id)
    {
        // Adapter les clés du formulaire pour correspondre aux noms attendus
        $fields = [
            'name' => 'name-edit-' . $id,
            'color' => 'color-edit-' . $id,
        ];

        // Valider les données du formulaire
        $validator = Validator::make($request->all(), [
            $fields['name'] => 'required|string|max:255',
            $fields['color'] => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ], [
            $fields['name'] . '.required' => 'Le nom est obligatoire.',
            $fields['name'] . '.string' => 'Le nom doit être une chaîne de caractères.',
            $fields['name'] . '.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            $fields['color'] . '.regex' => 'La couleur doit être un code hexadécimal valide.',
        ]);

        // Vérification des erreurs
        if ($validator->fails()) {
            // Si la validation échoue, on retourne les erreurs au format JSON
            return response()->json([
                'error' => true,
                'success' => false,
                'errors' => $validator->errors(),  // Utilise les erreurs générées par Validator
            ], 400);  // 400 pour une mauvaise requête
        }

        // Récupération de l'animation
        $category = AnimationsCategories::findOrFail($id);

        // Mise à jour des champs
        $category->name = $request->input($fields['name']);
        $category->color = $request->input($fields['color']);
        $category->text_color = $this->getTextColor($category->color);

        $category->save();

        Session::flash('success', 'Catégorie modifiée avec succès.');
        return response()->json([
            'success' => true,
            'error' => false,
            'redirect_url' => '/admin/gestionCategoriesAnimations'
        ], 200);
    }

    public function deleteCategoryBackend(Request $request, $id)
    {
        // Vérifie si l'animation existe
        $animation = AnimationsCategories::findOrFail($id);

        // Tentative de suppression
        if ($animation->delete()) {
            Session::flash('success', 'Catégorie supprimée avec succès.');
            return response()->json([
                'success' => true,
                'error' => false,
                'message' => 'Catégorie supprimée avec succès.',
                'redirect_url' => '/admin/gestionCategoriesAnimations'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => 'Erreur lors de la suppression de la catégorie.'
            ], 400);
        }
    }

    public function visiteursAnimation()
    {

        $animations = Animation::where('visibilite', '=', '1')->where('animation_starttime', '>', now())->get();
        $loginUser = auth()->check() ? User::find(auth()->id()) : null;

        return view('Animations.animations', compact('animations', 'loginUser'));
    }

    public function visiteursAnimationInscription(Request $request)
    {
        $animationId = $request->input('animation_id');
        $animation = Animation::find($animationId);

        if (!$animation) {
            Session::flash('error', 'L\'animation sélectionnée n\'existe pas.');
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => 'L’animation sélectionnée n’existe pas.',
                'redirect_url' => route('visiteurs.animation'),
            ], 404);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'nom.*' => 'required|string|max:255',
            'prenom.*' => 'required|string|max:255',
            'email.*' => 'required|email|max:255',
            'telephone.*' => [
                'required',
                'string',
                'max:30',
                'regex:/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/'
            ],
            'age.*' => 'required|integer|min:1',
            'contact_urgence.*' => [
                'required',
                'string',
                'max:30',
                'regex:/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/'
            ],
        ], [
            'nom.*.required' => 'Le nom est obligatoire.',
            'nom.*.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.*.max' => 'Le nom ne doit pas dépasser 255 caractères.',

            'prenom.*.required' => 'Le prénom est obligatoire.',
            'prenom.*.string' => 'Le prénom doit être une chaîne de caractères.',
            'prenom.*.max' => 'Le prénom ne doit pas dépasser 255 caractères.',

            'email.*.required' => 'L’email est obligatoire.',
            'email.*.email' => 'Veuillez entrer une adresse email valide.',
            'email.*.max' => 'L’email ne doit pas dépasser 255 caractères.',

            'telephone.*.required' => 'Le numéro de téléphone est obligatoire.',
            'telephone.*.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'telephone.*.max' => 'Le numéro de téléphone ne doit pas dépasser 30 caractères.',
            'telephone.*.regex' => 'Le numéro de téléphone doit être valide (ex: +33 6 12 34 56 78 ou 0612345678).',

            'age.*.required' => 'L’âge est obligatoire.',
            'age.*.integer' => 'L’âge doit être un nombre entier.',
            'age.*.min' => 'L’âge doit être supérieur ou égal à 1.',

            'contact_urgence.*.required' => 'Le contact d’urgence est obligatoire.',
            'contact_urgence.*.string' => 'Le contact d’urgence doit être une chaîne de caractères.',
            'contact_urgence.*.max' => 'Le contact d’urgence ne doit pas dépasser 30 caractères.',
            'contact_urgence.*.regex' => 'Le contact d’urgence doit être valide (ex: +33 6 12 34 56 78 ou 0612345678).',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => true,
                'errors' => $validator->errors(),
            ], 400);
        }

        // Vérifier le nombre max de participants
        $currentParticipants = AnimationsRegistrations::where('animation_id', $animationId)->count();
        // dd($currentParticipants);
        if ($currentParticipants + count($request->nom) > $animation->max_participants) {
            Session::flash('error', 'Il n’y a plus assez de places disponibles pour cette animation.');
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => 'Il n’y a plus assez de places disponibles pour cette animation.',
                'redirect_url' => route('visiteurs.animation'),
            ], 400);
        }


        foreach ($request->nom as $index => $value) {
            $registration = AnimationsRegistrations::create([
                'first_name' => $request->nom[$index],
                'last_name' => $request->prenom[$index],
                'email' => $request->email[$index],
                'phone' => $request->telephone[$index],
                'age' => $request->age[$index],
                'emergency_contact' => $request->contact_urgence[$index],
                'unsubscribe_token' => Str::uuid(),
                'animation_id' => $animationId,
            ]);

            $unsubscribeLink = route('visiteurs.animation.desinscription');

            $subject = "Confirmation d'inscription à l'animation : {$animation->title}";
            $content = "
                <p>Nous vous confirmons votre inscription à l'animation : <strong>\"{$animation->title}\"</strong>.</p>
                
                <p><strong>Détails de l'animation :</strong></p>
                <ul>
                    <li><strong>Date de début :</strong> " . \Carbon\Carbon::parse($animation->animation_starttime)->format('d/m/Y à H\hi') . "</li>
                    <li><strong>Durée :</strong> " . \Carbon\Carbon::parse($animation->duration)->format('H\hi') . "</li>
                    " . ($animation->price > 0 ? "<li><strong>Prix :</strong> {$animation->price}€ (À payer en espèces sur place)</li>" : "<li><strong>Prix :</strong> Gratuit</li>") . "
                </ul>
                
                <p>Nous sommes ravis de vous compter parmi nous et espérons que vous passerez un excellent moment.</p>
                
                <p>Si vous souhaitez vous désinscrire, vous pouvez cliquer sur le lien suivant :  
                <form action='{$unsubscribeLink}' method='POST'>
                    <input type='hidden' name='_token' value='" . csrf_token() . "'>
                    <input type='hidden' name='unsubscribe_token' value='{$registration->unsubscribe_token}'>
                    <button type='submit' style='padding: 10px 15px; background-color: #dc3545; color: #ffffff; border: none; font-weight: bold; border-radius: 5px; cursor: pointer; display: inline-block;'>
                        Se désinscrire
                    </button>
                </form>
            ";

            $attachmentsPaths = [];
            // $fromEmail = 'president@gym-concordia.com';
            // $fromName = 'Gym Concordia [Président]';
            // $senderName = 'Elric Ferandel - Président';

            $fromEmail = 'internet@gym-concordia.com';
            $fromName = 'Gym Concordia [Test]';
            $senderName = 'Internet Test - Test';


            EmailQueue::create([
                'recipient' => $registration->email,
                'recipientName' => "{$registration->last_name} {$registration->first_name}",
                'subject' => $subject,
                'content' => $content,
                'sender' => $fromEmail,
                'fromName' => $fromName,
                'senderName' => $senderName,
                'status' => 'pending',
                'attachments' => json_encode($attachmentsPaths),
            ]);
        }

        Session::flash('success', 'Inscription(s) réalisée(s) avec succès. Vérifiez votre boîte mail dans les minutes qui suivent.');
        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'Inscription(s) réalisée(s) avec succès. Vérifiez votre boîte mail dans les minutes qui suivent pour avoir la confirmation.',
            'redirect_url' => route('visiteurs.animation'),
        ], 200);
    }

    public function visiteursAnimationDesinscription(Request $request)
    {
        $token = $request->input('unsubscribe_token');

        $registration = AnimationsRegistrations::where('unsubscribe_token', $token)->first();

        return view('Animations.animationsDesinscription', compact('registration'));
    }

    public function visiteursAnimationDesinscriptionBackend(int $id)
    {
        $registration = AnimationsRegistrations::find($id);

        if (!$registration) {
            Session::flash('error', 'Cette inscription n\'existe pas.');
            return redirect()->route('visiteurs.animation');
        }

        $email = $registration->email;
        $fullName = "{$registration->last_name} {$registration->first_name}";
        $animationTitle = $registration->animation->title;

        $registration->delete();

        $subject = "Confirmation de désinscription à l'animation : {$animationTitle}";
        $content = "
            <p>Nous vous confirmons votre désinscription à l'animation : <strong>\"{$animationTitle}\"</strong>.</p>
        ";

        $attachmentsPaths = [];
        $fromEmail = 'internet@gym-concordia.com';
        $fromName = 'Gym Concordia [Test]';
        $senderName = 'Internet Test - Test';

        EmailQueue::create([
            'recipient' => $email,
            'recipientName' => $fullName,
            'subject' => $subject,
            'content' => $content,
            'sender' => $fromEmail,
            'fromName' => $fromName,
            'senderName' => $senderName,
            'status' => 'pending',
            'attachments' => json_encode($attachmentsPaths),
        ]);

        Session::flash('success', 'Désinscription réalisée avec succès. Vérifiez votre boîte mail pour la confirmation.');
        return redirect()->route('visiteurs.animation');
    }
}
