<?php

namespace Tomato\OmiPay\Requests\Payment;

/**
 * 微信小程序下单
 */
class WxMiniProgramOrderRequest extends OmiOrderRequest
{
    /**
     * appid
     * @param string $value
     **/
    public function setWxAppid($value)
    {
        $this->parameters['app_id'] = $value;
    }

    /**
     * customer_id  openid
     * @param $value
     */
    public function setCustomerId($value)
    {
        $this->parameters['customer_id'] = $value;
    }
}