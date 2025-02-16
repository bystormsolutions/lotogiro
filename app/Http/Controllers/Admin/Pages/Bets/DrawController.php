<?php

namespace App\Http\Controllers\Admin\Pages\Bets;

use App\Http\Controllers\Controller;
use App\Models\Draw;
use App\Models\Game;
use App\Models\TypeGame;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DrawController extends Controller
{
    protected $draw;

    public function __construct(Draw $draw)
    {
        $this->draw = $draw;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('read_draw')){
            abort(403);
        }

        if ($request->ajax()) {
            $draw = $this->draw->get();
            return DataTables::of($draw)
                ->addIndexColumn()
                ->addColumn('action', function ($draw) {
                    $data = '';
                    if(auth()->user()->hasPermissionTo('update_draw')){
                        $data .= '<a href="' . route('admin.bets.draws.show', ['draw' => $draw->id]) . '">
                        <button class="btn btn-sm btn-warning" title="Editar"><i class="far fa-edit"></i></button>
                    </a>';
                    }
                    if(auth()->user()->hasPermissionTo('delete_draw')) {
                        $data .= '<button class="btn btn-sm btn-danger" id="btn_delete_draw" draw="' . $draw->id . '" title="Deletar" data-toggle="modal" data-target="#modal_delete_draw"> <i class="far fa-trash-alt"></i></button>';
                    }
                    return $data;
                })
                ->addColumn('type_game', function ($draw) {
                    return $draw->typeGame->name;
                })
                ->addColumn('competition', function ($draw) {
                    return $draw->competition->number;
                })
                ->editColumn('created_at', function ($draw) {
                    return Carbon::parse($draw->created_at)->format('d/m/Y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.pages.bets.draw.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->hasPermissionTo('create_draw')){
            abort(403);
        }

        $typeGames = TypeGame::get();

        return view('admin.pages.bets.draw.create', compact('typeGames'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Draw  $draw
     * @return \Illuminate\Http\Response
     */
    public function show(Draw $draw)
    {
        $gamesDraw = explode(',', $draw->games);
        $games = Game::whereIn('id', $gamesDraw)->get();

        return view('admin.pages.bets.draw.read', compact('draw', 'games'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Draw  $draw
     * @return \Illuminate\Http\Response
     */
    public function edit(Draw $draw)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Draw  $draw
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Draw $draw)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Draw  $draw
     * @return \Illuminate\Http\Response
     */
    public function destroy(Draw $draw)
    {
        if(!auth()->user()->hasPermissionTo('delete_draw')){
            abort(403);
        }

        try {
            $draw->delete();

            return redirect()->route('admin.bets.draws.index')->withErrors([
                'success' => 'Sorteio deletado com sucesso'
            ]);

        } catch (\Exception $exception) {

            return redirect()->route('admin.bets.draws.index')->withErrors([
                'error' => config('app.env') != 'production' ? $exception->getMessage() : 'Ocorreu um erro ao deletar o sorteio, tente novamente'
            ]);

        }
    }
}
