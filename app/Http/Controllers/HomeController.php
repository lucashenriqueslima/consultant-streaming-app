<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages;
use App\Models\Galleries;
use App\Models\Configs;

class HomeController extends Controller
{
    public $config;
    public $categorias;

    function __construct()
    {
        $configs = Configs::get();
        $this->config = [];
        foreach($configs as $key => $value) {
            $this->config[$configs[$key]['key']] = $value->value;
        }

        $this->categorias = Pages::get();
    }

    public function index()
    {
        $trilha = Pages::first();
        $trilhas = Galleries::where('parent_id', $trilha->id)->get();
        $categorias = $this->categorias;
        return view('welcome', compact(['categorias','trilhas','trilha']));
    }

    public function trilha(Request $request, $url) {
        $trilha = Pages::where('url', $url)->first();
        $trilhas = Galleries::where('parent_id', $trilha->id)->get();
        $categorias = $this->categorias;
        return view('categoria', compact(['categorias','trilha','trilhas']));
    }

    public function termos(Request $request) {
        $page = Pages::where('url', $request->segment(2))->first();
        $categorias = $this->categorias;
        $config = $this->config;
        return view('termos', compact(['categorias','page','config']));
    }

    public function privacidade(Request $request) {
        $page = Pages::where('url', $request->segment(2))->first();
        $categorias = $this->categorias;
        $config = $this->config;
        return view('privacidade', compact(['categorias','page','config']));
    }

    public function contato(Request $request) {
        $page = Pages::where('url', $request->segment(2))->first();
        $categorias = $this->categorias;
        return view('contato', compact(['categorias','page']));
    }

    public function busca(Request $request) {
        $trilhas = Galleries::where([
            ['title','like', '%'.$request->get('search').'%']
        ])->get();

        $search = $request->get('search');

        $categorias = $this->categorias;
        return view('busca', compact(['trilhas','search','categorias']));
    }
}
