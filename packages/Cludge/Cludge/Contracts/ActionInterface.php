<?php
namespace Cludge\Contracts;

/**
 * Actions are a way to define things that can happen when a url hits a slug.
 *
 * Interface ActionInterface
 * @package Cludge\Contracts
 */

interface ActionInterface
{
    //TODO: docs.
    public function handle($action, $namespace, $content, $slug);

}