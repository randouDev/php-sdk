<?php
declare(strict_types=1);

namespace Randou\Result;

use Randou\Exception\RdException;
use Randou\Http\ResponseCore;

abstract class Result
{
    /**
     * Indicate whether the request is successful
     */
    protected $isOk = false;
    /**
     * Data parsed by subclasses
     */
    protected $parsedData = null;
    /**
     * Store the original Response returned by the auth function
     *
     * @var ResponseCore
     */
    protected $rawResponse;

    /**
     * Result constructor.
     * @param $response ResponseCore
     * @throws RdException
     */
    public function __construct(ResponseCore $response)
    {
//        if ($response === null) {
//            throw new RdException("raw response is null");
//        }
        $this->rawResponse = $response;
        $this->parseResponse();
    }

    /**
     * Get the returned data, different request returns the data format is different
     *
     * $return mixed
     */
    public function getDataSet()
    {
        return $this->parsedData;
    }

    /**
     * Subclass implementation, different requests return data has different analytical logic, implemented by subclasses
     *
     * @return mixed
     */
    abstract protected function parseDataFromResponse();

    /**
     * Whether the operation is successful
     *
     * @return bool
     */
    public function isOK(): bool
    {
        return $this->isOk;
    }

    /**
     * @throws RdException
     */
    protected function parseResponse()
    {
        $this->isOk = $this->isResponseOk();
        if ($this->isOk()) {
            $this->parsedData = $this->parseDataFromResponse();
        } else {
            $httpStatus = strval($this->rawResponse->status);
            $body = \json_decode($this->rawResponse->body, true);
            $details = array(
                'http_status' => $httpStatus,
                'code'        => $body['code'] ?? 100000,
                'error'       => $body['error'] ?? '未知错误',
            );
            throw new RdException($details);
        }
    }

    /**
     * Judging from the return http status code, [200-299] that is OK
     *
     * @return bool
     */
    protected function isResponseOk(): bool
    {
        $status = $this->rawResponse->status;
        return 2 === (int)(intval($status) / 100);
    }

    /**
     * Return the original return data
     *
     * @return ResponseCore
     */
    public function getRawResponse(): ResponseCore
    {
        return $this->rawResponse;
    }


}