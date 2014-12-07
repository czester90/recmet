<?php
/**
 * Created by PhpStorm.
 * User: szwester
 * Date: 05/11/14
 * Time: 22:35
 */

namespace Library;


class HttpServiceCaller {

    public static function toAscii($str, $delimiter='-') {
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }
} 