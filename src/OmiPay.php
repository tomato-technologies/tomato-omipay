<?php

namespace Tomato\OmiPay;

use Tomato\OmiPay\Exceptions\OmiPayException;
use Tomato\OmiPay\Requests\OmiRequest;
use Tomato\OmiPay\Events\GotNotification;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

/**
 *
 * 接口访问类，包含所有Omi支付API列表的封装，类中方法为static方法，
 *
 */
class OmiPay
{

    /**
     * The Laravel application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    public $baseURL;

    public $url;

    public function __construct($app = null)
    {
        if (!$app) {
            $app = app();
        }
        $this->app = $app;

        $this->setBaseURL();
    }

    protected function setBaseURL()
    {
        $url = Config::get("omipay.domain_cn");

        if (Config::get("omipay.domain_type") == 'AU') {
            $url = Config::get("omipay.domain");
        }

        $this->baseURL = $url . Config::get("omipay.api_version") . "/";
    }

    /**
     *
     * 汇率查询，nonce_str、time不需要填入
     * @param \Tomato\OmiPay\Requests\Common\ExchangeRateRequest $inputObj
     * @param int $timeOut
     * @throws \Tomato\OmiPay\Exceptions\OmiPayException
     * @return array
     */
    public function exchangeRate($inputObj, $timeOut = 30)
    {
        $this->setDomain("GetExchangeRate");
        return $this->getJsonCurl($inputObj, $timeOut);
    }

    /**
     *
     * 查询订单，nonce_str、time不需要填入
     * @param \Tomato\OmiPay\Requests\Common\QueryOrderRequest $inputObj
     * @param int $timeOut
     * @throws \Tomato\OmiPay\Exceptions\OmiPayException
     * @return array $result 成功时返回，其他抛异常
     */
    public function orderQuery($inputObj, $timeOut = 10)
    {
        $this->setDomain("QueryOrder");
        return $this->getJsonCurl($inputObj, $timeOut);
    }


    /**
     *
     * QR下单，nonce_str、time不需要填入
     * @param \Tomato\OmiPay\Requests\Payment\QROrderRequest $inputObj
     * @param int $timeOut
     * @return array
     */
    public function qrOrder($inputObj, $timeOut = 100)
    {
        $this->setDomain("MakeQROrder");
        return $this->getJsonCurl($inputObj, $timeOut);
    }

    /**
     *
     * JsApi下单，nonce_str、time不需要填入
     * @param \Tomato\OmiPay\Requests\Payment\JSAPIOrderRequest $inputObj
     * @param int $timeOut
     * @return array
     */
    public function jsApiOrder($inputObj, $timeOut = 10)
    {
        $this->setDomain("MakeJSAPIOrder");
        return $this->getJsonCurl($inputObj, $timeOut);
    }


    /**
     *
     * 生成线上订单
     * @param \Tomato\OmiPay\Requests\Payment\OnlineOrderRequest $inputObj
     * @param int $timeOut
     * @return mixed
     */
    public function onlineOrder($inputObj, $timeOut = 10)
    {
        $this->setDomain("MakeOnlineOrder");
        return $this->getJsonCurl($inputObj, $timeOut);
    }

    /**
     *
     * 生成扫描支付订单
     * @param \Tomato\OmiPay\Requests\Payment\ScanOrderRequest $inputObj
     * @param int $timeOut
     * @return mixed
     */
    public function scanOrder($inputObj, $timeOut = 10)
    {
        $this->setDomain("MakeScanOrder");
        return $this->getJsonCurl($inputObj, $timeOut);
    }

    /**
     * 微信小程序服务端下单
     * @param \Tomato\OmiPay\Requests\Payment\WxMiniProgramOrderRequest $inputObj
     * @param int $timeOut
     * @return mixed
     */
    public function wxMiniProgramOrder($inputObj, $timeOut = 100)
    {
        $this->setDomain("MakeAppletOrder");
        return $this->getJsonCurl($inputObj, $timeOut);
    }

