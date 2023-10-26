<?php
declare(strict_types=1);

namespace Randou\Result;

use Randou\Exception\RdException;

class OrderResult extends Result
{

    /**
     * Parse data from response
     *
     * @return array
     * @throws RdException
     */
    protected function parseDataFromResponse(): array
    {
        $content = $this->rawResponse->body;
        if (empty($content)) {
            throw new RdException("body is null");
        }
        $data = \json_decode($content, true);
        if (empty($data['orderNo']) || empty($data['bizNo'])) {
            throw new RdException('invalid dataSet');
        }
        return $data;
    }
}
