<?php
declare(strict_types=1);

namespace Randou;

class RdClientBuilder
{
    /**
     * 获取客户端
     *
     * @param string $appid
     * @param string $appsecret
     * @return RdClient
     * @throws Exception\RdException
     */
    public static function getClient(string $appid, string $appsecret): RdClient
    {
        return self::getClientByKey(new RdKeys($appid, $appsecret));
    }

    /**
     * 获取客户端
     *
     * @param RdKeys $rdKeys
     * @param Options|null $options
     * @return RdClient
     * @throws Exception\RdException
     */
    public static function getClientByKey(RdKeys $rdKeys, Options $options = null): RdClient
    {
        if (null === $options) {
            $options = Options::defaultOptions();
        }
        return new RdClient($rdKeys, $options);
    }
}