<?php

namespace App\Services;

use Goutte\Client;
use App\Models\Manga;
use App\Events\NewChapterScanned;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpClient\HttpClient;


class ScanMangaChapterService
{
    protected $manga;

    protected $client;

    protected $proxy;

    public function __construct(Manga $manga)
    {
        $this->manga = $manga;
        
        // $this->client = new Client();
        $this->client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
    
        $this->proxy = new GetProxyService();
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

            $proxy = 'http://'.$this->proxy->random();

            try {
                // dump($proxy);
                $crawler = $this->client->request('GET', $source->url, ['proxy' => $proxy]);
                $links = $crawler->filter($source->scanFilter->filter)->links();

            } catch (\Exception $e) {
                Log::warning('INVALID SOURCE', (array)$e);
                continue; // if source url is error then go to the next loop/source
            }

            // send logs if source is no longer working
            if (!$links || empty($links)) {
                $tempLog = [
                    'manga' => backpack_url('manga/'.$this->manga->id.'/show'),
                    'invalid_url' => $source->url,
                ];

                Log::warning('INVALID SOURCE', $tempLog);
            }


            $tempScanChapters = [];
            // web crawled website links
            foreach ($links as $link) {
                $data = $this->prepareData($this->manga->id, $link->getUri(), $source->url);

                if (!$data) {
                    continue;
                }

                // manga has no chapters yet, then after saving the latest chapter then exit loop.
                if ($currentChapter == null) {
                    // $newChapters[] = modelInstance('Chapter')->create($data);
                    $tempScanChapters[] = $data;
                    $firstEverMangaChapter = true; // this is use if manga has multiple source and if it's his first chapter then dont scan the rest of sources
                    break;
                }else {
                    if (is_numeric($data['chapter']) && is_numeric($currentChapter->chapter)) {
                        if ($currentChapter->chapter < $data['chapter']) {
                            $duplicate = modelInstance('Chapter')
                                        ->where('chapter', $data['chapter'])
                                        ->where('manga_id', $data['manga_id'])
                                        ->notInvalidLink()
                                        ->first();
                            
                            if (!$duplicate) {
                                $tempScanChapters[] = $data;
                            }
                        }else {
                            break; // add this break so it will exit the foreach if no latest chapters found
                        }
                    }else {
                        // not numeric
                        $duplicate = modelInstance('Chapter')
                                        ->where('chapter', $data['chapter'])
                                        ->where('manga_id', $data['manga_id'])
                                        ->notInvalidLink()
                                        ->first();
                        if (!$duplicate) {
                            $tempScanChapters[] = $data;
                        }

                        break; // add this break so it will only insert 1 non numeric chapter. bec. non numeric chapter is seldom
                    }
                }// end if currentChapter == null


            }// loop links


            if (!empty($tempScanChapters)) {

                // laravel collection reverse array sort
                $tempScanChapters = collect($tempScanChapters)->reverse()->toArray();
    
                $tempArrayChapters = [];
                foreach ($tempScanChapters as $tempScan) {

                    // if it has chapter already in database 
                    if (!$firstEverMangaChapter) {

                        $tempCurrentChapter = $currentChapter->chapter;
                        if (is_numeric($tempScan['chapter']) && is_numeric($tempCurrentChapter)) {
                            if ($tempCurrentChapter < $tempScan['chapter']) {
                                
                                $difference = abs($tempScan['chapter'] - $tempCurrentChapter);
                                
                                if ($difference > 0 && $difference <= 1){
                                    $tempArrayChapters[] = $tempScan;
                                    $tempCurrentChapter = $tempScan['chapter'];
                                }
                            }
                        }// end if is_numeric
                    }else {
                         // if first ever chapter to be inserted in DB
                        $tempArrayChapters[] = $tempScan;
                    
                    }// end if $firstEverMangaChapter
    
                }// end foreach $tempScanChapters
    

                // insert chapters to DB
                foreach ($tempArrayChapters as $tempArrayChapter) {
    
                    $isNewChapter = modelInstance('Chapter')->firstOrCreate($tempArrayChapter);
    
                    if ($isNewChapter->wasRecentlyCreated) {
                        $newChapters[] = $isNewChapter;
                    }
    
                }// foreach $tempArrayChapters

            }// end !empty $tempScanChapters

        }// loop sources


        // if has new manga chapters then triggered event and do the listeners
        if (!empty($newChapters)) {            
            foreach ($newChapters as $chapter) {
                event(new NewChapterScanned($chapter));
            }
        }

        return $newChapters;
    }

    public function prepareData($mangaId, $scrapUrl, $sourceUrl)
    {
        //only allow links that has `chapter-` in readlightnovel.me website
  		if (stringContains($scrapUrl, 'www.readlightnovel.me')) {
			if (!stringContains($scrapUrl, 'chapter-')) {
				return;
			}
		}


        if (stringContains($scrapUrl, 'chapter-')) {
            // dash
            $chapter = explode('chapter-', $scrapUrl);
            $chapter = $chapter[1];
            
            // NOTE:: comment for now forgot what this for .ahahah
            // preg_match('/(\d+(\.\d+)?)/',$chapter, $temp);
		  	
		  	//if (!empty($temp)) {
				// $chapter = $temp[1];	
			// }
            
        }elseif (stringContains($scrapUrl, 'chapter_')) {
            // underscore
            $chapter = explode('chapter_', $scrapUrl);
            $chapter = $chapter[1];
        }
        elseif (stringContains($scrapUrl, 'www.webtoons.com')) {
			$chapter = explode('episode-', $scrapUrl);
		  	$chapter = explode('/', $chapter[1]);
		  	$chapter = $chapter[0];
		}
        else {
            $chapter = str_replace($sourceUrl, '', $scrapUrl);
        }
        
        //Remove String in chapter below.

        // https://www.mangageko.com/
        $chapter = str_replace('-eng-li', '', $chapter);

        // filter chapter with html or html extention
        for($i = 1; $i <= 3; $i++) {
            $chapter = str_replace($i.'.html', '', $chapter);
            $chapter = str_replace($i.'.htm', '', $chapter);
        }

        $chapter = str_replace('/all-pages', '', $chapter);

        $chapter = str_replace('/', '', $chapter);
        
        // support decimal chapters ex. 1.1
        $chapter = str_replace('-', '.', $chapter);
        
        // support mangatown
        $chapter = str_replace('c', '', $chapter);
        
        $data = [
            'manga_id' => $mangaId,
            'chapter' => $chapter,
            'url' => $scrapUrl,
        ];

        // dump($data);

        return $data;
    }

}