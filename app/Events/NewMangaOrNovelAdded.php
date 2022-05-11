<?php

namespace App\Events;

use App\Models\Manga;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class NewMangaOrNovelAdded
{
    use Dispatchable, SerializesModels;

    public $manga;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Manga $manga)
    {
        $this->manga = $manga;
    }
}
