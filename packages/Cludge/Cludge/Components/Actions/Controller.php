<?php
namespace Cludge\Components\Actions;

use Cludge\Contracts\ActionInterface;

/**
 * The controller action routes you to a specific controller action, allowing
 * controller logic to be placed in the right place
 *
 * Class Controller
 * @package Cludge\Components\Actions
 */
class Controller implements ActionInterface
{
    public function handle($action, $namespace, $content, $slug)
    {
        $controller = Helpers::clean($action);
        $namespace = Helpers::clean($namespace);
        return App::call($namespace ? "$namespace\\$controller" : $controller);
    }
}