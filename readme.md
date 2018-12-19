## OmiPay Laravel wrapper

## Installation

```shell
composer require tomato-technologies/tomato-omipay
```

Laravel 5.5 uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

### Laravel 5.5+:

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```php
Tomato\OmiPay\ServiceProvider::class,
```

If you want to make it easier to access Pusher or Event class, add this to your facades in app.php:

```php
'TomatoOmiPay' => Tomato\OmiPay\Facade::class,
```

## Usage

Before usage, please remember to set your `OMIPAY_MERCHANT_NO` and `OMIPAY_MERCHANT_KEY` in `.env` file. The merchant number should be a number with "M", eg. "000034".


If you want to get more config on this wrapper, you can pull a configuration file into your application by running on of the following artisan command:

```cli
php artisan vendor:publish --provider="Tomato\OmiPay\ServiceProvider"
```

## Example Coding

Making a QRorder:

```php
    $qrRequest=new \Tomato\OmiPay\Requests\Payment\QROrderRequest();
    $qrRequest->setOrderName("Test Product 1");
    $qrRequest->setCurrency("AUD");
    $qrRequest->setAmount(1*100);//1 dollar
    $qrRequest->setNotifyUrl(route("opmipay-notification"));
    $qrRequest->setOutOrderNo("10001");
    $qrRequest->setPlatform("ALIPAY");
    $result=\TomatoOmiPay::qrOrder($qrRequest);

    var_dump($result);
```

Here is what you will see from `var_dump($result)`, then using `pay_url` to redirect use for payment
```log
array (size=8)
  'order_no' => string 'TR1812060010011263009795' (length=24)
  'qrcode' => string 'https://qr.alipay.com/bax06022po8u7fm93zxp200b' (length=46)
  'pay_url' => string 'https://www.omipay.com.cn/Omipay/H5Pay/QRcode_Pay.html?paycode=TR1812060010011263009795&m_number=0010011263&platform=WECHATPAY&timestamp=1544088240364&nonce_str=jinwuwc8hyzi67itxwf9x0fjxjz00q&sign=F49FC0DFFB81EDF639AB8BF4A0FE7252' (length=229)
  'platform' => string 'ALIPAY' (length=6)
  'error_msg' => null
  'msg' => null
  'success' => boolean true
  'return_code' => string 'SUCCESS' (length=7)
```

## Example of How to receive Notification from OmiPay

Only verified request will be fired on this event, so there is not need to check the sign.

Listen `omipay.got-notification` in you EventServiceProvider and map it to you own handler.
```php
'omipay.got-notification'=>[
            'App\Http\Controllers\Site\HomeController@onOmipayNotice'
        ],
```

Here is example handler, `$data` is an array containing all request data (https://www.omipay.com.au/Help/API_new.html#nine).
```php
    public function onOmipayNotice($data=[]){
        \Log::info(json_encode($data));
    }
```

### Change Logs
Updates on 19 Dec 2018:

For any Omipay order which support "redirect_url", this package will include the "redirect_url" to the "pay_url", for example

Before:

```php
    $qrRequest=new \Tomato\OmiPay\Requests\Payment\QROrderRequest();
    $qrRequest->setOrderName("Test Product 1");
    $qrRequest->setCurrency("AUD");
    $qrRequest->setAmount(1*100);//1 dollar
    $qrRequest->setNotifyUrl(route("opmipay-notification"));
    $qrRequest->setOutOrderNo("10001");
    $qrRequest->setPlatform("ALIPAY");
    $result=\TomatoOmiPay::qrOrder($qrRequest);
    
    $redirectURL=route("thank-you-page",["order_id"=>10001]);
    $directCusomterToURL=data_get($result,"pay_url")."&redirect_url=".urlencode($redirectURL);
```

After:
```php
    $qrRequest=new \Tomato\OmiPay\Requests\Payment\QROrderRequest();
    $qrRequest->setOrderName("Test Product 1");
    $qrRequest->setCurrency("AUD");
    $qrRequest->setAmount(1*100);//1 dollar
    $qrRequest->setNotifyUrl(route("opmipay-notification"));
    $qrRequest->setRedirectUrl(route("thank-you-page",["order_id"=>10001]))
    $qrRequest->setOutOrderNo("10001");
    $qrRequest->setPlatform("ALIPAY");
    $result=\TomatoOmiPay::qrOrder($qrRequest);
 
    $directCusomterToURL=data_get($result,"pay_url");
```
