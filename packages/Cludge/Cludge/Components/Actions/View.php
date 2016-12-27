<?php
namespace Cludge\Components\Actions;

use Cludge\Contracts\ActionInterface;
use Cludge\Facades\Helpers;

/**
 * The view action routes to a specific view, for more simple actions.
 *
 * Class Controller
 * @package Cludge\Components\Actions
 */
class View implements ActionInterface
{
    public function handle($action, $namespace, $content, $slug)
    {
        $view = Helpers::clean($action);
        $namespace = Helpers::clean($namespace);
        return view($namespace ? "$namespace::$view" : $view, ['content'=>$content, 'slug'=>$slug]);
    }
}