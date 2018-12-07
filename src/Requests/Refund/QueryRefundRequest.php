<?php

namespace Tomato\OmiPay\Requests\Refund;

use Tomato\OmiPay\Requests\OmiRequest;
/**
 * 查询退款状态对象
 */
class QueryRefundRequest extends OmiRequest
{
    /**
     * 设置Omipay退款单号
     * @param string $value
     **/
    public function setRefundNo($value)
    {
        $this->parameters['refund_no'] = $value;
    }

    /**
     * 获取Omipay退款单号
     * @return mixed 值
     **/
    public function getRefundNo()
    {
        return $this->parameters['refund_no'];
    }
}