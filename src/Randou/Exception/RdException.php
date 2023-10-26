<?php
declare(strict_types=1);

namespace Randou\Exception;

class RdException extends \Exception
{
    /** The error message */
    protected $message = 'unknown error';

    /** The error code */
    protected $code = 9999;

    private $details = array();

    function __construct($details)
    {
        if (is_array($details)) {
//            $message = $details['code'] . ': ' . $details['error'];
//            parent::__construct($message);
            parent::__construct($details['error'], $details['code']);
            $this->details = $details;
        } else {
//            $message = $details;
            parent::__construct($details);
        }
    }

//    public function getHTTPStatus()
//    {
//        return $this->details['http_status'] ?? '';
//    }

    public function getErrorCode()
    {
        return $this->details['code'] ?? '';
    }

    public function getErrorMessage()
    {
        return $this->details['error'] ?? '';
    }

    public function getDetails(): array
    {
        return $this->details;
    }

}