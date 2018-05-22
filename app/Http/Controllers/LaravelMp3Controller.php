<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\LaravelMp3;

class LaravelMp3Controller extends Controller
{
    public function sandbox(){
    	$laravelMp3 = new LaravelMp3();
    	$laravelMp3 = $laravelMp3->load('Icy.mp3');
    	dd($laravelMp3->setTags([
    		'title'=> 'Cold',

    	]));
    }
}
