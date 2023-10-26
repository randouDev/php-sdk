<?php

declare(strict_types=1);

namespace Randou\Event;

//use Randou\Constant\RdConstant;

class CreditsNotifyEvent extends Event
{
    const SUCCESS = 'success';
    const FAIL = 'fail';

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
        $this->data['bizNo'] = $this->origin['bizNo'];
        $this->data['status'] = $this->origin['status'];

        if (self::FAIL === $this->data['status']) {
            $this->data['message'] = $this->origin['message'];
        } else {
            $this->data['message'] = '';
        }
    }
}