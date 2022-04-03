<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait BookmarkOperation
{
    protected $bookmarkButton = 'custom_bookmark';
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupBookmarkRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/bookmark', [
            'as'        => $routeName.'.bookmark',
            'uses'      => $controller.'@bookmark',
            'operation' => 'bookmark',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupBookmarkDefaults()
    {
        $this->crud->allowAccess('bookmark');

        $this->crud->operation('bookmark', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButtonFromView('line', 'bookmark', $this->bookmarkButton, 'beginning');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function bookmark($id)
    {
        $this->crud->hasAccessOrFail('bookmark');

        $id = $this->crud->getCurrentEntryId() ?? $id;

        if ($id) {
            $manga = modelInstance('Manga')->find($id);

            auth()->user()->bookmark($manga);
            
            return true;
        }

        return;
    }
}
