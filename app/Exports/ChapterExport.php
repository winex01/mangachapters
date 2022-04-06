<?php

namespace App\Exports;

use App\Exports\BaseExport;

class ChapterExport extends BaseExport
{
    /**
     * add ons order
     */
    protected function orderByAddOns()
    {
        $this->query->orderByRelease();
    }
}
