<?php
/**
 * @name 模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<?php include $this->getTpl('Header','Common') ?>
<div class="subnav">
    <div class="content-menu ib-a blue line-x">
       <a class="on" href="javascript:;"><em>修改个人信息</em></a>    </div>
</div>

<div class="pad_10">
	<div class="common-form">
		<form name="myform" action="" method="post" >
			<table width="100%" class="table_form contentWrap">
				<tr>
					<td width="80">帐号</td> 
					<td><?php echo $this->addquote($info['username']); ?></td>
				</tr>

				<tr>
					<td width="80">角色</td> 
					<td>
						<?php echo $this->addquote($info['rolename']); ?>
					</td>
				</tr>

				
				<tr>
				<td width="80">部门</td> 
				<td>
					<?php echo $this->addquote($info['department_name']); ?>
				</td>
				</tr>
				
				<tr>
				<td>职位*</td>
				<td>
				 	<?php echo $this->addquote($info['position']); ?>
				</td>
				</tr>
				
				<tr>
				<td>真实姓名*</td>
				<td>
				<input type="text" name="info[realname]"  class="input-text" size="30" value="<?php echo $this->addquote($info['realname']); ?>" />
				</td>
				</tr>
				
				<tr>
				<td>邮箱*</td>
				<td>
				<input type="text" name="info[email]"  class="input-text" size="30" value="<?php echo $this->addquote($info['email']); ?>" />
				</td>
				</tr>	
				
				<tr>
				<td>手机*</td>
				<td>
				<input type="text" name="info[phone]"  class="input-text" size="30" value="<?php echo $this->addquote($info['phone']); ?>" />
				</td>
				</tr>
				
				<tr>
				<td>QQ*</td>
				<td>
				<input type="text" name="info[qq]"  class="input-text" size="30" value="<?php echo $this->addquote($info['qq']); ?>" />
				</td>
				</tr>
				
				</table>
				
				<div class="bk15"></div>
				<input name="doEditSubmit" type="submit" value="提交" class="button" id="dosubmit">
		</form>
	</div>
</div>

<?php include $this->getTpl('Footer','Common') ?>