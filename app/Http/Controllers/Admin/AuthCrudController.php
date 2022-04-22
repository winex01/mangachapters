<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Route;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class AuthCrudController extends CrudController
{
    protected function setupModerateRoutes($segment, $routeName, $controller) {
        Route::get($segment.'/about', [
            'as'        => $routeName.'.about',
            'uses'      => $controller.'@about',
            'operation' => 'about',
        ]);

        Route::get($segment.'/terms', [
            'as'        => $routeName.'.terms',
            'uses'      => $controller.'@terms',
            'operation' => 'terms',
        ]);

        Route::get($segment.'/contact', [
            'as'        => $routeName.'.contact',
            'uses'      => $controller.'@contact',
            'operation' => 'contact',
        ]);
    }

    public function about()
    {
        return view('backpack::crud.custom_about');
    }

    public function terms()
    {
        return view('backpack::crud.custom_terms');
    }

    public function contact()
    {
        return view('backpack::crud.custom_contact');
    }
    
}
