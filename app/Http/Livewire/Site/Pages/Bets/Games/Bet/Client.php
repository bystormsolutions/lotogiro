<?php

namespace App\Http\Livewire\Site\Pages\Bets\Games\Bet;

use App\Http\Controllers\Site\Pages\Bets\GameController;
use Livewire\Component;

class Client extends Component
{
    public $bet, $typeGames, $cpf, $name, $last_name, $pix, $phone;

    protected $rules = [
        'name' => 'required|max:50',
        'last_name' => 'required|max:100',
        'cpf' => 'required|max:11',
        'pix' => 'required',
        'phone' => 'required'
    ];

    public function mount($bet, $typeGames)
    {
        $this->bet = $bet;
        $this->typeGames = $typeGames;
    }

    public function updatedCpf($value)
    {
        $client = $this->searchClient($value);
        if (!empty($client)) {
            $this->name = $client->name;
            $this->last_name = $client->last_name;
            $this->pix = $client->pix;
            $this->phone = $client->ddd.$client->phone;
        }
    }

    public function searchClient($cpf)
    {
        $client = \App\Models\Client::where('cpf', $cpf)->first();

        return $client;
    }

    public function submit()
    {
        $data = $this->validate();

        try {
            $client = $this->searchClient($data['cpf']);
            if (empty($client)) {
                $client = new \App\Models\Client();
            }

            $client->cpf = $data['cpf'];
            $client->name = $data['name'];
            $client->last_name = $data['last_name'];
            $client->pix = $data['pix'];
            $client->ddd = substr($data['phone'], 0, 2);
            $client->phone = substr($data['phone'], 2);
            $client->save();

            (new GameController())->setClient($this->bet, $client->id);

            session()->flash('success', 'Cliente cadastrado com sucesso!');
            return redirect()->route('games.bet', ['user' => $this->bet->user_id, 'bet' => $this->bet]);

        } catch (\Exception $exception) {
            session()->flash('error', config('app.env') != 'production' ? $exception->getMessage() : 'Ocorreu um erro no processo!');
            return redirect()->route('games.bet', ['user' => $this->bet->user_id, 'bet' => $this->bet]);
        }

    }

    public function render()
    {
        return view('livewire.site.pages.bets.games.bet.client');
    }
}
