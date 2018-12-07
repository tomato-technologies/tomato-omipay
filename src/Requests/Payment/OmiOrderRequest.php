<?php

namespace Tomato\OmiPay\Requests\Payment;

use Tomato\OmiPay\Requests\OmiRequest;
/**
 * 创建订单对象基类
 */
class OmiOrderRequest extends OmiRequest
{

    /**
     * 设置订单名称
     * @param string $value
     **/
    public function setOrderName($value)
    {
        $this->parameters['order_name'] = $value;
    }

    /**
     * 获取订单名称
     **/
    public function getOrderName()
    {
        return $this->parameters['order_name'];
    }

    /**
     * 设置外部订单号
     * @param string $value
     **/
    public function setOutOrderNo($value)
    {
        $this->parameters['out_order_no'] = $value;
    }

    /**
     * 获取外部订单号
     **/
    public function getOutOrderNo()
    {
        return $this->parameters['out_order_no'];
    }

    /**
     * 设置订单金额，单位为当前货币最小单位
     * @param string $value
     **/
    public function setAmount($value)
    {
        $this->parameters['amount'] = $value;
    }

    /**
     * 获取订单金额
     * @return float 值
     **/
    public function getAmount()
    {
        return $this->parameters['amount'];
    }

    /**
     * 设置支付通知url,该URL需要为安全域名下。
     * @param string $value
     **/
    public function setNotifyUrl($value)
    {
        $this->parameters['notify_url'] = $value;
    }

    /**
     * 获取支付通知url
     * @return string 值
     **/
    public function getNotifyUrl()
    {
        return $this->parameters['notify_url'];
    }

    /**
     * 设置支付成功后跳转页面
     * @param string $value
     **/
    public function setRedirect($value)
    {
        $this->parameters['redirect'] = $value;
    }

    /**
     * 设置跳转地址
     * @param string $value
     **/
    public function setRedirectUrl($value)
    {
        $this->parameters['redirect_url'] = $value;
    }

    /**
     * 获取跳转地址
     * @return string 值
     **/
    public function getRedirectUrl()
    {
        return $this->parameters['redirect_url'];
    }

    /**
     * 设置货币类型
     * 允许值: AUD, CNY
     * @param string $value
     **/
    public function setCurrency($value)
    {
        $this->parameters['currency'] = $value;
    }

    /**
     * 获取货币类型
     **/
    public function getCurrency()
    {
        return $this->parameters['currency'];
    }


    /**
     * 指定下单门店编号
     * @param string $value
     **/
    public function setONumber($value)
    {
        $this->parameters['o_number'] = $value;
    }

    /**
     * 获取指定下单门店编号
     **/
    public function getONumber()
    {
        return $this->parameters['o_number'];
    }

    /**
     * 设置POS机编号
     * @param string $value
     **/
    public function setPOSNo($value)
    {
        $this->parameters['pos_no'] = $value;
    }

    /**
     * 获取POS机编号
     * @return string 值
     **/
    public function getPOSNo()
    {
        return $this->parameters['pos_no'];
    }

}