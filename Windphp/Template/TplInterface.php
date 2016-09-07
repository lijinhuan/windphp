<?php

/**
 * @todo 模板接口
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-9-02
 */

namespace Windphp\Template;

interface TplInterface {
	
	public function __construct();
	
	public function  show($fileName,$dirName);
	
}

?>


