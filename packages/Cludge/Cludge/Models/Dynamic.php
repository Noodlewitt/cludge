<?php

namespace Cludge\Models;

use Illuminate\Database\Eloquent\Model;

class Dynamic extends Model
{

    /**
     * @param $table
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Dynamically set a model's table.
     *
     * @param  $table
     * @return void
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

}