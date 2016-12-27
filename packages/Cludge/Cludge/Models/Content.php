<?php

namespace Cludge\Models;

use Cludge\Models\Traits\DynamicHasMany;
use Cludge\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use Sluggable;
    use DynamicHasMany;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'content';
    
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function content_type()
    {
        return $this->belongsTo(ContentType::class, 'content_type_id', 'id');
    }

    public function data($table){
        return $this->dynHasMany($table);
    }

}