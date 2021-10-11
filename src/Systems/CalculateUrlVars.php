<?php

class CalculateUrlVars {
    static function createParamString($d)
    {
        $str = '';
        foreach($d as $key => $z)
        {
            $str .= $key.'='.$z.';';
        }

        return $str;
    }

    static function getParamsString($urlVars, $currentVar, $delVars = [])
    {
        $d = $urlVars;

        $d[$currentVar] = !isset($d[$currentVar]) || $d[$currentVar] == 'desc' ? 'asc' : 'desc';

        foreach($delVars as $item)
        {
            unset($d[$item]);
        }

        return self::createParamString($d);
    }
}