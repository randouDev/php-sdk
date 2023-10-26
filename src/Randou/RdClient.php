<?php
declare(strict_types=1);

namespace Randou;

use Randou\Constant\OrderReviewConstants;
use Randou\Core\Util;
use Randou\Event\CreditsIssueEvent;
use Randou\Event\CreditsNotifyEvent;
use Randou\Event\WithHoldingEvent;
use Randou\Exception\RdException;
use Randou\Http\RequestCore;
use Randou\Http\RequestCore_Exception;
use Randou\Http\ResponseCore;
use Randou\Result\FetchMallUrlResult;
use Randou\Result\OrderResult;
use Randou\Verification\Verification;

/**
 * 燃豆客户端
 */
class RdClient
{
    const GUEST = 'guest';
    const VERSION = '1.2.0';
    const URI_VERSION = 'v1';

    /**
     * @var RdKeys
     */
    private $rdKeys;
    /**
     * @var Options
     */
    private $options;

    /**
     * @var Verification
     */
    private $verification = null;

    /**
     * @var string
     */
    private $base_url = 'https://openapi.randou-tech.com';

    /**
     * @param RdKeys $rdKeys
     * @param Options $options
     * @throws RdException
     */
    public function __construct(RdKeys $rdKeys, Options $options)
    {
        $this->rdKeys = $rdKeys;
        $this->options = $options;
        self::checkEnv();
    }

    /**
     * @return Verification
     */
    private function getVerification(): Verification
    {
        if (empty($this->verification) || $this->verification->getAppId() !== $this->rdKeys->getAppid()) {
            $this->verification = new Verification($this->rdKeys->getAppid(), $this->rdKeys->getAppsecret());
        }

        return $this->verification;
    }

    /**
     * @param RdKeys $rdKeys
     * @return void
     */
    public function setRdKeys(RdKeys $rdKeys)
    {
        $this->rdKeys = $rdKeys;
    }

    /**
     * @param Options $options
     */
    public function setOptions(Options $options)
    {
        $this->options = $options;
    }

    /**
     * @return void
     * @throws RdException
     */
    private static function checkEnv()
    {
        if (function_exists('get_loaded_extensions')) {
            //Test curl extension
            $enabled_extension = array("curl");
            $extensions = get_loaded_extensions();
            if ($extensions) {
                foreach ($enabled_extension as $item) {
                    if (!in_array($item, $extensions)) {
                        throw new RdException("Extension {" . $item . "} is not installed or not enabled, please check your php env.");
                    }
                }
            } else {
                throw new RdException("function get_loaded_extensions not found.");
            }
        } else {
            throw new RdException('Function get_loaded_extensions has been disabled, please check php config.');
        }
    }

//    /**
//     * @param $debug
//     * @return void
//     */
//    public function setDebug($debug)
//    {
//        $this->debug = $debug;
//    }

    /**
     * Fetch mall login url
     *
     * @param $options
     * @return FetchMallUrlResult
     * @throws RdException
     */
    public function fetchMallDst($options): FetchMallUrlResult
    {
        if (!is_array($options)) {
            throw new RdException('invalid params, options has to be an array!');
        }
        if (empty($options['uid']) || empty($options['mall_no']) || !isset($options['credits'])) {
            throw new RdException('invalid params, there is no needed key');
        }
        $params = array(
            'uid'      => trim($options['uid']),
            'mall_no'  => trim($options['mall_no']),
            'credits'  => (int)$options['credits'],
            'grade'    => empty($options['grade']) ? 1 : (int)$options['grade'],
            'redirect' => empty($options['redirect']) ? '/' : $options['redirect'],
//            'nonce_str' => Util::random(),
//            'timestamp' => time(),
//            'appid'     => $this->rdKeys->getAppid(),
        );
        $params = array_merge($params, $this->getCommonParams());

        $params['sign'] = Util::sign($params, $this->rdKeys->getAppsecret());

        $response = $this->send(sprintf("/%s%s", self::URI_VERSION, '/autoLogin'), $params);
        return new FetchMallUrlResult($response);
    }

    /**
     * common params
     * @return array
     */
    private function getCommonParams(): array
    {
        return array(
            'nonce_str' => Util::random(),
            'timestamp' => time(),
            'appid'     => $this->rdKeys->getAppid(),
        );
    }

    /**
     * order review
     *
     * @param $options
     * @return OrderResult
     * @throws RdException
     */
    public function review($options): OrderResult
    {
        if (!is_array($options)) {
            throw new RdException('invalid params, options has to be an array!');
        }
        if (empty($options['pass']) && !in_array($options, [OrderReviewConstants::PASS, OrderReviewConstants::UNPASS])) {
            throw new RdException('invalid pass');
        }
        if (empty($options['orderNo']) && empty($options['bizNo'])) {
            throw new RdException('invalid params, order is empty');
        }

        $params = array('pass' => (int)$options['pass']);
        if (!empty($options['orderNo'])) {
            $params['orderNo'] = $options['orderNo'];
        }
        if (!empty($options['bizNo'])) {
            $params['bizNo'] = $options['bizNo'];
        }
        if (OrderReviewConstants::UNPASS === (int)$options['pass']) {
            if (isset($options['reason_type'])) {
                $params['reason_type'] = (int)$options['reason_type'];
            }
            if (isset($options['reason_display'])) {
                $params['reason_display'] = (int)$options['reason_display'];
            }
            if (!empty($options['reason_detail'])) {
                $params['reason_detail'] = $options['reason_detail'];
            }
        }
        $params = array_merge($params, $this->getCommonParams());
        $params['sign'] = Util::sign($params, $this->rdKeys->getAppsecret());

        $response = $this->send(sprintf("/%s%s", self::URI_VERSION, '/order/review'), $params);
        return new OrderResult($response);
    }


