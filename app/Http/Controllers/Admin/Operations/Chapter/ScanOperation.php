<?php

namespace App\Http\Controllers\Admin\Operations\Chapter;

use App\Services\ScanMangaChapterService;
use Illuminate\Support\Facades\Route;

trait ScanOperation
{
    private $scanButton = 'chapters.custom_scan';

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupScanRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/scan', [
            'as'        => $routeName.'.scan',
            'uses'      => $controller.'@scan',
            'operation' => 'scan',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupScanDefaults()
    {
        $this->crud->allowAccess('scan');

        $this->crud->operation('scan', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation(['list', 'show'], function () {
            $this->crud->enableBulkActions();
            $this->crud->addButtonFromView('top', 'scan', $this->scanButton);
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function scan()
    {
        $this->crud->hasAccessOrFail('scan');
        
        $ids = request()->ids;
        $mangas = null;

        // query selected manga
        if ($ids) {
            $ids = modelInstance('Chapter')->whereIn('id', $ids)->pluck('manga_id')->all();
            $mangas = modelInstance('Manga')
                        ->has('sources')
                        ->with(['sources' => function ($query) {
                            $query->published();
                        }])
                        ->whereIn('id', $ids)
                        ->get();
        }else { // query all manga
            // debug('else');
            $mangas = modelInstance('Manga')
                        ->has('sources')
                        ->with(['sources' => function ($query) {
                            $query->published();
                        }])->get();
        }

        $newChapters = [];
        if ($mangas) {
            foreach ($mangas as $manga) {
                $temp = new ScanMangaChapterService($manga);
                $newChapters = $temp->scan(); 
            }
        }

        return $newChapters;
    }
    
}