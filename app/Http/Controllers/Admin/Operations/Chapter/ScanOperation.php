<?php

namespace App\Http\Controllers\Admin\Operations\Chapter;

use Goutte\Client;
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

        $error = false;
        $failMangas = [];
        $client = new Client();

        // loop all mangas
        $mangas = modelInstance('Manga')->all();

        foreach ($mangas as $manga) {
            foreach (json_decode($manga->sources) as $source) {
                // get my current chapter, check last chapter entries of that manga_id, if no data then save only the first links
                // reQuery every source to avoid duplicate
                $currentChapter = modelInstance('Chapter')
                                    ->withoutGlobalScope('CurrentChapterScope')
                                    ->where('manga_id', $manga->id)
                                    ->first();
                
                $crawler = $client->request('GET', $source->url);
                $links = $crawler->filter($source->crawler_filter)->links();

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
                                    ->withoutGlobalScope('CurrentChapterScope')
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
                    }else {
                        $failMangas[] = $data;
                    }
                    
                }// loop links
            }// loop sources
        }// loop manga


        return compact('error', 'failMangas');
    }

    private function prepareData($mangaId, $crawUrl, $sourceUrl)
    {
        if ( stringContains($sourceUrl, 'www.test.com') ) {
            $test = '';
        }else {
            $chapter = str_replace($sourceUrl, '', $crawUrl);
            $chapter = str_replace('/', '', $chapter);
            $chapter = str_replace('chapter-', '', $chapter);
            
            // support decimal chapters ex. 1.1
            $chapter = str_replace('-', '.', $chapter);
        }

        return [
            'manga_id' => $mangaId,
            'chapter' => $chapter,
            'url' => $crawUrl,
        ];
    }
}
