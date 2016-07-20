<?php
/**
 * @desc 支付宝APP支付接口
 * @author shangheguang@yeah.net
 * @date 2015-08-24
 */

require_once 'Alipay.php';

$Alipay = new Alipay();
$Alipay->out_trade_no = date('YmdHis') . substr(time(), - 5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));//订单号
$Alipay->subject 	= '描述信息';//支付描述信息
$Alipay->body 		= '主体信息';//支付描述信息
$Alipay->total_fee  = 1;//订单总金额，1元
$Alipay->it_b_pay 	= (time()+86400).'m';//订单支付的过期时间(eg:一天过期);
$Alipay->notify_url = 'http://www.baidu.cn/v1/alipay/notify/';

//数据以JSON的形式返回给APP
$app_response = $Alipay->doPay();
if ($app_response == false) {
	$errorCode = 100;
	$errorMsg = 'sign error';
	echoResult($errorCode, $errorMsg);
} else {
	$errorCode = 0;
	$errorMsg = 'success';
	$responseData = array(
		'notify_url'  => $Alipay->notify_url,
		'app_response' => $Alipay->doPay()
	);
	echoResult($errorCode, $errorMsg, $responseData);
}

//接口输出
function echoResult($errorCode = 0, $errorMsg = 'success', $responseData = array()) 
{
    $arr = array(
        'errorCode' => $errorCode,
        'errorMsg' => $errorMsg,
        'responseData' => $responseData,
    );
    exit(json_encode($arr));
}

