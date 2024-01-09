<?php

declare(strict_types=1);

namespace Randou\Event;

class GoodsChargeEvent extends Event
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
        $this->data['orderNo'] = $this->origin['orderNo'];
        $this->data['product_no'] = $this->origin['product_no'];
        $this->data['description'] = $this->origin['description'];
        $this->data['bizNo'] = isset($this->origin['bizNo']) && !empty($this->origin['bizNo']) ? $this->origin['bizNo'] : '';
        $this->data['account'] = isset($this->origin['account']) && !empty($this->origin['account']) ? $this->origin['account'] : '';
    }

    /**
     * @return bool
     */
    public function hasBizNo(): bool
    {
        return !empty($this->data['bizNo']);
    }

    /**
     * @return bool
     */
    public function hasAccount(): bool
    {
        return !empty($this->data['account']);
    }
}
