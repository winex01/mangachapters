<?php

namespace App\Events;

use App\Models\Source;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class NewSourceAdded
{
    use Dispatchable, SerializesModels;

    public $source;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Source $source)
    {
        $this->source = $source;
    }
}
