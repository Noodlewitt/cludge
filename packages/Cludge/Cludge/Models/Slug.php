<?php

namespace Cludge\Models;

use Illuminate\Database\Eloquent\Model;

class Slug extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'slugs';

    public function slugged()
    {
        return $this->morphTo('name', 'slug_type', 'slug_type_id');
    }

}