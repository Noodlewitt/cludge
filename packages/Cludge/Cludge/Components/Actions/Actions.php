<?php
namespace Cludge\Components\Actions;

use Cludge\Models\Slug;

class Actions
{
    public $actions = [
        'controller'=>\Cludge\Components\Actions\Controller::class,
        'redirect'=>\Cludge\Components\Actions\Redirect::class,
        'view'=>\Cludge\Components\Actions\View::class,
    ];

    public function __construct()
    {

    }

    public function addActions($actions){
        $this->actions =  array_merge($this->actions, $actions);
    }

    /**
     * read in slug, return with the action.
     *
     * @param $slug
     * @param $item
     * @return mixed
     */
    public function runSlugAction($slug, $item){

        $action = $this->actions[$slug->action_type];

        $r = new \ReflectionClass($action );
        $instance =  $r->newInstanceWithoutConstructor();
        return $instance->handle($slug->action, $slug->namespace, $item, $slug);
    }
}