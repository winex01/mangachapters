@php
	$userCount = modelInstance('User')->count();
    $userMilestone = 0;
    
    // increase milestone to 1k every time overpassed
    do {
        $userMilestone += 1000;
    } while ($userCount > $userMilestone);

    
	$chapter = modelInstance('Chapter');
	$chapterCount = $chapter->count(); 

    $lastChapter = $chapter->latest()->first();
	$lastScanChapter = null;	

	if ($lastChapter) {
		$lastScanChapter = $lastChapter->created_at->diffForHumans();
	}

    $mangaCount = modelInstance('Manga')->count();
	$mangaMilestone = 0;

	do {
		$mangaMilestone += 50;
	} while ($mangaCount > $mangaMilestone);


	// progressed value
	$chapterProgressed = 0;
	$chapterHint = null;
	if ($chapterCount) {
		$chapterProgressed = $chapterCount / $mangaCount;
		$chapaterHint = number_format($chapterCount / $mangaCount, 2);
	} 

    Widget::add()->to('before_content')->type('div')->class('row')->content([
		Widget::make()
			->type('progress')
			->class('card border-0 text-white bg-info my-widgets')
			->progressClass('progress-bar')
			->value(number_format($userCount))
			->description('Registered users.')
			->progress(100*(int)$userCount/$userMilestone)
			->hint(number_format($userMilestone-$userCount).' more until next milestone.'),

		Widget::make()
            ->type('progress')
            ->class('card border-0 text-white bg-success my-widgets')
            ->value(number_format($chapterCount))
            ->progressClass('progress-bar')
            ->description('Total combine chapters.')
            ->progress($chapterProgressed)
            ->hint($chapterHint. ' average manga chapters.'),

		// if you prefer defining your widgets as arrays
	    Widget::make([
			'type' => 'progress',
			'class'=> 'card border-0 text-white bg-dark my-widgets',
			'progressClass' => 'progress-bar',
			'value' => number_format($mangaCount),
			'description' => 'Mangas/Manhwa/Manhua.',
			'progress' => (int)$mangaCount/75*100,
			'hint' => number_format($mangaMilestone-$mangaCount).' more until next milestone.',
		]),

        Widget::make()
            ->type('progress')
            ->class('card border-0 text-white bg-warning my-widgets')
            ->value($lastScanChapter)
            ->progressClass('progress-bar')
            ->description('Last scanned chapter.')
            ->progress(0)
            ->hint('Scan chapter every 30 minutes.'),
	]);

@endphp

{{-- In case widgets have been added to a 'content' group, show those widgets. --}}
@include(backpack_view('inc.widgets'), [ 'widgets' => app('widgets')->where('group', 'content')->toArray() ])   