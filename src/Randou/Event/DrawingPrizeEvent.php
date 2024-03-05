<?php

declare(strict_types=1);

namespace Randou\Event;

use Randou\Constant\BooleanConstant;
use Randou\Constant\RdConstant;
use Randou\Exception\RdException;

class DrawingPrizeEvent extends Event
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
        if (!in_array($this->origin['hit'], [BooleanConstant::YES, BooleanConstant::NO])) {
            throw new RdException('hit field is illegal');
        }
        $this->data['uid'] = $this->origin['uid'];
        $this->data['mall_no'] = $this->origin['mall_no'];
        $this->data['credits'] = intval($this->origin['credits']);
        $this->data['serialNo'] = $this->origin['serialNo'];
        $this->data['created_at'] = $this->origin['created_at'];
        $this->data['drawinggame_detail'] = \json_decode($this->origin['drawinggame_detail'], true);
        $this->data['hit'] = $this->origin['hit'];

        if ($this->isHit()) {
            if (!in_array($this->origin['receive'], [BooleanConstant::YES, BooleanConstant::NO])) {
                throw new RdException('isReceive field is illegal');
            }
            $this->data['receive'] = $this->origin['receive'];

            $this->data['prize'] = \json_decode($this->origin['prize'], true);

//            if ($this->isReceive() && $this->isGoodsPrize()) {
//                if (!in_array($this->origin['order_result'], [self::FAIL, self::SUCCESS])) {
//                    throw new RdException('prize.isCreditsPrize field is illegal');
//                }
//                $this->data['order_result'] = $this->origin['order_result'];
//            }
        }
    }

    /**
     * 是否抽中
     * @return bool
     */
    public function isHit(): bool
    {
        return BooleanConstant::YES === $this->data['hit'];
    }

    /**
     * 是否领奖
     * @return bool
     */
    public function isReceive(): bool
    {
        if (!$this->isHit()) {
            return false;
        }

        return BooleanConstant::YES === $this->data['receive'];
    }

    /**
     * 是否积分类奖品
     * @return bool
     */
    public function isCreditsPrize(): bool
    {
        if (!$this->isHit()) {
            return false;
        }

//        return BooleanConstant::YES === $this->data['prize']['isCreditsPrize'];
        return 'CREDITS' === $this->data['prize']['type'];
    }

    /**
     * 是否商品类奖品
     * @return bool
     */
    public function isGoodsPrize(): bool
    {
        if (!$this->isHit()) {
            return false;
        }

//        return BooleanConstant::YES === $this->data['prize']['isGoodsPrize'];
        return in_array($this->data['prize']['type'], ['GOODS_MATERIAL', 'GOODS_COUPON', 'GOODS_CHARGE']);
    }

    /**
     * 是否再抽一次奖品
     * @return bool
     */
    public function isFreeDrawPrize(): bool
    {
        if (!$this->isHit()) {
            return false;
        }

        return 'FREE_DRAW' === $this->data['prize']['type'];
    }

    /**
     * 获取商品类奖品
     * @return array
     */
    public function getGoodsPrize(): array
    {
        if (!$this->isReceive() || !$this->isGoodsPrize()) {
            return [];
        }

        return $this->data['prize']['goods'];
    }

    /**
     * 获取抽得的积分
     * @return int|null
     */
    public function getCreditsGain(): int
    {
        if (!$this->isReceive() || !$this->isCreditsPrize()) {
            return 0;
        }

        return intval($this->data['prize']['credits_gain']);
    }

    /**
     * 获取奖品
     * @return array
     */
    public function getPrize(): array
    {
        if (!$this->isHit()) {
            return [];
        }

        return $this->data['prize'];
    }

    /**
     * 商品类奖品订单是否成功
     * @return bool
     */
    public function isPrizeGoodsOrderSuccess(): bool
    {
        if (!$this->isGoodsPrize() || !$this->isReceive()) {
            return false;
        }

        return self::SUCCESS === $this->data['prize']['order_result'];
    }
}
