<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait BulkBookmarkOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupBulkBookmarkRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/bulkBookmark', [
            'as'        => $routeName.'.bulkBookmark',
            'uses'      => $controller.'@bulkBookmark',
            'operation' => 'bulkBookmark',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupBulkBookmarkDefaults()
    {
        $this->crud->allowAccess('bulkBookmark');

        $this->crud->operation('bulkBookmark', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation(['list', 'show'], function () {
            $this->crud->enableBulkActions();
            $this->crud->addButtonFromView('top', 'bulkBookmark', 'custom_bulk_bookmark', 'end');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function bulkBookmark()
    {
        $this->crud->hasAccessOrFail('bulkBookmark');

        $entries = request()->input('entries');

        $mangas = modelInstance('Manga')->whereIn('id', $entries)->get();

        if ($mangas) {
            foreach ($mangas as $manga) {
                auth()->user()->bookmark($manga);
            }

            return $mangas;
        }

        return;
    }
}
