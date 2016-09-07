<?php
/**
 * @todo web 请求类
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Web;
use Windphp\Misc\Utils;

class Request {
	
	/**
	 * todo 判断是否是ajax请求
	 * @return boolean
	 */
	public static function isAjax() {
		if (!empty($_REQUEST['ajax'])
				||!empty($_REQUEST['jsoncallback'])
				|| (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
						&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		) {
			return true;
		}
		return false;
	}
	
	
	/**
	 * todo 判断是否是命令行模式
	 * @return boolean
	 */
	public static function isCli() {
		$cli_mode = false;
		if ( isset($_SERVER['argc']) && $_SERVER['argc']>=1 ) {
			$cli_mode = true;
		}
		if(!$cli_mode and isset($_SERVER['SHELL'])) {
			$cli_mode = true;
		}
		return $cli_mode;
	}
	
	
	/**
	 * @todo 获取参数
	 */
	public static function getParam($k,$paramType,$htmlspecialchars=true,$var = 'R') {
		switch($var) {
			case 'G': $var = &$_GET; break;
			case 'P': $var = &$_POST; break;
			case 'C': $var = &$_COOKIE; break;
			case 'R': $var = isset($_GET[$k]) ? $_GET : (isset($_POST[$k]) ? $_POST : $_COOKIE); break;
			case 'S': $var = &$_SERVER; break;
		}
		if($paramType=='int') {return isset($var[$k])?intval($var[$k]):0;}
		if($paramType=='string') {
			if(isset($var[$k])) {
				return $htmlspecialchars?$htmlspecialchars($var[$k]):$var[$k];
			}else{
				return '';
			}
		}
		if(!isset($var[$k]))return NULL; 
		return $var[$k];
	}
	
	
	
	/**
	 * 获得输入数据
	 * 如果输入了回调方法则返回数组:第一个值：value;第二个值：验证结果
	 *
	 * @param string $name input name
	 * @param string $type input type (GET POST )
	 * @return array string
	 */
	public static function getInput($name,$paramType='',$htmlspecialchars=true, $type = 'R', $bindKey = true) {
		if (is_array($name)) {
			$result = array();
			foreach ($name as $key => $value) {
				$_k = $bindKey ? $value : $key;
				if(isset($paramType[$key]))$ptype=$paramType[$key];
				else $ptype='';
				$result[$_k] = self::getInput($value,$ptype,$htmlspecialchars,$type);
			}
			return $result;
		} elseif ($name) {
			$value = '';
			switch (strtoupper($type)) {
				case 'G':
					$value = self::getParam($name,$paramType,$htmlspecialchars,'G');
					break;
				case 'P':
					$value = self::getParam($name,$paramType,$htmlspecialchars,'P');
					break;
				case 'C':
					$value = self::getParam($name,$paramType,$htmlspecialchars,'C');
					break;
				case 'R':
					$value = self::getParam($name,$paramType,$htmlspecialchars,'R');
					break;
				case 'S':
					$value = self::getParam($name,$paramType,$htmlspecialchars,'S');
					break;
				default:
					$value = self::getParam($name,$paramType,$htmlspecialchars,'R');
			}
			return $value;
		}
		return NULL;
	}
	
	
	/**
	 * @todo 获取客户端ip
	 */
	public static function getIp() {
		static $ip = null;
		if (! $ip) {
			$ip_keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'HTTP_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP', 'REMOTE_ADDR');
			if(!function_exists('filter_var')){
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
			}else{
				foreach ($ip_keys as $key) {
					if (array_key_exists($key, $_SERVER) === true ) {
						$x = explode(',', $_SERVER[$key]);
						$tmpip = filter_var(trim(end($x)), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
						if ($tmpip !== false) {
							$ip = $tmpip;
							break;
						}
					}
				}
			}
			$ip or $ip = 'Unknown';
		}
		return $ip;
	}
	
	
	/**
	 * @todo 获取cookie
	 */
	public static function getCookie($key,$autoKey,$prefix='windphp_'){
		$val =   isset($_COOKIE[$prefix.$key])?$_COOKIE[$prefix.$key] : '';
		if(empty($val)){return $val;}
		return Utils::sysAuth($val,'DECODE',$autoKey);
	}
	
	
	
	
	
}
