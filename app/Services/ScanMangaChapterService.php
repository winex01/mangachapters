<?php

namespace App\Services;

use Goutte\Client;
use App\Models\Manga;
use App\Events\NewChapterScanned;
use Illuminate\Support\Facades\Log;


class ScanMangaChapterService
{
    protected $manga;

    protected $client;

    public function __construct(Manga $manga)
    {
        $this->manga = $manga;
        
        $this->client = new Client();
    }

    public function scan()
    {
        $newChapters = [];
        
        $firstEverMangaChapter = false;

        foreach ($this->manga->sources as $source) {
            // escape source that are not published
            if (!$source->published){
                continue;
            }

            // if this is the first ever scan chapter of this manga, then exit loop to all sources
            // add only 1 chapter.
            if ($firstEverMangaChapter) {
                break;
            }

            // get my current chapter, check last chapter entries of that manga_id, if no data then save only the first links
            $currentChapter = $this->manga->latestChapter;

            $crawler = $this->client->request('GET', $source->url);
            $links = $crawler->filter($source->scanFilter->filter)->links();

            // web crawled website links
            foreach ($links as $link) {
                $data = $this->prepareData($this->manga->id, $link->getUri(), $source->url);
                if (is_numeric($data['chapter'])) {
                    // manga has no chapters yet, then after saving the latest chapter then exist loop.
                    if ($currentChapter == null) {
                        $newChapters[] = modelInstance('Chapter')->create($data);
                        $firstEverMangaChapter = true;
                        break;
                    }else {
                        if ($currentChapter->chapter < $data['chapter']) {
                            $duplicate = modelInstance('Chapter')
                                        ->where('chapter', $data['chapter'])
                                        ->where('manga_id', $data['manga_id'])
                                        ->notInvalidLink()
                                        ->first();
                            
                            if (!$duplicate) {
                                $isNewChapter = modelInstance('Chapter')->firstOrCreate($data);

                                if ($isNewChapter->wasRecentlyCreated) {
                                    $newChapters[] = $isNewChapter;
                                }
                            }
                        }else {
                            break; // add this break so it will exit the foreach if no latest chapters found
                        }
                    }
                }else {
                    Log::error($data);
                    // TODO:: if not numeric find a way for comparison, check this: https://stackoverflow.com/questions/14288534/php-compare-alphabet-position
                }
            }// loop links
        }// loop sources


        // if has new manga chapters then triggered event and do the listeners
        if (!empty($newChapters)) {            
            foreach ($newChapters as $chapter) {
                event(new NewChapterScanned($chapter));
            }
        }

        return $newChapters;
    }

    private function prepareData($mangaId, $scrapUrl, $sourceUrl)
    {
        
        // support mangaraw.pro
        if ( stringContains($sourceUrl, 'mangaraw.pro') ) {
            $chapter = explode('chapter-', $scrapUrl);

            if ( is_array($chapter) && count($chapter) == 2 ) {
                $chapter = $chapter[1];
            }
        }
         
        $chapter = str_replace($sourceUrl, '', $scrapUrl);
        $chapter = str_replace('chapter-', '', $chapter);
        $chapter = str_replace('chapter_', '', $chapter);
        $chapter = str_replace('/', '', $chapter);

        // support decimal chapters ex. 1.1
        $chapter = str_replace('-', '.', $chapter);
        
        return [
            'manga_id' => $mangaId,
            'chapter' => $chapter,
            'url' => $scrapUrl,
        ];
    }

}