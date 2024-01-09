<?php

declare(strict_types=1);

namespace Randou\Event;

class GoodsChargeQueryEvent extends Event
{

    /**
     * 解析事件通知数据
     *
     * @return void
     */
    public function map()
    {
        $this->data['orderNo'] = $this->origin['orderNo'];
        $this->data['chargeBizNo'] = $this->origin['chargeBizNo'];
    }
}
