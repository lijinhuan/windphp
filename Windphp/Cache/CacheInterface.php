<?php
/**
 * @todo 缓存接口
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-8-26
 */

namespace Windphp\Cache;


interface CacheInterface {

	public function __construct($machine);

	public function set($key,$value,$expire);
	
	public function update($key,$value,$expire);
	
	public function get($key);
	
	public function delete($key);
}



?>