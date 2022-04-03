<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait BookmarkToggleOperation
{
    protected $bookmarkToggleButton = 'custom_bookmark_toggle';

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupBookmarkToggleRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/bookmarkToggle', [
            'as'        => $routeName.'.bookmarkToggle',
            'uses'      => $controller.'@bookmarkToggle',
            'operation' => 'bookmarkToggle',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupBookmarkToggleDefaults()
    {
        $this->crud->allowAccess('bookmarkToggle');

        $this->crud->operation('bookmarkToggle', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButtonFromView('line', 'bookmarkToggle', $this->bookmarkToggleButton, 'beginning');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function bookmarkToggle($id)
    {
        $this->crud->hasAccessOrFail('bookmarkToggle');

        $id = $this->crud->getCurrentEntryId() ?? $id;

        if ($id) {
            $manga = modelInstance('Manga')->find($id);

            auth()->user()->toggleBookmark($manga);
            
            return true;
        }

        return;
    }
}
