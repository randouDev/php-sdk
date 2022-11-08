<?php
declare(strict_types=1);

namespace Randou\Core;

/**
 * @package Randou
 */
class Util
{
    /**
     * Generate a more truly "random" alpha-numeric string.
     * @param int $length
     *
     * @return string
     */
    public static function random(int $length = 20): string
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
            $bytes = random_bytes($size);
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    /**
     * Generate sign based params
     *
     * @param array $params
     * @param string $appSecret
     * @return string
     */
    public static function sign(array $params, string $appSecret): string
    {
        $open_str = self::sortAsc($params);
        $open_str .= "&app_secret=" . $appSecret;
        return md5($open_str);
    }


    /**
     * Sort a map
     *
     * @param array $params
     * @return string
     */
    public static function sortAsc(array $params): string
    {
        ksort($params);
        $str = '';
        foreach ($params as $k => $val) {
            if (!empty($val)) {
                $str .= $k . '=' . $val . '&';
            }
        }
        return trim($str, '&');
    }
}
