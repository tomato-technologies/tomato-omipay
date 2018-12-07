<?php

namespace Tomato\OmiPay\Requests\Common;

use Tomato\OmiPay\Requests\OmiRequest;

/**
 *
 * 汇率查询输入对象
 * @author Leijid
 *
 */
class ExchangeRateRequest extends OmiRequest
{

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

    public function setBaseCurrency($value)
    {
        $this->parameters['base_currency'] = $value;
    }

    public function getBaseCurrency()
    {
        return $this->parameters['base_currency'];
    }


}