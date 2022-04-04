<?php

namespace App\Http\Controllers\Admin\Operations\Chapter;

use Goutte\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendNewChapterNotification;

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
        $newChapters = [];
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

                // debug($currentChapter);

                $crawler = $client->request('GET', $source->url);
                $links = $crawler->filter($source->scanFilter->filter)->links();

                // web crawled website links
                foreach ($links as $link) {
                    $data = $this->prepareData($manga->id, $link->getUri(), $source->url);
                    if (is_numeric($data['chapter'])) {
                        // manga has no chapters yet, then after saving the latest chapter then exist loop.
                        if ($currentChapter == null) {
                            $newChapters[] = modelInstance('Chapter')->create($data);
                            break;
                        }else {
                            if ($currentChapter->chapter < $data['chapter']) {
                                $firstOrCreate = modelInstance('Chapter')->firstOrCreate($data);
                                
                                if($firstOrCreate->wasRecentlyCreated) {
                                    $newChapters[] = $firstOrCreate;
                                }
                            }else {
                                break; // add this break so i will exit the foreach if no latest chapters found
                            }
                        }
                    }else {
                        Log::error($data);
                        $failMangas[] = $data;

                        // TODO:: if not numeric find a way for comparison, check this: https://stackoverflow.com/questions/14288534/php-compare-alphabet-position
                    }
                }// loop links
            }// loop sources
        }// loop manga


        if (!empty($newChapters)) {
            // debug($newChapters);
            
            foreach ($newChapters as $chapter) {
                $bookmarkedByUsers = $chapter->manga->bookmarkers;
                
                // TODO:: events
                    // NewChapterScanned event

                Notification::send($bookmarkedByUsers, new SendNewChapterNotification($chapter));
            
            }
        }// end if !empty $newChapters

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