    /**
     * 信用卡支付服务端
     * @param $inputObj
     * @param int $timeOut
     * @return mixed
     */
    public function checkout($inputObj, $timeOut = 100)
    {
        $this->setDomain("ConfirmCheckOut");
        return $this->getJsonCurl( $inputObj, $timeOut);
    }


    /**
     *
     * 申请退款，nonce_str、time不需要填入
     * @param \Tomato\OmiPay\Requests\Refund\RefundRequest $inputObj
     * @param int $timeOut
     * @throws \Tomato\OmiPay\Exceptions\OmiPayException
     * @return array
     */
    public function refund($inputObj, $timeOut = 10)
    {
        $this->setDomain("Refund");
        return $this->getJsonCurl($inputObj, $timeOut);
    }

    /**
     *
     * 查询退款状态，nonce_str、time不需要填入
     * @param \Tomato\OmiPay\Requests\Refund\QueryRefundRequest $inputObj
     * @param int $timeOut
     * @return array
     */
    public function refundQuery($inputObj, $timeOut = 10)
    {
        $this->setDomain("QueryRefund");
        return $this->getJsonCurl($inputObj, $timeOut);
    }


    /**
     * 微信获取openid
     * */
    public function getOpenId($url, $timeOut = 100)
    {
        return $this->getJsonCurl($url);
    }

    protected function setDomain($name)
    {
        $this->url = $this->baseURL . $name;
    }

    /**
     *
     * QR支付跳转,JsApi支付跳转，nonce_str、time不需要填入
     * @param string $pay_url
     * @param \Tomato\OmiPay\Requests\OmiRequest $inputObj
     * @return string $pay_url
     */
    public function getRedirectUrl($pay_url, $inputObj)
    {
        $this->processRequest($inputObj);
        $pay_url .= '&' . $inputObj->toQueryParameters();
        return $pay_url;
    }


    /**
     *
     * 产生随机字符串，不长于30位
     * @param int $length
     * @return string $str 产生的随机字符串
     */
    public function getNonceStr($length = 30)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

//    /***
//     *判断时不时移动设备
//     */
//    public function isMobile()
//    {
//        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
//        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
//            return true;
//        }
//        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
//        if (isset ($_SERVER['HTTP_VIA'])) {
//            // 找不到为flase,否则为true
//            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
//        }
//        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
//        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
//            $clientkeywords = array('nokia',
//                'sony',
//                'ericsson',
//                'mot',
//                'samsung',
//                'htc',
//                'sgh',
//                'lg',
//                'sharp',
//                'sie-',
//                'philips',
//                'panasonic',
//                'alcatel',
//                'lenovo',
//                'iphone',
//                'ipod',
//                'blackberry',
//                'meizu',
//                'android',
//                'netfront',
//                'symbian',
//                'ucweb',
//                'windowsce',
//                'palm',
//                'operamini',
//                'operamobi',
//                'openwave',
//                'nexusone',
//                'cldc',
//                'midp',
//                'wap',
//                'mobile'
//            );
//            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
//            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
//                return true;
//            }
//        }
//        // 协议法，因为有可能不准确，放到最后判断
//        if (isset ($_SERVER['HTTP_ACCEPT'])) {
//            // 如果只支持wml并且不支持html那一定是移动设备
//            // 如果支持wml和html但是wml在html之前则是移动设备
//            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
//                return true;
//            }
//        }
//        return false;
//    }

