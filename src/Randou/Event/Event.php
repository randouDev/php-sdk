<?php

declare(strict_types=1);

namespace Randou\Event;

abstract class Event
{
    /**
     * @var array
     */
    protected $origin = [];

    /**
     * @var array
     */
    protected $data = [];

    public function __construct(array $data)
    {
        $this->origin = $data;
        $this->map();
    }

    /**
     * 解析事件通知数据
     *
     * @return mixed
     */
    abstract public function map();

    /**
     * 获取所有数据
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }
}