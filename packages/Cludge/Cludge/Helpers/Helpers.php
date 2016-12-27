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

    /**
     * remove input issues from string
     * @param $in
     */
    public function clean($in){
        $out = trim($in, ' \\/\'\"'); //remove some weird charactors
        return $out;
    }
}