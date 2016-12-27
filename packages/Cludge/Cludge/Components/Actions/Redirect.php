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
class Redirect implements ActionInterface
{
    public function handle($action, $namespace, $content, $slug)
    {
        //TODO: might need extra stuff in here for different routes etc..
        //maybe I could do other redirect types for named routes?
        return redirect()->to($action);
    }
}