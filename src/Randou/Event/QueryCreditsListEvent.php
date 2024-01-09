<?php

declare(strict_types=1);

namespace Randou\Event;

class QueryCreditsListEvent extends Event
{

    /**
     * 解析事件通知数据
     *
     * @return void
     */
    public function map()
    {
        $this->data['uid'] = $this->origin['uid'];
        $this->data['mall_no'] = $this->origin['mall_no'];
        $this->data['page'] = intval($this->origin['page']);
        $this->data['pageSize'] = intval($this->origin['pageSize']);
    }
}
