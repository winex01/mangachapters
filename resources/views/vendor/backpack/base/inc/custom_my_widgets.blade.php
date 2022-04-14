@php
	$userCount = modelInstance('User')->count();
    
	$chapter = modelInstance('Chapter');
	$chapterCount = $chapter->count(); 

    $lastChapter = $chapter->latest()->first();

	$mangaCount = modelInstance('Manga')->count();
	
	$lastScanChapter = null;	
	if ($lastChapter) {
		$lastScanChapter = $lastChapter->created_at->diffForHumans();
	}




    Widget::add()->to('before_content')->type('div')->class('row')->content([
		Widget::make()
			->type('progress')
			->class('card border-0 text-white bg-info my-widgets')
			->progressClass('progress-bar')
			->value(number_format($userCount))
			->description('Registered users.'),

		Widget::make()
            ->type('progress')
            ->class('card border-0 text-white bg-success my-widgets')
            ->value(number_format($chapterCount))
            ->progressClass('progress-bar')
            ->description('Total combine chapters.'),

		// if you prefer defining your widgets as arrays
	    Widget::make([
			'type' => 'progress',
			'class'=> 'card border-0 text-white bg-dark my-widgets',
			'progressClass' => 'progress-bar',
			'value' => number_format($mangaCount),
			'description' => 'Mangas/Manhwa/Manhua.',
		]),

        Widget::make()
            ->type('progress')
            ->class('card border-0 text-white bg-warning my-widgets')
            ->value($lastScanChapter)
            ->progressClass('progress-bar')
            ->description('Last scanned chapter.'),
	]);

@endphp

{{-- In case widgets have been added to a 'content' group, show those widgets. --}}
@include(backpack_view('inc.widgets'), [ 'widgets' => app('widgets')->where('group', 'content')->toArray() ])   