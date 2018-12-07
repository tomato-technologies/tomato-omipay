<?php

namespace Tomato\OmiPay\Requests;

use Illuminate\Support\Facades\Config;

/**
 *
 *  Omipay Data Class
 *  数据类
 *
 */
class OmiRequest
{
    protected $parameters = array();

    protected $serectKey = array();

    protected $receivedData = array();

    protected $bodyValues = array();

    public function __construct(){
        if(empty($m=Config::get("omipay.merchant_no")))
            throw new \Exception("Please config 'OMIPAY_MERCHANT_NO'");
        $this->setMerchant_no($m);

        if(empty($s=Config::get("omipay.merchant_key")))
            throw new \Exception("Please config 'OMIPAY_MERCHANT_KEY'");
        $this->setSercretKey($s);

        $this->setPlatform();
    }

    /**
     * 卡支付设置token_id
     * @param $value
     * @return mixed
     */
    public function setTokenId($value)
    {
        $this->parameters['token_id'] = $value;
        return $this->parameters['token_id'];
    }

    public function getTokenId()
    {
//        $val = !empty($this->parameters['token_id']) ? $this->parameters['token_id'] : null;
        return $this->parameters['token_id'];
    }

    /**
     * 设置商户号
     * @param string $value
     *
     * @return mixed
     */
    public function setMerchantNo($value)
    {
        $this->parameters['m_number'] = $value;
        return $this->parameters['m_number'];
    }

    /**
     * 获取商户号
     * @return string 值
     **/
    public function getMerchantNo()
    {
        // echo $this->parameters['m_number'];
        // $get = $this->parameters['m_number'];
        $val = !empty($this->parameters['m_number']) ? $this->parameters['m_number'] : null;
        return $val;
        // if($this->parameters['m_number']){
        //     return $this->parameters['m_number'];
        // }
    }


    /**
     * 设置商户密钥
     * @param string $value
     *
     * @return mixed
     */
    public function setSercretKey($value)
    {
        $this->serectKey['SECRET_KEY'] = $value;
        return $this->serectKey['SECRET_KEY'];
    }

    /**
     * 获取商户密钥
     * @return string 值
     **/
    public function getSercretKey()
    {
        // return $this->parameters['SECRET_KEY'];
        $val = !empty($this->serectKey['SECRET_KEY']) ? $this->serectKey['SECRET_KEY'] : null;
        return $val;
    }

    /**
     * 用于生成签名的随机字符串
     * @param string $value
     **/
    public function setNonceStr($value)
    {
        $this->parameters['nonce_str'] = $value;
    }

    /**
     * 用于生成签名的随机字符串
     * @return string nonce_str
     **/
    public function getNonceStr()
    {
        return $this->parameters['nonce_str'];
    }

    /**
     * 设置时间戳
     * @param int $value
     **/
    public function setTime($value)
    {
        $this->parameters['timestamp'] = $value;
    }

    /**
     * 获取时间戳
     * @return string 值
     **/
    public function getTime()
    {
        return $this->parameters['timestamp'];
    }

    /**
     * 设置签名
     * @return string 签名字符串
     * @internal param string $value
     */
    public function setSign()
    {
        $sign = $this->makeSign();
        $this->parameters['sign'] = $sign;
        return $sign;
    }

    /**
     * 获取签名
     * @return string 值
     **/
    public function getSign()
    {
        return $this->parameters['sign'];
    }

    /**
     * 格式化参数格式化成url参数
     */
    public function toQueryParameters()
    {
        $queryString = "";

        foreach ($this->parameters as $key => $value) {
            if ($value != "" && !is_array($value)) {
                $queryString .=  $key . '=' . urlencode($value) . '&';
            }
        }
        $queryString = (trim($queryString, "&"));
        return $queryString;
    }

    /**
     * 格式化参数格式化成json参数
     */
    public function toBodyParams()
    {
        return json_encode($this->bodyValues);
    }

    /**
     *商户号
     * @param $value
     */
    public function setMerchant_no($value)
    {
        $this->parameters['m_number'] = $value;
        // return $this->parameters['m_number'];
    }

    public function getMerchant_no()
    {
        return $this->parameters['m_number'];

    }

    /**
     * 生成签名
     * @return string 签名字符串
     */
    public function makeSign()
    {
        $originString = $this->getMerchantNo(). '&' . $this->getTime() . '&' . $this->getNonceStr() . "&" . $this->getSercretKey();
        $sign = strtoupper(md5($originString));
        return $sign;
    }

    /**
     * 获取设置的query参数值
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    public function getReceivedData()
    {
        return $this->receivedData;
    }

    /**
     * 设置的platform参数值
     * @param string $plat
     */
    public function setPlatform($plat = "WECHATPAY")
    {
        $value = $plat;
        // echo $value."<br>";
        //判断是不是微信
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            $value = 'WECHATPAY';
            // break;
        }elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false) {
            $value = 'ALIPAY';
            // break;
        }
        $this->parameters['platform'] = $value;
        // echo $value.'<br>';
    }

    public function getPlatform()
    {
        return $this->parameters['platform'];
    }

    /**
     * 设置的getNonceStr参数值
     * @param int $length
     * @return string
     */
    public static function getNonceStrPublic($length = 30)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 设置的getMillisecond参数值
     */
    public static function getMillisecondPublic()
    {
        //获取毫秒的时间戳
        $time = explode(" ", microtime());
        $time1 =explode(".", $time[0] * 1000);//  $time[0] * 1000;
        if($time1[0] < 10){
            $time2 = $time[1] ."00".$time1[0];
        }else if($time1[0] < 100){
            $time2 = $time[1] ."0".$time1[0];

        }else{
            $time2 = $time[1].$time1[0];
        }
        return $time2;
    }

}
