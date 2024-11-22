<?php

namespace App\Http\Livewire;

use GuzzleHttp\Promise\Create;
use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Models\Declinaison;
use App\Models\Shop_article;



class DeclinaisonArticletype2 extends Component
{   
    
    public $declinationName;
    public $stockInitial;
    public $articleId;

    public $NewdeclinationName;
    public $NewstockInitial;
    public $NewstockActual;
    public $EditedecID;

    public $declinaisons = [];

    //  `mount` method is automatically called when the component is initialized.
    public function mount($articleId,$declinaisons)
    {
        $this->articleId = $articleId;
        $this->declinaisons = $declinaisons;

    }

    public function create()
    {
        $declinaison = new Declinaison();
        $declinaison->shop_article_id =$this->articleId;
        $declinaison->libelle = $this->declinationName;
        $declinaison->stock_ini_d = $this->stockInitial;
        $declinaison->stock_actuel_d = $this->stockInitial;
        $declinaison->save();

        $this->declinaisons = Declinaison::where('shop_article_id', $this->articleId)->get();
        $shopArticle = Shop_article::find($this->articleId);
        $shopArticle->updateInitialStock();
        session()->flash('success','declinaison bien été créé');
        $this->declinationName="" ;
        $this->stockInitial ="" ;
    } 
    public function edit($declinaisonID) {
        $this->EditedecID=$declinaisonID;
        $declinaison = Declinaison::find($declinaisonID);
        $this->NewdeclinationName=$declinaison->libelle ;
        $this->NewstockInitial =$declinaison->stock_ini_d ;
        $this->NewstockActual = $declinaison->stock_actuel_d;
        
    }
    public function reload() {
        $this->EditedecID=0;
    }
    public function SaveEdit($declinaisonID) {
        $this->EditedecID=0;
        $declinaison = Declinaison::find($declinaisonID);
        
        if ($declinaison) {
            $declinaison->libelle = $this->NewdeclinationName;
            $declinaison->stock_ini_d = $this->NewstockInitial;
            $declinaison->stock_actuel_d = $this->NewstockActual;
            $declinaison->save();
            $article = Shop_article::find($declinaison->shop_article_id);
            $article->updateInitialStock();
            $this->declinaisons = Declinaison::where('shop_article_id', $this->articleId)->get();
        }
    }

    public function delete($declinaisonID) {
        
        $declinaison = Declinaison::find($declinaisonID);
        $article = Shop_article::find($declinaison->shop_article_id);
        if ($declinaison) {
            $declinaison->delete();
            $article->updateInitialStock();
            $this->declinaisons = Declinaison::where('shop_article_id', $this->articleId)->get();
        }
    }

    public function render()
    {
        return view('livewire.declinaison-articletype2');
    }
}
