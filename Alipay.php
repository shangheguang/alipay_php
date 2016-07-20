<?php
/* *
 * 功能：即时到账交易接口接入
 * 版本：3.3
 * 修改日期：2015-01-09
 *************************注意*************************
 * 参数说明：https://cshall.alipay.com/support/help_detail.htm?help_id=476935
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 * 
 */
class Alipay {
	
	//参数配置
	public $alipay_config;
	
	//服务器异步通知页面路径(必填)
	public $notify_url = '';
	
	//卖家支付宝帐户(必填)
	public $seller_email = 'shangheguang@yeah.net';
	
	//商户订单号(必填，商户网站订单系统中唯一订单号)
	public $out_trade_no = '';
	
	//商品名称(必填)
	public $subject = '';
	
	//商品描述(必填，不填则为商品名称)
	public $body = '';
	
	//付款金额(必填)
	public $total_fee = 0;
	
	//自定义超时(选填，支持dhmc)
	public $it_b_pay = '24h';
	
	//__construct
	function __construct() {
		require_once ("alipay/config.php");
		require_once ("alipay/alipay_submit.class.php");
		require_once ("alipay/alipay_notify.class.php");
		
		//载入基本配置
		$this->alipay_config = $alipay_config;
	}
	
	public function chkParam() {

		//用户网站订单号
		if (empty($this->out_trade_no)) {
			die('out_trade_no error');
		}
		
		//支付标题
		if (empty($this->subject)) {
			die('subject error');
		}
		
		//支付描述
		if (empty($this->body)) {
			$this->body = $this->subject;
		}
		
		//检测支付金额
		if (empty($this->total_fee) || !is_numeric($this->total_fee)) {
			die('total_fee error');
		}
		
		//回调URL地址
		if (empty($this->notify_url)) {
			die('notify_url error');
		}
		if (!preg_match("#^http:\/\/#i", $this->notify_url)) {
			$this->notify_url = "http://" . $_SERVER['HTTP_HOST'] . $this->notify_url;
		}
		
	}
	
	//生成提交支付宝参数数组
	public function createAppPara() {

		//检测构造参数
		$this->chkParam();
	
		//构造要请求的参数数组，无需改动
		$parameter = array(
			"partner" => $this->alipay_config['partner'],
			"seller_id" => $this->seller_email,
			"out_trade_no" => $this->out_trade_no,
			"subject" => $this->subject,
			"body" => $this->body,
			"total_fee" => $this->total_fee,
			"notify_url" => $this->notify_url,
			"service" => "mobile.securitypay.pay",
			"_input_charset" => trim(strtolower($this->alipay_config['input_charset'])),
			"it_b_pay" => $this->it_b_pay,
			"sign_type" => trim(strtoupper($this->alipay_config['sign_type'])),
			"sign" => "",
		);
		//建立请求
		$AlipaySubmit = new AlipaySubmit($this->alipay_config);
		return $AlipaySubmit->buildRequestPara($parameter);
	}
	
	//生成提交支付宝参数字符串
	public function createAppLink() {

		$parameter_sort = $this->createAppPara();
		if (!isset($parameter_sort['sign']) || empty($parameter_sort['sign'])) {
			return false;
		}

		$AlipaySubmit = new AlipaySubmit($this->alipay_config);
		return $AlipaySubmit->createAppLinkstring($parameter_sort);
	}
	
	//生成支付
	public function doPay() {

		return $this->createAppLink();
	}
	
	//__destruct
	function __destruct() {
	}
}