    /**
     * order review
     *
     * @param $options
     * @return OrderResult
     * @throws RdException
     */
    public function shipping($options): OrderResult
    {
        if (!is_array($options)) {
            throw new RdException('invalid params, options has to be an array!');
        }
        if (empty($options['shipping_company']) || empty($options['shipping_no'])) {
            throw new RdException('invalid params, no shipping info');
        }
        if (empty($options['orderNo']) && empty($options['bizNo'])) {
            throw new RdException('invalid params, order is empty');
        }

        $params = array('shipping_company' => $options['shipping_company'], 'shipping_no' => $options['shipping_no']);
        if (!empty($options['orderNo'])) {
            $params['orderNo'] = $options['orderNo'];
        }
        if (!empty($options['bizNo'])) {
            $params['bizNo'] = $options['bizNo'];
        }

        $params = array_merge($params, $this->getCommonParams());
        $params['sign'] = Util::sign($params, $this->rdKeys->getAppsecret());

        $response = $this->send(sprintf("/%s%s", self::URI_VERSION, '/order/shipping'), $params);
        return new OrderResult($response);
    }

    /**
     * cancel order shipping
     *
     * @param $options
     * @return OrderResult
     * @throws RdException
     */
    public function shippingCancel($options): OrderResult
    {
        if (!is_array($options)) {
            throw new RdException('invalid params, options has to be an array!');
        }
        if (empty($options['orderNo']) && empty($options['bizNo'])) {
            throw new RdException('invalid params, order is empty');
        }

        $params = array();
        if (!empty($options['orderNo'])) {
            $params['orderNo'] = $options['orderNo'];
        }
        if (!empty($options['bizNo'])) {
            $params['bizNo'] = $options['bizNo'];
        }

        $params = array_merge($params, $this->getCommonParams());
        $params['sign'] = Util::sign($params, $this->rdKeys->getAppsecret());

        $response = $this->send(sprintf("/%s%s", self::URI_VERSION, '/order/cancel-shipping'), $params);
        return new OrderResult($response);
    }


    /**
     * 积分预扣校验
     *
     * @param array $params
     * @return WithHoldingEvent
     * @throws RdException
     */
    public function withHolding(array $params): WithHoldingEvent
    {
        $message = $this->getVerification()->verify($params);
        if (!$message->success()) {
            throw new RdException('verified failed');
        }

        return new WithHoldingEvent($params);
    }

    /**
     * 增加积分校验
     *
     * @param array $params
     * @return CreditsIssueEvent
     * @throws RdException
     */
    public function creditsIssue(array $params): CreditsIssueEvent
    {
        $message = $this->getVerification()->verify($params);
        if (!$message->success()) {
            throw new RdException('verified failed');
        }

        return new CreditsIssueEvent($params);
    }


    /**
     * 回调通知校验
     *
     * @param array $params
     * @return CreditsNotifyEvent
     * @throws RdException
     */
    public function creditsNotify(array $params): CreditsNotifyEvent
    {
        $message = $this->getVerification()->verify($params);
        if (!$message->success()) {
            throw new RdException('verified failed');
        }

        return new CreditsNotifyEvent($params);
    }

    /**
     * Send GET method request
     *
     * @param string $uri
     * @param array $params
     * @return ResponseCore
     * @throws RdException
     */
    private function send(string $uri, array $params): ResponseCore
    {
//        $url = sprintf("%s%s?%s", $this->debug ? $this->base_url_debug : $this->base_url, $uri, http_build_query($params));
        $url = sprintf("%s%s?%s", $this->base_url, $uri, http_build_query($params));
        $request = new RequestCore($url);
        $request->set_useragent($this->generateUserAgent());
        $request->timeout = $this->options->getTimeout();
        $request->connect_timeout = $this->options->getConnectTimeout();

        try {
            $request->send_request();
        } catch (RequestCore_Exception $e) {
            throw new RdException('Request Fail: ' . $e->getMessage());
        }
        return new ResponseCore($request->get_response_header(), $request->get_response_body(), $request->get_response_code());
    }

    /**
     * Generates UserAgent
     *
     * @return string
     */
    private function generateUserAgent(): string
    {
        return "Randou PHP SDK/" . self::VERSION . " (" . php_uname('s') . "/" . php_uname('r') . "/" . php_uname('m') . ";" . \phpversion() . ")";
    }

}