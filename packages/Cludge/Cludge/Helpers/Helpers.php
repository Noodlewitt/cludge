<?php
/**
 * Created by PhpStorm.
 * User: sidavies
 * Date: 8/12/2016
 * Time: 8:57 PM
 */

namespace Cludge\Helpers;


class Helpers
{

    /**
     * get cludge base path
     * @param null $file
     * @return string
     */
    public function base_path($file = NULL){
       return __DIR__.'/../'.$file;
    }
}