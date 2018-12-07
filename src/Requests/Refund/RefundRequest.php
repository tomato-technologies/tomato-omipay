<?php

namespace Tomato\OmiPay\Requests\Refund;

use Tomato\OmiPay\Requests\OmiRequest;
/**
 * 退款申请对象
 */
class RefundRequest extends OmiRequest
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
     * 获取Omipay订单编号
     * @return mixed 值
     **/
    public function getOrderNo()
    {
        return $this->parameters['order_no'];
    }

    /**
     * 设置外部退款单号
     * @param string $value
     **/
    public function setOutRefundNo($value)
    {
        $this->parameters['out_refund_no'] = $value;
    }

    /**
     * 获取外部退款单号
     * @return mixed 值
     **/
    public function getOutRefundNo()
    {
        return $this->parameters['out_refund_no'];
    }

    /**
     * 设置退款金额，货币类型为订单货币类型。单位是货币最小单位
     * @param string $value
     **/
    public function setAmount($value)
    {
        $this->parameters['amount'] = $value;
    }

    /**
     * 获取退款金额
     * @return mixed 值
     **/
    public function getAmount()
    {
        return $this->parameters['amount'];
    }
}