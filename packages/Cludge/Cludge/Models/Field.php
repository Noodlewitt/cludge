<?php


namespace Cludge\Models;


use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fields';


    public function content_types(){
        return $this->belongsToMany(ContentType::class);
    }
}