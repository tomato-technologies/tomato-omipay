<?php

namespace Tomato\OmiPay\Requests\Payment;

/**
 * 创建ScanOrder订单对象
 */
class ScanOrderRequest extends OmiOrderRequest
{
    /**
     * 设置付款码
     * @param string $value
     **/
    public function setQRCode($value)
    {
        $this->parameters['qrcode'] = $value;
    }

    /**
     * 获取付款码
     * @return mixed 值
     **/
    public function getQRCode()
    {
        return $this->parameters['qrcode'];
    }

}