<?php

namespace AppBundle\Component\Helper;


class StringHelper
{
    /**
     * @param string $dirty
     * @return string
     */
    static function slugify(string $dirty) : string{
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $dirty);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
        return $clean;
    }
}