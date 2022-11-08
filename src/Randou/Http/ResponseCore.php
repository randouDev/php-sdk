<?php

namespace Randou\Http;

/**
 * Container for all response-related methods.
 */
class ResponseCore
{
    /**
     * Store the HTTP header information.
     */
    public $header;

    /**
     * Store the SimpleXML response.
     */
    public $body;

    /**
     * Store the HTTP response code.
     */
    public $status;

    /**
     * Construct a new instance of this class.
     *
     * @param array $header
     * @param string $body
     * @param integer $status
     * @return ResponseCore
     */
    public function __construct($header, $body, $status = null)
    {
        $this->header = $header;
        $this->body = $body;
        $this->status = $status;

        return $this;
    }

    /**
     * Did we receive the status code we expected?
     *
     * @param integer|array $codes (Optional) The status code(s) to expect. Pass an <php:integer> for a single acceptable value, or an <php:array> of integers for multiple acceptable values.
     * @return boolean Whether we received the expected status code or not.
     */
    public function isOK($codes = array(200, 201, 204, 206))
    {
        if (is_array($codes)) {
            return in_array($this->status, $codes);
        }

        return $this->status === $codes;
    }
}