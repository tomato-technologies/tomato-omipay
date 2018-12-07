<?php

namespace Tomato\OmiPay\Requests\Common;

use Tomato\OmiPay\Requests\OmiRequest;

/**
 * 查询订单对象
 */
class QueryOrderRequest extends OmiRequest
{
    /**
     * 设置Omipay订单编号
     * @param string $value
     **/
    public function setOrderNo($value)
    {
        $this->parameters['order_no'] = $value;
    }

    /**
     * 获取付款码
     * @return mixed 值
     **/
    public function getOrderNo()
    {
        return $this->parameters['order_no'];
    }
}