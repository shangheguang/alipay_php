<?php
/**
 * @desc 支付宝APP支付异步通知，更改订单状态。
 * @author shangheguang@yeah.net
 * @date 2015-08-24
 */

require_once 'Alipay.php';
require_once 'alipay/alipay_notify.class.php';

$Alipay 	   = new Alipay();
$AlipayNotify  = new AlipayNotify($Alipay->alipay_config);
$verify_result = $AlipayNotify->verifyNotify();
if ($verify_result) {
	//通知的原始数据
	$requestReturnData = file_get_contents("php://input");
	//商户订单号
	$out_trade_no = isset($_REQUEST['out_trade_no']) ? $_REQUEST['out_trade_no'] : '';
	//支付宝交易号
	$trade_no     = isset($_REQUEST['trade_no']) ? $_REQUEST['trade_no'] : '';
	//交易状态
	$trade_status = isset($_REQUEST['trade_status']) ? $_REQUEST['trade_status'] : '';
	//支付金额
	$total_fee 	  = isset($_REQUEST['total_fee']) ? $_REQUEST['total_fee'] : '';
	//支付时间
	$pay_date 	  = isset($_REQUEST['gmt_payment']) ? $_REQUEST['gmt_payment'] : '';
	/* 
		@todo
		1.更改订单状态为已支付。(需自己完善)
		2.添加付款信息到数据库,方便对账。(需自己完善)
	*/
	$pay_arr = array(
		'pay_type' 			=> isset($_REQUEST['pay_type']) ? $_REQUEST['pay_type'] : '',
		'action' 			=> 'notify',
		'domain_type' 		=> isset($_REQUEST['domain_type']) ? $_REQUEST['domain_type'] : '',
		'out_trade_no' 		=> $out_trade_no,
		'trade_no' 			=> $trade_no,
		'trade_status' 		=> $trade_status,
		'trade_return_data' => $requestReturnData,
	);					
	//判断状态
	if (in_array($trade_status, array('TRADE_FINISHED', 'TRADE_SUCCESS'))) {
		//更新订单。。。。。。
		//处理后同步返回给支付宝
		exit('success');
	}
}
exit('fail');


