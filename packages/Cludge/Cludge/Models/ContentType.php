<?php


namespace Cludge\Models;


use Illuminate\Database\Eloquent\Model;

class ContentType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'content_types';

    public function fields(){
        return $this->belongsToMany(Field::class, 'content_type_fields');
    }

    public function content(){
        return $this->hasMany(Content::class);
    }
}