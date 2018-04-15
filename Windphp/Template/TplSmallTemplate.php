<?php

/**
 * @todo 模板解析引擎
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-9-02
 */

namespace Windphp\Template;

use Windphp\Core\Config;
use Windphp\Web\Request;
use Windphp\Windphp;
use Windphp\Misc\FileDir;
use Windphp\Core\UrlRoute;
class TplSmallTemplate implements TplInterface {
	
	public $vars = array();			//变量表
	public $force = 1;		// 强制判断文件是否过期，会影响效率
	public $var_regexp = "\@?\\\$[a-zA-Z_]\w*(?:\[[\w\.\"\'\$]+\])*";
	public $vtag_regexp = "\<\?=(\@?\\\$[a-zA-Z_]\w*(?:\[[\w\.\"\'\[\]\$]+\])*)\?\>";
	public $const_regexp = "";
	public $conf = array();
	public $json = array();
	public $tag_left = "\<\!\-\-\{";
	public $tag_right = "\}\-\-\>";
	
	
	public function __construct(){
		$this->conf = Config::$systemConfig;
		$this->conf['action'] = UrlRoute::$current_action;
		$this->conf['controller'] = UrlRoute::$current_controller;
		if(isset($this->conf['tpl_tag']) and !empty($this->conf['tpl_tag'])){
			$this->tag_left = $this->conf['tpl_tag']['left'];
			$this->tag_right = $this->conf['tpl_tag']['right'];
		}
		$this->const_regexp = $this->tag_left."([\w]+)".$this->tag_right; 
	}
	
	
	public function  show($fileName='',$dirName='',$return=false){
		extract($this->vars,1);
		if(empty($fileName))$fileName=$this->conf['action'];
		if(empty($dirName))$dirName=$this->conf['controller'];
		$system_conf = $this->conf;
		$style_url = Windphp::getViewsPath().$system_conf['template_theme'].DS.'style'.DS;
		$tpl =  $this->getTpl($fileName,$dirName);
		if($return) {
			return $tpl;
		}else{
			include $tpl;
		}
	}
	
	
	
	//获取编译后的模板
	public function getTpl($file,$dirName=''){
		if($dirName)$dirName = $dirName.'/';
		$objfile = Windphp::getRuntimeTplPath().$this->conf['template_theme'].'/'.$dirName.md5($file).'.'.str_replace(array('\\','/'), '.', $file).'.php';
		$objfile = str_replace(array('\\','/'), DS, $objfile);
		if(!is_file($objfile) || $this->conf['debug'] > 0) {
			$file = Windphp::getViewsPath().$this->conf['template_theme'].'/'.$dirName.'tpl.'.$file.'.php';
			$file = str_replace(array('\\','/'), DS, $file);
			if(!is_file($file)) {
				throw new \Exception("模板文件 ".$file." 不存在。");
			}
			$filemtime = filemtime($file);
			if(!$filemtime) {
				throw new \Exception("模板文件 $filename 最后更新时间读取失败。");
			}
			$filemtimeold = is_file($objfile) ? filemtime($objfile) : 0;
			//判断是否比较过期
			if($filemtimeold < $filemtime || $this->conf['debug'] > 0) {
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
		$str = preg_replace ( "/".$this->tag_left."template\s+(.+)".$this->tag_right."/", "<?php include \$this->getTpl(\\1) ?>", $str );
		$str = preg_replace ( "/".$this->tag_left."include\s+(.+)".$this->tag_right."/", "<?php include \\1; ?>", $str );
		$str = preg_replace ( "/".$this->tag_left."php\s+(.+)".$this->tag_right."/", "<?php \\1?>", $str );
		$str = preg_replace ( "/".$this->tag_left."if\s+(.+?)".$this->tag_right."/", "<?php if(\\1) { ?>", $str );
		$str = preg_replace ( "/".$this->tag_left."else".$this->tag_right."/", "<?php } else { ?>", $str );
		$str = preg_replace ( "/".$this->tag_left."elseif\s+(.+?)".$this->tag_right."/", "<?php } elseif (\\1) { ?>", $str );
		$str = preg_replace ( "/".$this->tag_left."\/if".$this->tag_right."/", "<?php } ?>", $str );
		//for 循环
		$str = preg_replace("/".$this->tag_left."for\s+(.+?)".$this->tag_right."/","<?php for(\\1) { ?>",$str);
		$str = preg_replace("/".$this->tag_left."\/for".$this->tag_right."/","<?php } ?>",$str);
		//++ --
		$str = preg_replace("/".$this->tag_left."\+\+(.+?)".$this->tag_right."/","<?php ++\\1; ?>",$str);
		$str = preg_replace("/".$this->tag_left."\-\-(.+?)".$this->tag_right."/","<?php ++\\1; ?>",$str);
		$str = preg_replace("/".$this->tag_left."(.+?)\+\+".$this->tag_right."/","<?php \\1++; ?>",$str);
		$str = preg_replace("/".$this->tag_left."(.+?)\-\-".$this->tag_right."/","<?php \\1--; ?>",$str);
		$str = preg_replace ( "/".$this->tag_left."loop\s+(\S+)\s+(\S+)".$this->tag_right."/", "<?php if(is_array(\\1)) foreach(\\1 AS \\2) { ?>", $str );
		$str = preg_replace ( "/".$this->tag_left."loop\s+(\S+)\s+(\S+)\s+(\S+)".$this->tag_right."/", "<?php  if(is_array(\\1)) foreach(\\1 AS \\2 => \\3) { ?>", $str );
		$str = preg_replace ( "/".$this->tag_left."\/loop".$this->tag_right."/", "<?php } ?>", $str );
		$str = preg_replace ( "/".$this->tag_left."([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))".$this->tag_right."/", "<?php echo \\1;?>", $str );
		$str = preg_replace ( "/".$this->tag_left."\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))".$this->tag_right."/", "<?php echo \\1;?>", $str );
		$str = preg_replace ( "/".$this->tag_left."(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)".$this->tag_right."/", "<?php echo \\1;?>", $str );
		$str = preg_replace("/".$this->tag_left."(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)".$this->tag_right."/s", "<?php echo \$this->addquote(\\1); ?>",$str);
		$str = preg_replace("/".$this->tag_left."(\$this\-\>\w+\(.*?\))".$this->tag_right."/is", "<?php echo \\1;?>", $str); // 变量
		$str = preg_replace("/".$this->tag_left."func\s+(.*?)".$this->tag_right."/is", "<?php  \\1;?>", $str); // 变量
		$str = preg_replace("/".$this->tag_left."funcecho\s+(.*?)".$this->tag_right."/is", "<?php echo  \\1;?>", $str); // 变量
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
		if(empty($fileName))$fileName=$this->conf['action'];
		if(empty($dirName))$dirName=$this->conf['controller'];
		return sprintf( "%s%s".DS."tpl.%s.php", Windphp::getViewsPath(), $dirName, $fileName );
	}
	

}

?>
