<?php

declare(strict_types=1);

namespace Randou\Event;

class CreditsIssueEvent extends Event
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
        $this->data['unique_no'] = $this->origin['unique_no'];
        $this->data['created_at'] = $this->origin['created_at'];
        $this->data['type'] = $this->origin['type'];
        $this->data['description'] = $this->origin['description'];
        $this->data['ip'] = $this->origin['ip'];
    }
}