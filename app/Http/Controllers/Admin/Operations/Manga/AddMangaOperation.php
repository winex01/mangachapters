<?php

namespace App\Http\Controllers\Admin\Operations\Manga;

use Illuminate\Support\Facades\Route;

trait AddMangaOperation
{
    private $button = 'mangas.custom_add_manga';

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupAddMangaRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/addManga', [
            'as'        => $routeName.'.addManga',
            'uses'      => $controller.'@addManga',
            'operation' => 'addManga',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupAddMangaDefaults()
    {
        $this->crud->allowAccess('addManga');

        $this->crud->operation('addManga', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButtonFromView('top', 'addManga', $this->button);
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function addManga()
    {
        $this->crud->hasAccessOrFail('addManga');
        
        $url = request()->url;


        return true;
    }
}
