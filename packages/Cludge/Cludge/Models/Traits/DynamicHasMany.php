<?php

namespace Cludge\Models\Traits;

use Cludge\Models\Dynamic;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait DynamicHasMany
{

    /**
     * Define a one-to-many relationship.
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dynHasMany($related, $foreignKey = null, $localKey = null)
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();

        //$instance = new $related;
        $instance = new Dynamic();
        $instance->setTable($related);

        $localKey = $localKey ?: $this->getKeyName();

        return new HasMany($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey);
    }

}