<?php
declare(strict_types=1);

namespace Randou;

/**
 * 保存键值对
 */
class RdKeys
{
    private $appid = '';

    private $appsecret = '';


    public function __construct(string $appid, string $appsecret)
    {
        $this->appid = $appid;
        $this->appsecret = $appsecret;
    }

    /**
     * @return string
     */
    public function getAppid(): string
    {
        return $this->appid;
    }

    /**
     * @return string
     */
    public function getAppsecret(): string
    {
        return $this->appsecret;
    }
}