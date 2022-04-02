<?php

namespace App\Http\Controllers\Admin\Operations\Chapter;

use Goutte\Client;
use Illuminate\Support\Facades\Log;
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

        $client = new Client();
        $error = false;
        $failMangas = [];
        $ids = request()->ids;

        // query selected manga
        if ($ids) {
            // debug('if');
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
        
        // debug($mangas);
        
        // loop all mangas
        foreach ($mangas as $manga) {
            foreach ($manga->sources as $source) {
                // get my current chapter, check last chapter entries of that manga_id, if no data then save only the first links
                $currentChapter = modelInstance('Chapter')
                                    ->where('manga_id', $manga->id)
                                    ->orderBy('chapter', 'desc')
                                    ->orderBy('created_at', 'desc')
                                    ->first();

                $crawler = $client->request('GET', $source->url);
                $links = $crawler->filter($source->scanFilter->filter)->links();

                // web crawled website links
                foreach ($links as $link) {
                    $data = $this->prepareData($manga->id, $link->getUri(), $source->url);
                    if (is_numeric($data['chapter'])) {
                        // manga has no chapters yet, then after saving the latest chapter then exist loop.
                        if ($currentChapter == null) {
                            modelInstance('Chapter')->create($data);
                            break;
                        }else {
                            if ($currentChapter->chapter < $data['chapter']) {
                                $count = modelInstance('Chapter')
                                    ->where('manga_id', $manga->id)
                                    ->where('chapter', $data['chapter'])
                                    ->count();

                                // avoid duplicate
                                if ($count == 0) {
                                    modelInstance('Chapter')->firstOrCreate($data);
                                }

                            }else {
                                break; // add this break so i will exit the foreach if no latest chapters found
                            }
                        }

                        // TODO:: add pivot bookmarks here
                    }else {
                        Log::error($data);
                        $failMangas[] = $data;
                    }
                    
                }// loop links
            }// loop sources
        }// loop manga


        return compact('error', 'failMangas');
    }

    private function prepareData($mangaId, $scrapUrl, $sourceUrl)
    {

        // support mangaraw.pro
        if ( stringContains($sourceUrl, 'mangaraw.pro') ) {
            $chapter = explode('chapter-', $scrapUrl);

            if ( is_array($chapter) && count($chapter) == 2 ) {
                $chapter = $chapter[1];
            }

            $chapter = str_replace('/', '', $chapter);

            // support decimal chapters ex. 1.1
            $chapter = str_replace('-', '.', $chapter);

            // debug($chapter);

        }else { // universal
            $chapter = str_replace($sourceUrl, '', $scrapUrl);
            $chapter = str_replace('/', '', $chapter);
            $chapter = str_replace('chapter-', '', $chapter);
            
            // support decimal chapters ex. 1.1
            $chapter = str_replace('-', '.', $chapter);
        }

        return [
            'manga_id' => $mangaId,
            'chapter' => $chapter,
            'url' => $scrapUrl,
        ];
    }
}
