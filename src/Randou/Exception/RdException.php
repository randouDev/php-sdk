<?php
declare(strict_types=1);

namespace Randou\Exception;

class RdException extends \Exception
{
    private $details = array();

    function __construct($details)
    {
        if (is_array($details)) {
            $message = $details['code'] . ': ' . $details['error'];
            parent::__construct($message);
            $this->details = $details;
        } else {
            $message = $details;
            parent::__construct($message);
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