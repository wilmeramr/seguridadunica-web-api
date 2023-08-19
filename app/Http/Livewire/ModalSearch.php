<?php

namespace App\Http\Livewire;

use App\Models\Product;
//use App\Traits\CartTrait;
use Livewire\Component;
use App\Models\User;
use App\Models\Lote;
class ModalSearch extends Component
{
    //use CartTrait;

    public $search, $Users = [];

    protected $paginationTheme = 'bootstrap';

    public function liveSearch()
    {

        if (strlen($this->search) > 0) {


                $us_lote_id = \Auth::user()->us_lote_id;
                $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
                $lotes = Lote::where('lot_country_id','=',$lote->lot_country_id)->select('lot_id')->get();

                  $this->Users = User::join('lotes as lt','lt.lot_id','users.us_lote_id')
                                ->select('users.id','users.us_name','lt.lot_name','lt.lot_country_id')
                                ->whereIn('us_lote_id',$lotes)
                                ->where('email', '<>', '')
                                ->where( function($query) {
                                    $query->where('us_name', 'like', "%{$this->search}%")
                                    ->orWhere('lot_name', 'like', "%{$this->search}%");
                              })
                                ->orderBy('us_name','asc')
                                ->get()->take(10);


        } else {
            return $this->Users = [];
        }
    }


    public function render()
    {
        $this->liveSearch();

        return view('livewire.modalsearch.component');
    }


    public function addAll()
    {
        if (count($this->products) > 0) {
            foreach ($this->products  as $product) {
                $this->emit('scan-code-byid', $product->id);
            }
        }
    }


}
