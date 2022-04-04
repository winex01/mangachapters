<?php

namespace App\Events;

use App\Models\Chapter;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class NewChapterScanned
{
    use Dispatchable, SerializesModels;

    public $chapter;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Chapter $chapter)
    {
        $this->chapter = $chapter;
    }
}
