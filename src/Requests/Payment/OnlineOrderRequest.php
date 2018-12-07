<?php

namespace Tomato\OmiPay\Requests\Payment;

/**
 * 创建onlineOrder订单对象
 */
class OnlineOrderRequest extends OmiOrderRequest
{
    /**
     * 设置支付类型
     * @param string $value
     **/
    public function setPayType($value)
    {
        $this->parameters['type'] = $value;
    }

    /**
     * 获取支付类型
     * @return mixed 值
     **/
    public function getPayType()
    {
        return $this->parameters['type'];
    }

    /**
     * 设置支付类型
     * @param string $value
     **/
    public function setReturnUrl($value)
    {
        $this->parameters['return_url'] = $value;
    }

    /**
     * 获取支付类型
     * @return mixed 值
     **/
    public function getReturnUrl()
    {
        return $this->parameters['return_url'];
    }

}