    /**
     * 以get方式提交json到对应的接口url
     *
     * @param OmiRequest $inputObj
     * @param int $second url执行超时时间，默认30s
     * @return mixed|string
     * @throws \Tomato\OmiPay\Exceptions\OmiPayException
     */
    private function getJsonCurl($inputObj, $second = 100)
    {
        $url = $this->url;
        $this->processRequest($inputObj);

        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //如果有配置代理这里就设置代理
        if (Config::get("omipay.curl_proxy_host") != "0.0.0.0"
            && Config::get("omipay.curl_proxy_port") != 0
        ) {
            curl_setopt($ch, CURLOPT_PROXY, Config::get("omipay.curl_proxy_host"));
            curl_setopt($ch, CURLOPT_PROXYPORT, Config::get("omipay.curl_proxy_port"));
        }

        if (isset($inputObj)) {
            $url .= '?' . $inputObj->toQueryParameters();
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        //GET提交方式
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);

        //运行curl
        $data = curl_exec($ch);

        //返回结果
        if ($data) {
            curl_close($ch);
            return $this->processResponse($data,data_get($inputObj->getParameters(),"redirect_url"));
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            // throw new OmiPayException("curl出错，错误码:$error");
            return array("curl error code" => $error);
        }
    }

    /**
     * @param OmiRequest $inputObj
     */
    protected function processRequest($inputObj)
    {
        $inputObj->getMerchantNo();
        $inputObj->setTime($this->getMillisecond());//时间戳
        $inputObj->setNonceStr($this->getNonceStr());//随机字符串
        $inputObj->setSign();
    }

    /**
     * 以put方式提交json到对应的接口url
     *
     * @param OmiRequest $inputObj
     * @param int $second url执行超时时间，默认30s
     * @return mixed|string
     * @throws \Tomato\OmiPay\Exceptions\OmiPayException
     */
    private function postJsonCurl($inputObj, $second = 30)
    {
        if (empty($inputObj)) {
            return false;
        }

        $this->processRequest($inputObj);

        $postUrl = $this->url;
        $curlPost = $inputObj->toQueryParameters();
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验

        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl

        //返回结果
        if ($data) {
            curl_close($ch);
            return $this->processResponse($data,data_get($inputObj->getParameters(),"redirect_url"));
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            // throw new OmiPayException("curl出错，错误码:$error");
            return array("curl error code" => $error);
        }

    }

    /**
     * 获取毫秒级别的时间戳
     */
    private function getMillisecond()
    {
        $time = explode(" ", microtime());
        $time1 = explode(".", $time[0] * 1000);//  $time[0] * 1000;
        if ($time1[0] < 10) {
            $time2 = $time[1] . "00" . $time1[0];
        } else if ($time1[0] < 100) {
            $time2 = $time[1] . "0" . $time1[0];

        } else {
            $time2 = $time[1] . $time1[0];
        }
        return $time2;
    }

    private function processResponse($response,$redirectURL=null)
    {
        $result= json_decode($response, true);
        if(array_get($result,"return_code")!="SUCCESS"){
            $exception=new OmiPayException("There is error when OmiPay process your request.");
            $exception->errorCode=array_get($result,"return_code");
            $exception->errorMessage=array_get($result,"error_msg");
            throw $exception;
        }
        if(array_key_exists("pay_url",$result)){
            $result["pay_url"]=$this->getRedirectUrl($result["pay_url"],new OmiRequest()).($redirectURL?("&redirect_url=".urlencode($redirectURL)):"");
        }
        return $result;
    }

    public function anyNotification(){
        $data=Request::all();
        $inputObj=new OmiRequest();
        $inputObj->setTime(array_get($data,"timestamp"));//时间戳
        $inputObj->setNonceStr(array_get($data,"nonce_str"));//随机字符串
        $inputObj->setSign();
        $signed=$inputObj->getSign();
        if($signed!=array_get($data,"sign")){
            Log::error("OmiPay SIGN_ERROR: ".json_encode($data));
            return json_encode(["return_code"=>"FAIL","error_code"=>"SIGN_ERROR"]);
        }

        Event::fire("omipay.got-notification",[$data]);
        return json_encode(["return_code"=>"SUCCESS"]);
    }

//    public function mkdirs($dirs, $mode = 0777)
//    {
//
//        $dir = iconv("UTF-8", "GBK", $dirs); // 防止中文乱码
//        if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
//        if (!mkdirs(dirname($dir), $mode)) return FALSE;
//
//        return @mkdir($dir, $mode);
//    }

}