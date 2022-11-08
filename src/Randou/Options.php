<?php

declare(strict_types=1);

namespace Randou;

class Options
{
    public $timeout = 10;

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @return int
     */
    public function getConnectTimeout(): int
    {
        return $this->connect_timeout;
    }

    /**
     * @param int $connect_timeout
     */
    public function setConnectTimeout(int $connect_timeout)
    {
        $this->connect_timeout = $connect_timeout;
    }

    public $connect_timeout = 10;


    public function __construct()
    {
    }

    public static function defaultOptions(): Options
    {
        return new Options();
    }

}