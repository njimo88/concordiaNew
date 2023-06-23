<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Shop_article;
use App\Models\User;
use Illuminate\Support\Facades\DB;
require_once(app_path().'/fonction.php');

class UserArticleAssociation extends Component
{
    public $selectedArticle;
    public $search = '';

    protected $listeners = ['updateList' => '$refresh'];

    public function getArticlesProperty()
    {
        $S_active = saison_active();
        return Shop_article::where('saison', $S_active)->orderBy('title')->get();
    }

    public function getUsersProperty()
{
    if (strlen($this->search) < 3) {
        return collect();
    }

    return User::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('lastname', 'like', '%' . $this->search . '%')
                ->get();
}


    public function getSelectedUsersProperty()
    {
        if (! $this->selectedArticle) {
            return collect();
        }

        return DB::table('users')
            ->join('selected_limit', 'users.user_id', '=', 'selected_limit.user_id')
            ->where('selected_limit.id_shop_article', $this->selectedArticle)
            ->select('users.*')
            ->get();
    }

    public function addUser($userId)
{
    $exists = DB::table('selected_limit')
        ->where('user_id', $userId)
        ->where('id_shop_article', $this->selectedArticle)
        ->exists();

    if (!$exists) {
        DB::table('selected_limit')->insert(
            ['user_id' => $userId, 'id_shop_article' => $this->selectedArticle]
        );

        $this->emit('updateList');
    } else {
        $this->emit('userAlreadySelected');
    }
}


    public function removeUser($userId)
    {
        DB::table('selected_limit')
            ->where('user_id', $userId)
            ->where('id_shop_article', $this->selectedArticle)
            ->delete();

        $this->emit('updateList');
    }

    public function render()
    {
        return view('livewire.user-article-association');
    }
}
