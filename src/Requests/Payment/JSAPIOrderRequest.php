<?php

namespace Tomato\OmiPay\Requests\Payment;

/**
 * 创建JSAPI订单对象
 */
class JSAPIOrderRequest extends OmiOrderRequest
{
    /**
     * 设置是否直接支付
     * @param string $value
     **/
    public function setDirectPay($value)
    {
        $this->parameters['direct_pay'] = $value;
    }

    /**
     * 获取是否直接支付
     * @return mixed 值
     **/
    public function getDirectPay()
    {
        return $this->parameters['direct_pay'];
    }

    /**
     * 判断直接支付是否存在
     * @return true 或 false
     **/
    public function isDirectPaySet()
    {
        return array_key_exists('direct_pay', $this->parameters);
    }

    /**
     * 设置是否PC支付
     * @param string $value
     **/
    public function setPcPay($value)
    {
        $this->parameters['show_pc_pay_url'] = $value;
    }

    /**
     * 获取是否PC支付
     * @return mixed 值
     **/
    public function getPcPay()
    {
        return $this->parameters['show_pc_pay_url'];
    }

}