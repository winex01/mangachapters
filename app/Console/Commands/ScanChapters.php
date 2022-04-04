<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ScanMangaChapterService;

class ScanChapters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'winex:scan-chapters
        {--mangaId= : Manga table id with comma(,) delimeter, Ex. 3,6,7}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Web scrap manga chapters from different sources.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ids = $this->option('mangaId');
        
        // query selected manga
        if ($ids) {
            $ids = explode(',', $ids);
            
            if (!is_array($ids)) {
                $ids = (array) $ids;
            }

            $mangas = modelInstance('Manga')
                        ->has('sources')
                        ->with(['sources' => function ($query) {
                            $query->with('scanFilter');
                            $query->published();
                        }])
                        ->with('latestChapter')
                        ->whereIn('id', $ids)
                        ->get();
        }else { // query all manga
            // debug('else');
            $mangas = modelInstance('Manga')
                        ->has('sources')
                        ->with(['sources' => function ($query) {
                            $query->with('scanFilter');
                            $query->published();
                        }])
                        ->with('latestChapter')
                        ->get();
        }

        if ($mangas->isNotEmpty()) {
            $this->getOutput()->progressStart(count($mangas)); // progress bar total

            foreach ($mangas as $manga) {
                $temp = new ScanMangaChapterService($manga);
                $temp->scan(); 

                $this->getOutput()->progressAdvance();
            }

            $this->getOutput()->progressFinish();
        }else {
            $this->info('Invalid Manga ID(s) specified.');
        }

    }
}
