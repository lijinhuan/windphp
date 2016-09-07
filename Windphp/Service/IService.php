<?php
/**
 * @todo 配置类
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Service;

use Windphp\Windphp;
class IService {
	
	public $noCache =false;
	
	
	
	public function __get($var) {
		if(ucfirst(substr($var, -3))==='Dao') {
			$dao = substr($var,0, -3);
			return Windphp::getDao($dao);	
		}elseif(ucfirst($var)==='Cache') {
			return Windphp::getCache();
		}elseif(ucfirst(substr($var, -8))==='Memcache') {
			$memcache_machine = substr($var,0, -8);
			return Windphp::getCache('memcache',$memcache_machine);
		}elseif(ucfirst(substr($var, -5))==='Redis') {
			$redis_machine = substr($var,0, -5);
			return Windphp::getCache('redis',$redis_machine);
		}
	}
	
	
	
	/**
	 * @todo 错误返回
	 * @param integer $code 负数
	 * @param string $msg 错误消息
	 */
	public function error($msg,$code=-1) {
		return array('code'=>$code,'msg'=>$msg);	
	}
	
	

	/**
	 *  @todo 成功返回
	 *  
	 */
	public function success($data,$code=1) {
		return array('code'=>$code,'data'=>$data);
	} 
    
}
