<?php

namespace Cludge\Models\Traits;

use Cludge\Models\Slug;

trait Sluggable
{

    public function slug()
    {
        return $this->morphOne(Slug::class);
    }


}