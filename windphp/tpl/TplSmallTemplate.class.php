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
  

class TplSmallTemplate implements TplInterface {
	
	public $vars = array();			//变量表
	public $force = 1;		// 强制判断文件是否过期，会影响效率
	public $var_regexp = "\@?\\\$[a-zA-Z_]\w*(?:\[[\w\.\"\'\$]+\])*";// \[\]
	public $vtag_regexp = "\<\?=(\@?\\\$[a-zA-Z_]\w*(?:\[[\w\.\"\'\[\]\$]+\])*)\?\>";
	public $const_regexp = "\{([\w]+)\}";
	public $conf = array();
	public $json = array();
	
	
	public function __construct($conf){
		$this->conf = $conf;
	}
	
	
	public function  show($fileName,$dirName=''){
		extract($this->vars,1);
		$system_conf = $this->conf;
		$style_url = $system_conf['app_url'].'views/'.$system_conf['template_theme'].'/style/';
		include $this->getTpl($fileName,$dirName);
	}
	
	
	
	//获取编译后的模板
	public function getTpl($file,$dirName=''){
		if($dirName)$dirName = $dirName.'/';
		$objfile = APP_PATH.'runtime/tpl/'.$this->conf['template_theme'].'/'.$dirName.md5($file).'.'.str_replace(array('\\','/'), '.', $file).'.php';
		$objfile = str_replace(array('\\','/'), '/', $objfile);
		if(!is_file($objfile) || DEBUG > 0) {
			$file = APP_PATH.'views/'.$this->conf['template_theme'].'/'.$dirName.'tpl.'.$file.'.php';
			$file = str_replace(array('\\','/'), '/', $file);
			if(!is_file($file)) {
				throw new Exception("模板文件 ".$file." 不存在。");
			}
			$filemtime = filemtime($file);
			if(!$filemtime) {
				throw new Exception("模板文件 $filename 最后更新时间读取失败。");
			}
			$filemtimeold = is_file($objfile) ? filemtime($objfile) : 0;
			//判断是否比较过期
			if($filemtimeold < $filemtime || DEBUG > 0) {
				$s = $this->complie($file,$dirName);
				FileDir::mkdir(dirname($objfile));
				file_put_contents($objfile, $s);
			}
		}
		return $objfile;
	}
	
	
	
	//编译
	public function complie($viewfile,$dirName) {
		$conf = $this->conf;
		$str = file_get_contents($viewfile);
		$str = preg_replace ( "/\{template\s+(.+)\}/", "<?php include \$this->getTpl(\\1) ?>", $str );
		$str = preg_replace ( "/\{include\s+(.+)\}/", "<?php include \\1; ?>", $str );
		$str = preg_replace ( "/\{php\s+(.+)\}/", "<?php \\1?>", $str );
		$str = preg_replace ( "/\{if\s+(.+?)\}/", "<?php if(\\1) { ?>", $str );
		$str = preg_replace ( "/\{else\}/", "<?php } else { ?>", $str );
		$str = preg_replace ( "/\{elseif\s+(.+?)\}/", "<?php } elseif (\\1) { ?>", $str );
		$str = preg_replace ( "/\{\/if\}/", "<?php } ?>", $str );
		//for 循环
		$str = preg_replace("/\{for\s+(.+?)\}/","<?php for(\\1) { ?>",$str);
		$str = preg_replace("/\{\/for\}/","<?php } ?>",$str);
		//++ --
		$str = preg_replace("/\{\+\+(.+?)\}/","<?php ++\\1; ?>",$str);
		$str = preg_replace("/\{\-\-(.+?)\}/","<?php ++\\1; ?>",$str);
		$str = preg_replace("/\{(.+?)\+\+\}/","<?php \\1++; ?>",$str);
		$str = preg_replace("/\{(.+?)\-\-\}/","<?php \\1--; ?>",$str);
		$str = preg_replace ( "/\{loop\s+(\S+)\s+(\S+)\}/", "<?php if(is_array(\\1)) foreach(\\1 AS \\2) { ?>", $str );
		$str = preg_replace ( "/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/", "<?php  if(is_array(\\1)) foreach(\\1 AS \\2 => \\3) { ?>", $str );
		$str = preg_replace ( "/\{\/loop\}/", "<?php } ?>", $str );
		$str = preg_replace ( "/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str );
		$str = preg_replace ( "/\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str );
		$str = preg_replace ( "/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/", "<?php echo \\1;?>", $str );
		$str = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/s", "{\$this->addquote(\\1)}",$str);
		$str = preg_replace('/\{(\$this\-\>\w+\(.*?\))\}/is', "<?php echo \\1;?>", $str); // 变量
		$str = preg_replace('/\{func\s+(.*?)\}/is', "<?php  \\1;?>", $str); // 变量
		$str = preg_replace('/\{funcecho\s+(.*?)\}/is', "<?php echo  \\1;?>", $str); // 变量
		$str = "<?php defined('FRAMEWORK_PATH') or exit('No permission resources.'); ?>" . $str;
		return $str;
	}
	
	
	/**
	 * 转义 // 为 /
	 *
	 * @param $var	转义的字符
	 * @return 转义后的字符
	 */
	public function addquote($var) {
		return str_replace ( "\\\"", "\"", preg_replace ( "/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var ) );
	}
	
	
	// publlic
	public function assign($k, &$v) {
		$this->vars[$k] = &$v;
	}
	
	
	
	public function getFile($fileName,$dirName='default'){
		return sprintf( "%s%s/tpl.%s.php", APP_PATH.'views/', $dirName, $fileName );
	}
	

}

?>
