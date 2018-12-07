<?PHP

RETURN [

    // API版本
    "api_version" => "/omipay/api/v2",

    // // 正式环境
    "domain" => "https://www.omipay.com.au",
    "domain_cn" => "https://g.omipay.com.cn",

    "IpayLinksUrl" => "https://g.omipay.com.cn/cardpay/",

    // =======【curl代理设置】===================================
    /**
     * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
     * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
     * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
     */
    "curl_proxy_host" => "0.0.0.0",//"192.168.0.1",
    "curl_proxy_port" => 0,//8080,

    // 设置商户号，不要加M  如 "000034"
    "merchant_no" => env("OMIPAY_MERCHANT_NO"),

    // 商户密钥
    "merchant_key" => env("OMIPAY_MERCHANT_KEY"),

    // 支付完成通知地址
    "notify_url" => "",

    // 默认为CN / AU
    "domain_type" => "CN",

    //use package's routes for notification
    "use_package_routes"=>true,
];