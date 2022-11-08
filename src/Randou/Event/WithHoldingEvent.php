<?php

declare(strict_types=1);

namespace Randou\Event;

use Randou\Constant\RdConstant;

class WithHoldingEvent extends Event
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
        $this->data['credits'] = intval($this->origin['credits']);
        $this->data['orderNo'] = $this->origin['orderNo'];
        $this->data['created_at'] = $this->origin['created_at'];
        $this->data['type'] = $this->origin['type'];
        $this->data['description'] = $this->origin['description'];
        $this->data['subsidy_fee'] = intval($this->origin['subsidy_fee']);
        $this->data['ip'] = $this->origin['ip'];

        if ($this->data['type'] === RdConstant::WITHHOLDING_TYPE_REDEEM) {
            $this->data['redeem_detail'] = \json_decode($this->origin['redeem_detail'], true);
        }
    }
}