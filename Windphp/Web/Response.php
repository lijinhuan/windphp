<?php
/**
 * @todo web 响应类
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Web;
use Windphp\Misc\Utils;

class Response {
	
	
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
			@header ("Pragma: public");
		}
		if($charset)
			@header("Content-type: " . $type . "; charset=" . $charset);
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
	public static function setCookie($var, $value = '', $autoKey,$life = 0, $prefix = 'windphp_',$path='',$domain='', $httpOnly = false,$secure=0){
		$var = $prefix.$var;
		if($value == '' || $life < 0){
			$value = '';
			$life = -1;
		}
		$life = $life > 0 ? time() + $life : ($life < 0 ? time() - 31536000 : 0);
		if(empty($path)){
			$path = $httpOnly && PHP_VERSION < '5.2.0' ? '/; HttpOnly' : '/';
		}
		if($secure)$secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
		if(PHP_VERSION < '5.2.0'){
			setcookie($var,Utils::sysAuth($value,'ENCODE',$autoKey), $life, $path, $domain, $secure);
		}else{
			setcookie($var,Utils::sysAuth($value,'ENCODE',$autoKey), $life, $path, $domain, $secure, $httpOnly);
		}
	}
	
	
	/**
	 * @todo json格式
	 * @param array $arr
	 * @param string $callback
	 * @param number $type
	 */
	public static function JsonFormat($arr,$callback='',$type=0){
		if(is_string($arr))return $arr;
		if($callback)return $callback.'('.json_encode($arr,$type).')';
		else return json_encode($arr,$type);
	}
	
	
	/**
	 * 提示信息页面跳转，跳转地址如果传入数组，页面会提示多个地址供用户选择，默认跳转地址为数组的第一个值，时间为5秒。
	 * showmessage('登录成功', array('默认跳转地址'=>'http://www.test.cn'));
	 * @param string $msg 提示信息
	 * @param mixed(string/array) $url_forward 跳转地址
	 * @param int $ms 跳转等待时间
	 */
	public static function showMessage($msg, $urlForward = 'goback',$type='pc', $ms = 3000, $dialog = '', $returnjs = '') {
		$str='<html>
				<head>
				  	<meta charset="utf-8">
				    <meta http-equiv="X-UA-Compatible" content="IE=edge">
				    <META HTTP-EQUIV="Pragma" CONTENT="no-cache"> 
					<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache"> 
					<META HTTP-EQUIV="Expires" CONTENT="0"> 
				    <title>消息提示</title>';
		if($type=='pc')	{
			$str .= '
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
					</style>';
		}else	{
			$str .= "
					<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
					<style type=\"text/css\">
					body{font-family: 'seanfont', \"microsoft yahei\";font-size: 14px;line-height: 22px;color: #333;}
					.layermbox{position: relative;z-index: 19891014;}
					.laymshade{background-color: rgba(0,0,0, .5);pointer-events: auto;}
					.laymshade, .layermmain{position: fixed;left: 0;top: 0;width: 100%;height: 100%;}
					.layermmain{display: table;font-family: Helvetica, arial, sans-serif;pointer-events: none;}
					.layermmain .section{display: table-cell;text-align: center;}
					.layermbox0 .layermchild{max-width: 90%;min-width: 150px;margin-top:130px;}
					.layermcont{padding: 20px 15px;line-height: 22px;text-align: center;}
					.layermchild{position: relative;display: inline-block;text-align: left;background-color: #fff;font-size: 14px;border-radius: 3px;box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);pointer-events: auto;}
					</style>";
		}
		$str .= '</head><body>';
		
		if($type=='pc'){
			$str .='<div class="showMsg" style="text-align:center">
						<h5>消息提示</h5>
					    <div class="content guery" style="display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline;max-width:330px">'.$msg.'</div>
					    <div class="bottom">';
						if($urlForward=='goback' || $urlForward=='') {
							$str = $str.'<a href="javascript:history.back();" >返回</a>';
						}elseif($urlForward=="close") {
							$str = $str.'<input type="button" name="close" value="关闭 " onClick="window.close();">';
						}elseif($urlForward=="blank") {
						}elseif($urlForward) {
							$str = $str."系统将在 <b id=\"wait\" style=\"color:blue\">".ceil($ms/1000)."</b> 秒后自动跳转&nbsp;";
							$str = $str.'<a href="'.$urlForward.'">无法跳转？请点击这里</a>';
							$str = $str.'<script language="javascript">setTimeout("redirect(\''.$urlForward.'\');",'.$ms.');</script> ';
						}
						if($returnjs) {
							$str = $str.'<script style="text/javascript">'.$returnjs.'</script>';
						}
						if ($dialog){
							$str = $str.'<script style="text/javascript">window.top.right.location.reload();window.top.art.dialog({id:"'.$dialog.'"}).close();</script>';
						}
			$str = $str.'</div></div>';
			$str.='<script style="text/javascript">
							function close_dialog() {
								window.top.right.location.reload();window.top.art.dialog({id:"'.$dialog.'"}).close();
							}
							function redirect(url) {
								location.href = url;
							}
						    function jump() {
						        var wait = document.getElementById("wait"), time = 3;
						        var interval = setInterval(function(){
						            var time = --wait.innerHTML;
						            --time;
						            if(time <= 0) {
						                clearInterval(interval);
						            };
						        }, 1000);
						    }
							jump();
						</script>';
		}else { 
			$str .= '<div id="layermbox0" class="layermbox layermbox0" index="0">
						<div class="laymshade"></div>
						<div class="layermmain">
							<div class="section">
								<div class="layermchild  layermanim">
									<div class="layermcont">'.$msg.' (<span id="wait" style="color:gray"> '.ceil($ms/1000).' </span>)</div>
								</div>
							</div>
						</div>
					</div>';
			$str .= "<script language=\"javascript\">
					     setTimeout(\"redirect('{$urlForward}');\",{$ms});
					 	 function redirect(url){
					     	if(url){
								window.location.href = url;
							}
						 }
					     function jump() {
						        var wait = document.getElementById(\"wait\"), time = 3;
						        var interval = setInterval(function(){
						            var time = --wait.innerHTML;
						            --time;
						            if(time <= 0) {
						                clearInterval(interval);
						            };
						        }, 1000);
						}
					    jump();
				 	</script>";
		}
		$str .= '</body></html>';
		exit($str);
	}
	
	
	
}
