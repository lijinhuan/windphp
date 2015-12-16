<?php
/*
 * windphp v1.0
 * https://github.com/lijinhuan
 *
 * Copyright 2015 (c) 543161409@qq.com
 * GNU LESSER GENERAL PUBLIC LICENSE Version 3
 * http://www.gnu.org/licenses/lgpl.html
 *
 */

if(!defined('FRAMEWORK_PATH')) {
	exit('access error !');
}

class Misc {
	public static $pre = 'windphp_';
	
	/**
	 * 获取客户端ip
	 */
	public static function getIp() {
		static $ip = null;
		if (! $ip) {
			if (isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ) && $_SERVER ['HTTP_X_FORWARDED_FOR'] && $_SERVER ['REMOTE_ADDR']) {
				if (strstr ( $_SERVER ['HTTP_X_FORWARDED_FOR'], ',' )) {
					$x = explode ( ',', $_SERVER ['HTTP_X_FORWARDED_FOR'] );
					$_SERVER ['HTTP_X_FORWARDED_FOR'] = trim ( end ( $x ) );
				}
				if (preg_match ( '/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER ['HTTP_X_FORWARDED_FOR'] )) {
					$ip = $_SERVER ['HTTP_X_FORWARDED_FOR'];
				}
			} elseif (isset ( $_SERVER ['HTTP_CLIENT_IP'] ) && $_SERVER ['HTTP_CLIENT_IP'] && preg_match ( '/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER ['HTTP_CLIENT_IP'] )) {
				$ip = $_SERVER ['HTTP_CLIENT_IP'];
			}
			if (! $ip && preg_match ( '/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER ['REMOTE_ADDR'] )) {
				$ip = $_SERVER ['REMOTE_ADDR'];
			}
			! $ip && $ip = 'Unknown';
		}
		return $ip;
	}
	
	
	
	/**
	 * 生成随机字符串
	 * @param string $lenth 长度
	 * @return string 字符串
	 */
	public static function createRandomstr($lenth = 6) {
		return self::random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
	}
	
	
	
	
	/**
	 * 产生随机字符串
	 *
	 * @param    int        $length  输出长度
	 * @param    string     $chars   可选的 ，默认为 0123456789
	 * @return   string     字符串
	 */
	public static function random($length, $chars = '0123456789') {
		$hash = '';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
		return $hash;
	}
	
	
	public static function makeSalt( $passwordLength = 32, $generatedPassword='') {
		$valid_characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~!@#$%^&*";
		$chars_length = strlen($valid_characters) - 1;
		for($i = $passwordLength; $i--; ) {
			$generatedPassword .= substr($valid_characters, (mt_rand()%(strlen($valid_characters))), 1);
		}
		return $generatedPassword;
	}
	
	
	/**
	 * @name send_cache_headers
	 * @todo send http headers for cache control
	 * @param int $expire expire time in seconds
	 * @return void
	 */
	public static function sendCacheHeaders($expire=30, $type = 'text/html', $charset='') {
		if($expire==0) {
			@header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			@header ("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
			@header ("Cache-Control: no-cache, no-store, must-revalidate");
			@header ("Pragma: no-cache");
		} else {
			@header("Expires: ".gmdate("D, d M Y H:i:s", time()+$expire)." GMT");
			@header("Cache-Control: max-age=".$expire);
		}
		if($charset)
			@header("Content-type: " . $type . "; charset=" . $charset);
	}
	
	
	public static function getCookie($key,$autoKey){
		$val =   isset($_COOKIE[self::$pre.$key])?$_COOKIE[self::$pre.$key] : '';
		if(empty($val)){return $val;}
		return self::sysAuth($val,'DECODE',$autoKey);
	}
	
	
	
	/**
	 * 设置cookie
	 * @param string $var
	 * @param string $value
	 * @param int $life
	 * @param bool $prefix
	 * @param bool $http_only
	 * @return void
	 */
	public static function setCookie($var, $value = '', $autoKey,$life = 0, $prefix = true,$path='',$domain='', $httpOnly = false){
		$var = ($prefix ? self::$pre : '').$var;
		if($value == '' || $life < 0){
			$value = '';
			$life = -1;
		}
		$life = $life > 0 ? time() + $life : ($life < 0 ? time() - 31536000 : 0);
		if(empty($path)){
			$path = $httpOnly && PHP_VERSION < '5.2.0' ? '/; HttpOnly' : '/';
		}
		
		$secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
		if(PHP_VERSION < '5.2.0'){
			setcookie($var,self::sysAuth($value,'ENCODE',$autoKey), $life, $path, $domain, $secure);
		}else{
			setcookie($var,self::sysAuth($value,'ENCODE',$autoKey), $life, $path, $domain, $secure, $httpOnly);
		}
	}
	
	
	/**
	 * $string 明文或密文
	 * $operation 加密ENCODE或解密DECODE
	 * $key 密钥
	 * $expiry 密钥有效期
	 */
	public static function sysAuth($string, $operation = 'DECODE', $key, $expiry = 0) {
		// 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
		// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
		// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
		// 当此值为 0 时，则不产生随机密钥
		$ckey_length = 4;	
		// 密匙
		// $GLOBALS['discuz_auth_key'] 这里可以根据自己的需要修改
		$key = md5($key);
			
		// 密匙a会参与加解密
		$keya = md5(substr($key, 0, 16));
		// 密匙b会用来做数据完整性验证
		$keyb = md5(substr($key, 16, 16));
		// 密匙c用于变化生成的密文
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
		// 参与运算的密匙
		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);
		// 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
		// 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确	
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);
		$result = '';
		$box = range(0, 255);
		$rndkey = array();
		// 产生密匙簿
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}
		// 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上并不会增加密文的强度
		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
		// 核心加解密部分
		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			// 从密匙簿得出密匙进行异或，再转成字符
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
		if($operation == 'DECODE') {
			// substr($result, 0, 10) == 0 验证数据有效性
			// substr($result, 0, 10) - time() > 0 验证数据有效性
			// substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
			// 验证数据有效性，请看未加密明文的格式
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			// 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
			// 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}
	
	
	

	/**
	 * 提示信息页面跳转，跳转地址如果传入数组，页面会提示多个地址供用户选择，默认跳转地址为数组的第一个值，时间为5秒。
	 * showmessage('登录成功', array('默认跳转地址'=>'http://www.test.cn'));
	 * @param string $msg 提示信息
	 * @param mixed(string/array) $url_forward 跳转地址
	 * @param int $ms 跳转等待时间
	 */
	public static function showMessage($msg, $urlForward = 'goback', $ms = 1250, $dialog = '', $returnjs = '') {
		$str='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta http-equiv="X-UA-Compatible" content="IE=7" />
				<title>消息提示</title>
				<style type="text/css">
				*{ padding:0; margin:0; font-size:12px}
				a:link,a:visited{text-decoration:none;color:#0068a6}
				a:hover,a:active{color:#ff6600;text-decoration: underline}
				.showMsg{overflow:hidden;border: 1px solid #1e64c8;    border-color: #204d74; zoom:1; width:450px; height:200px;position:absolute;top:44%;left:50%;margin:-87px 0 0 -225px}
				.showMsg h5{    background-color: #286090;box-shadow: inset 0 -4px 0 #2a6496; color:#fff; padding-left:35px; height:30px; line-height:26px;*line-height:28px; overflow:hidden; font-size:14px; text-align:left}
				.showMsg .content{ padding:46px 12px 10px 45px; font-size:14px; height:88px; text-align:left}
				.showMsg .bottom{ background:#e4ecf7; margin: 0 1px 1px 1px;line-height:26px; *line-height:30px; height:26px; text-align:center}
				.showMsg .ok,.showMsg .guery{}
				.showMsg .guery{}
				</style>
				</head>
				<body>
				<div class="showMsg" style="text-align:center">
					<h5>消息提示</h5>
				    <div class="content guery" style="display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline;max-width:330px">'.$msg.'</div>
				    <div class="bottom">';
		if($urlForward=='goback' || $urlForward=='') {
			$str = $str.'<a href="javascript:history.back();" >返回</a>';
		}elseif($urlForward=="close") {
			$str = $str.'<input type="button" name="close" value="关闭 " onClick="window.close();">';
		}elseif($urlForward=="blank") {
				
		}elseif($urlForward) {
			$str = $str.'<a href="'.$urlForward.'">无法跳转？请点击这里</a>';
			$str = $str.'<script language="javascript">setTimeout("redirect(\''.$urlForward.'\');",'.$ms.');</script> ';
		}
	
	
		if($returnjs) {
			$str = $str.'<script style="text/javascript">'.$returnjs.'</script>';
		}
	
		if ($dialog){
			$str = $str.'<script style="text/javascript">window.top.right.location.reload();window.top.art.dialog({id:"'.$dialog.'"}).close();</script>';
		}
		$str = $str.'</div>
					</div>
					<script style="text/javascript">
						function close_dialog() {
							window.top.right.location.reload();window.top.art.dialog({id:"'.$dialog.'"}).close();
						}
						function redirect(url) {
							location.href = url;
						}
					</script>
					</body>
					</html>';
		exit($str);
	}
	
	
	
	public static  function showMmsg($msg,$url='',$s=5000){
		$str=<<<MMSG
			<html>
				<head>
					<meta charset="utf-8">
				    <meta http-equiv="X-UA-Compatible" content="IE=edge">
				    <meta name="viewport" content="width=device-width, initial-scale=1">
					<title>消息提示</title>
				</head>
				<body>
				  <div style="text-align:center;">
					<div style="-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;-moz-box-shadow: 0 1px 1px #fff inset;-webkit-box-shadow: 0 1px 1px #fff inset;box-shadow: 0 1px 1px #fff inset;;padding:10px 30px;margin-top:10%;background:pink;border: 1px solid #333;">
					   {$msg}
					</div>
				  </div>
				 <script language="javascript">
				     setTimeout("redirect('{$url}');",$s);
				 	 function redirect(url){
				     	if(url){
							window.location.href = url;
						}
					 }
				 </script>
				</body>
			</html>
MMSG;
						exit($str);
	}
	
	
	
	
	public static function getParam($k, $var = 'G') {
		switch($var) {
			case 'G': $var = &$_GET; break;
			case 'P': $var = &$_POST; break;
			case 'C': $var = &$_COOKIE; break;
			case 'R': $var = isset($_GET[$k]) ? $_GET : (isset($_POST[$k]) ? $_POST : $_COOKIE); break;
			case 'S': $var = &$_SERVER; break;
		}
		return isset($var[$k]) ? $var[$k] : NULL;
	}
	
	
	
	/**
	 * 获得输入数据
	 * 如果输入了回调方法则返回数组:第一个值：value;第二个值：验证结果
	 *
	 * @param string $name input name
	 * @param string $type input type (GET POST )
	 * @return array string
	 */
	public static function getInput($name, $type = '', $bindKey = false) {
		if (is_array($name)) {
			$result = array();
			foreach ($name as $key => $value) {
				$_k = $bindKey ? $value : $key;
				$result[$_k] = self::getInput($value, $type);
			}
			return $result;
		} elseif ($name) {
			$value = '';
			switch (strtoupper($type)) {
				case 'G':
					$value = self::getParam($name,'G');
					break;
				case 'P':
					$value = self::getParam($name,'P');
					break;
				case 'C':
					$value = self::getParam($name,'C');
					break;
				case 'R':
					$value = self::getParam($name,'R');
					break;
				case 'S':
					$value = self::getParam($name,'S');
					break;
				default:
					$value = self::getParam($name,'G');
			}
			return $value;
		}
		return '';
	}
	
	
	/**
	 * @name            input_clean
	 * @description     少量过滤request值
	 * @param           string $field (request的key)
	 * @return          mixed 过滤后的field值
	 * @author          jian.chen5
	 * @copyright       2013-08-01
	 */
	public static function inputClean($field){
		$result = '';
		$clean_val = isset($_REQUEST[$field]) ? $_REQUEST[$field] : '';
		if( !empty($clean_val) ){
			if( is_array($clean_val) ){
				foreach($clean_val as $key => $value){
					$key = self::inputCleanOne($key);
					$result[$key] = self::inputCleanOne($value);
				}
			}else{
				$result = self::inputCleanOne($clean_val);
			}
		}
		return $result;
	}
	
	
	
	/**
	 * @name            input_clean_one
	 * @description     需要过滤的值,请使用input_clean
	 * @param           string $value (过滤值)
	 * @return          string 过滤后的值
	 * @author          jian.chen5
	 * @copyright       2013-08-01
	 */
	public static function inputCleanOne($value){
		$result = trim($value);
		$result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8', false);
		return $result;
	}
	
	 public static function exitJson($arr){
	 	if(is_string($arr))echo $arr;
	 	else exit(json_encode($arr));
	 }
	 
	 
	 public static function runTime(){
	 	return sprintf("%1\$.3f",microtime(true)-$_SERVER['starttime']);
	 }
	 
	 
	 public static function usageMemory(){
	 	return (round(memory_get_usage()/1024,2)) .'kb';
	 }
	 
	
	/**
	 des: 将多维数组转换成字符串
	 @param: $array 多维数组
	 @param: $mode 分割的界定符
	 @return: 字符串
	 */
	public static function implodeMultiArr( $array, $mode ){
		$data_str = '';
		foreach( $array as $keys => $values ){
			if( is_array( $values ) ){
				$data_str .= self::implodeMultiArr( $values, $mode );
			}else {
				$data_str .= $values . $mode;
			}
		}
		return rtrim($data_str,$mode);
	}
	
	
	public static function getSmallPage($page, $dataNum, $prePageNum){
		$show_page = new ShowPage();
		$show_page->setvar(array('page'));
		return $show_page->getSmallPage($page, $dataNum, $prePageNum);
	}
	
	
	
	public static function page($totalCount,$currentPage,$pageRows=20,$showPageNum=10,$pagevars=array('page')){
		$total_page = ceil($totalCount/$pageRows);
		if($total_page==1){return '';}
		$sp = new ShowPage();
		$sp -> setShowNum($showPageNum); // 分页列表显示多少页
		$sp -> setVar($pagevars);
		$sp -> setAdmin($pageRows, $totalCount,$currentPage);
		return $sp -> outPut(true);
	}
	
	
	
	/**
	 * 检测中文长度
	 */
	public static function utf8Strlen($str) {
		$count = mb_strlen($str,'utf-8');
		return $count;
	}
	
	
	
	/**
	 * xss过滤函数
	 *
	 * @param $string
	 * @return string
	 */
	public static function removeXss($string) {
		$string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);
		$parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
		$parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
		$parm = array_merge($parm1, $parm2);
		for ($i = 0; $i < sizeof($parm); $i++) {
			$pattern = '/';
			for ($j = 0; $j < strlen($parm[$i]); $j++) {
				if ($j > 0) {
					$pattern .= '(';
					$pattern .= '(&#[x|X]0([9][a][b]);?)?';
					$pattern .= '|(&#0([9][10][13]);?)?';
					$pattern .= ')?';
				}
				$pattern .= $parm[$i][$j];
			}
			$pattern .= '/i';
			$string = preg_replace($pattern, '', $string);
		}
		return $string;
	}
	
	
	/**
	 * 将文本格式成适合js输出的字符串
	 * @param string $string 需要处理的字符串
	 * @param intval $isjs 是否执行字符串格式化，默认为执行
	 * @return string 处理后的字符串
	 */
	public static function  formatJs($string, $isjs = 1) {
		$string = addslashes(str_replace(array("\r", "\n", "\t"), array('', '', ''), $string));
		return $isjs ? 'document.write("'.$string.'");' : $string;
	}
	
	
	/**
	 * 安全过滤函数
	 *
	 * @param $string
	 * @return string
	 */
	public static function safeReplace($string) {
		$string = str_replace('%20','',$string);
		$string = str_replace('%27','',$string);
		$string = str_replace('%2527','',$string);
		$string = str_replace('*','',$string);
		$string = str_replace('"','&quot;',$string);
		$string = str_replace("'",'&#39',$string);
		$string = str_replace("'",'',$string);
		$string = str_replace('"','',$string);
		$string = str_replace(';','',$string);
		$string = str_replace('<','&lt;',$string);
		$string = str_replace('>','&gt;',$string);
		$string = str_replace("{",'',$string);
		$string = str_replace('}','',$string);
		$string = str_replace('\\','',$string);
		return $string;
	}
	
	
	
	/**
	 *  简单过滤
	 */
	public static function stripHtml($string,$strip=1){
		$string = str_replace('"','&quot;',$string);
		$string = str_replace("'",'&#39;',$string);
		$string = str_replace("'",'',$string);
		$string = str_replace('"','',$string);
		if($strip){
			$string = strip_tags($string);
		}else{
			$string = str_replace('<','&lt;',$string);
			$string = str_replace('>','&gt;',$string);
		}
		return $string;
	}
	
	
	
	/**
	 * 过滤转换
	 * @param unknown $safeStr
	 * @return mixed
	 */
	public static function editorSafeBase($safeStr){
		$safeStr = preg_replace('/\<img\s+data\=\"flash\"\s+src\=\".*?(?:[\.gif|\.jpg|\.png])\"\s+val\=\"(.*?(?:[\.swf|\.mp4]))\?.*?\"\s*\/\>/is', '[flash src=+\1+]', $safeStr);
		$safeStr = str_replace('<br />', '[[br/]]', $safeStr);
		$safeStr = str_replace('<br/>', '[[br/]]', $safeStr);
		$safeStr = str_replace('<br>', '[[br/]]', $safeStr);
		$safeStr = str_replace('<strong>', '[[strong]]', $safeStr);
		$safeStr = str_replace('</strong>', '[[/strong]]', $safeStr);
		$safeStr = str_replace('<p>', '[[p]]', $safeStr);
		$safeStr = str_replace('</p>', '[[/p]]', $safeStr);
		$safeStr = str_replace('<em>', '[[em]]', $safeStr);
		$safeStr = str_replace('</em>', '[[/em]]', $safeStr);
		$safeStr = str_replace('</u>', '[[/u]]', $safeStr);
		$safeStr = str_replace('<u>', '[[u]]', $safeStr);
		$safeStr = str_replace('<s>', '[[s]]', $safeStr);
		$safeStr = str_replace('</s>', '[[/s]]', $safeStr);
		$safeStr = str_replace('</blockquote>', '[[/blockquote]]', $safeStr);
		$safeStr = str_replace('<blockquote>', '[[blockquote]]', $safeStr);
		$safeStr = preg_replace('/\<span\s+style\=\"color\:\#(.*?)\"\>(.*?)\<\/span\>/is', '[span style=+color:#\1+]\2[/span]', $safeStr);
		$safeStr = preg_replace('/\<a\s+href\=\"(.*?)\"\>(.*?)\<\/a\>/is', '[a href=+\1+]\2[/a]', $safeStr);
		$safeStr = preg_replace('/\<img\s+data\=\"flash\"\s+src\=\".*?(?:[\.gif|\.jpg|\.png])\"\s+val\=\"(.*?(?:[\.swf|\.mp4]))\"\s*\/\>/is', '[flash src=+\1+]', $safeStr);
		$safeStr = preg_replace('/\<img\s+src\=\"(.*?(?:[\.gif|\.jpg|\.png]))\"\s+(.*?)\/\>/is', '[img src=+\1+\2+]', $safeStr);
		$safeStr = self::strip_html($safeStr);
		$safeStr = preg_replace('/\[flash\s+src\=\+(.*?)\+\]/is', '<embed src="\1" autostart=false fullscreen=true width="480" height="360" align="bottom"></embed>', $safeStr);
		$safeStr = preg_replace('/\[img\s+src\=\+(.*?)\+(.*?)\+\]/is', '<img src="\1" \2 />', $safeStr);
		$safeStr = preg_replace('/\<img\s+src\=\"(.*?)\"\s*style\=\&quot\;(.*?)\&quot\;\s*\/\>/is', '<img src="\1" style="\2" />', $safeStr);
		$safeStr = preg_replace('/\[a\s+href\=\+(.*?)\+\](.*?)\[\/a\]/is', '<a href="\1">\2</a>', $safeStr);
		$safeStr = preg_replace('/\[span\s+style\=\+color\:\#(.*?)\+\](.*?)\[\/span\]/is', '<span style="color:#\1">\2</span>', $safeStr);
		$safeStr = str_replace('[[br/]]', '<br/>', $safeStr);
		$safeStr = str_replace('[[strong]]', '<strong>', $safeStr);
		$safeStr = str_replace('[[/strong]]', '</strong>', $safeStr);
		$safeStr = str_replace('[[em]]', '<em>', $safeStr);
		$safeStr = str_replace('[[/em]]', '</em>', $safeStr);
		$safeStr = str_replace('[[p]]', '<p>', $safeStr);
		$safeStr = str_replace('[[/p]]', '</p>', $safeStr);
		$safeStr = str_replace('[[u]]', '<u>', $safeStr);
		$safeStr = str_replace('[[/u]]', '</u>', $safeStr);
		$safeStr = str_replace('[[s]]', '<s>', $safeStr);
		$safeStr = str_replace('[[/s]]', '</s>', $safeStr);
		$safeStr = str_replace('[[blockquote]]', '<blockquote>', $safeStr);
		$safeStr = str_replace('[[/blockquote]]', '</blockquote>', $safeStr);
		return $safeStr;
	}
	
	
	/**
	 * 检查长度
	 * @param unknown $str
	 * @return number
	 */
	public static function checkEditorStrLength($str){
		$con = strip_tags(str_replace('<br />', '',$str));
		$con = strip_tags(str_replace('<br/>', '',$con));
		$con = strip_tags(str_replace('<br>', '',$con));
		$con = strip_tags(str_replace("\n", '',$con));
		$con = strip_tags(str_replace("\t", '',$con));
		$con = strip_tags(str_replace(' ', '',$con));
		$con = str_replace('&nbsp;', '', $con);
		$strLength = self::utf8_strlen($con);
		return $strLength;
	}
	
	
	/**
	 * 截取字符串
	 * @param unknown $string
	 * @param unknown $start
	 * @param unknown $end
	 * @param string $dot
	 * @return string
	 */
	public static  function cutstr($string,$start,$end,$dot='...'){
		if(self::utf8_strlen($string)>$end){
			$string = str_replace('&ldquo;', '', $string);
			$string = str_replace('&rdquo;', '', $string);
			$string = str_replace('&middot;', '', $string);
			$string = str_replace('&nbsp;', '', $string);
			$string =  mb_substr($string, $start,$end,'utf-8').$dot;
		}
		return $string;
	}
	
	
	
	/**
	 *检查是否包含特殊字符
	 */
	public static function checkSpecialStr($str){
		if(!preg_match('/^[\w\x7f-\xff]+$/',$str)){
			return 1;
		}else{
			return 0;
		}
	}
	
	
	/**
	 * 不能包含中文或者特殊字符
	 */
	public static function checkChinaAndSpecial($str){
		if(!preg_match('/^[\w]+$/is',$str)){
			return 1;
		}else{
			return 0;
		}
	}
	
	
	public static function formatDate($time){
		$t=time()-$time;
		$f=array(
				'31536000'=>'年',
				'2592000'=>'个月',
				'604800'=>'星期',
				'86400'=>'天',
				'3600'=>'小时',
				'60'=>'分钟',
				'1'=>'秒'
		);
		foreach ($f as $k=>$v)    {
			if (0 !=$c=floor($t/(int)$k)) {
				return $c.$v.'前';
			}
		}
	}
	
	
	// 从一个二维数组中取出一个 values() 格式的一维数组，某一列key
	public static function arrlistValues($arrlist, $key) {
		if(!$arrlist) return array();
		$return = array();
		foreach($arrlist as &$arr) {
			$return[] = $arr[$key];
		}
		return $return;
	}
	
	
	// 将 key 更换为某一列的值，在对多维数组排序后，数字key会丢失，需要此函数
	public static function arrlistChangeKey($arrlist, $key, $pre = '') {
		$return = array();
		if(empty($arrlist)) return $return;
		foreach($arrlist as $arr) {
			$return[$pre.''.$arr[$key]] = $arr;
		}
		return $return;
	}
	
	
	// 判断一个字符串是否在另外一个字符串里面，分隔符 ,
	public static function inString($s, $str) {
		if(!$s || !$str) return FALSE;
		$s = ",$s,";
		$str = ",$str,";
		return strpos($str, $s) !== FALSE;
	}
	
}


?>
