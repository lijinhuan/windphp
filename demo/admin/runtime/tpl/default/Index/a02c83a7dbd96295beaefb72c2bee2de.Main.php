<?php
/**
 * @name 模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<?php include $this->getTpl('Header','Common') ?>
<div id="main_frameid" class="pad-10" style="_margin-right:-12px;_width:98.9%;">


<div class="col-2 lf mr10" style="width:48%">
	<h6>我的个人信息</h6>
	<div class="content">
	您好，<?php echo $this->addquote($user['realname']); ?><br>
	所属角色：[<?php echo implode(',',$user['rolename'])?>]<br>
	上次登录时间：<?php echo  date('Y-m-d H:i:s',$user['lastlogintime']);?><br>
	上次登录IP：<?php echo $this->addquote($user['lastloginip']); ?> <br>
	职位：<?php echo $this->addquote($user['position']); ?> <br>
	</div>
</div>
<div class="col-2 col-auto">
	<h6>系统信息</h6>
	<div class="content">
	
	操作系统：<?php echo PHP_OS;?> <br>
	服务器软件：<?php echo strpos($_SERVER['SERVER_SOFTWARE'], 'PHP')===false ? $_SERVER['SERVER_SOFTWARE'].'PHP/'.phpversion() : $_SERVER['SERVER_SOFTWARE'];?> <br>
	MySQL客户端 版本：<?php echo $mysql_version;?><br>
	上传文件：<?php echo @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';?><br>	
	</div>
</div>
<div class="bk10"></div>



<div class="col-2 lf mr10" style="width:48%">
	<h6>版权信息</h6>
	<div class="content">
	作者：kimlee<br>
	框架：windphp<br>
	github：<a target="_blank" href="https://github.com/lijinhuan">https://github.com/lijinhuan</a><br>
	</div>
</div>
<div class="bk10"></div>


</div>

<?php include $this->getTpl('Footer','Common') ?>