<?php
/* *
 * 功能：即时到账交易接口返回
 * 版本：3.3
 * 日期：2015-01-11
 */

class AlipayCallback {
	
	//参数配置
	public $alipay_config;
	
	//__construct
	function __construct() {
		require_once ("alipay/config.php");
		require_once ("alipay/alipay_notify.class.php");
		
		//载入基本配置
		$this->alipay_config = $alipay_config; 
	}
	
	//支付宝页面跳转同步通知
	public function checkReturn() {
		$alipayNotify = new AlipayNotify($this->alipay_config);
		return $alipayNotify->verifyReturn();
	}
	
	//支付宝服务器异步通知
	public function checkNotify() {
		$alipayNotify = new AlipayNotify($this->alipay_config);
		return $alipayNotify->verifyNotify();
	}
	
	//解密
	public function decrypt($prestr) {
		$alipayNotify = new AlipayNotify($this->alipay_config);
		return $alipayNotify->decrypt($prestr);
	}
	
	//__destruct
	function __destruct() {
	}
}

