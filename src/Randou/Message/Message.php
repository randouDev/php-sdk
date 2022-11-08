<?php
declare(strict_types=1);

namespace Randou\Message;

class Message
{
    /**
     * @var bool
     */
    private $ok = false;

    /**
     * @var
     */
    private $data = [];

    private function __construct(bool $ok = false, array $data = [])
    {
        $this->ok = $ok;
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function success(): bool
    {
        return $this->ok;
    }

    /**
     * @return Message
     */
    public static function getFailMsg(): Message
    {
        return new Message();
    }

    /**
     * @param $data
     * @return Message
     */
    public static function getSuccessMsg($data): Message
    {
        return new Message(true, $data);
    }
}