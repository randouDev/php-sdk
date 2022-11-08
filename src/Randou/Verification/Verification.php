<?php
declare(strict_types=1);

namespace Randou\Verification;

use Randou\Core\Util;
use Randou\Message\Message;

class Verification
{
    /**
     * @var string
     */
    private $appId = '';

    /**
     * @var string
     */
    private $appSecret = '';

    /**
     * @param string $appId
     * @param string $appSecret
     */
    public function __construct(string $appId, string $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    /**
     * @param array $params
     * @return Message
     */
    public function verify(array $params = []): Message
    {
        if (empty($params)) {
            $params = $_GET;
        }
        if (
            empty($params['appid']) ||
            empty($params['sign']) ||
            empty($params['timestamp']) ||
            empty($params['nonce_str'])
        ) {
            return Message::getFailMsg();
        }
        if ($params['appid'] !== $this->appId) {
            return Message::getFailMsg();
        }

        $sign = $params['sign'];
        unset($params['sign']);
        if (Util::sign($params, $this->appSecret) !== $sign) {
            return Message::getFailMsg();
        }
        unset($params['appid'], $params['sign'], $params['timestamp'], $params['nonce_str']);

        return Message::getSuccessMsg($params);
    }


    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * @return string
     */
    public function getAppSecret(): string
    {
        return $this->appSecret;
    }
}
