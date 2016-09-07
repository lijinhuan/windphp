<?php
/**
 * @todo 文件缓存
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Cache;

use Windphp\Windphp;
use Windphp\Core\Config;
class FileCache implements CacheInterface  {
	
	public  $machine = '';
	
	
	public function __construct($machine){
		$this->machine = $machine;
	}
	
	
	public function get($key){
		$file = Windphp::getRuntimeDataPath().DS.'cache'.DS.$key.'.php';
		if(is_file($file)){
			$data = require $file;
			if($data and is_string($data)){
				$data = json_decode($data,true);
				if($data['expire']==0 or (time()-$data['cache_time']<$data['expire'])){
					return $data['data'];
				}else{
					return false;	
				}
			}else{
				unlink($file);
				return false;
			}
		}else{
			return false;
		}
	}
	
	
	
	public function set($key,$value,$expire=0){
		if(empty($expire))$expire = Config::getSystem('data_default_cache_time');
		$dir = Windphp::getRuntimeDataPath().DS.'cache'.DS;
		if(!is_dir($dir)){
			if(!mkdir($dir,0777)){
				return false;
			}
		}
		$file = $dir.$key.'.php';
		$data = array();
		$data['cache_time'] = time();
		$data['expire'] = $expire;
		$data['data'] = $value;
		$str = '<?php return $str=\'' .json_encode($data)."'; ?>";
		return file_put_contents($file,$str);
	}
	
	
	public function update($key,$value,$expire=0){
		if(empty($expire))$expire = Config::getSystem('data_default_cache_time');
		return $this->set($key, $value,$expire);
	}
	
	
	public function delete($key){
		$file =  Windphp::getRuntimeDataPath().DS.'cache'.DS.$key.'.php';
		if(is_file($file)){
			return @unlink($file);
		}else{
			return true;
		}
	}
	
	
}

?>