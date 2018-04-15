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
       <a class="on" href="javascript:;"><em>修改部门名称</em></a>    </div>
</div>

<div class="pad_10">
	<div class="common-form">
		<form name="myform" action="" method="post" >
			<table width="100%" class="table_form contentWrap">
				
				<tr>
					<td width="80">名称</td> 
					<td><input type="text" name="info[name]" id="realname" class="input-text" size="30" value="<?php echo $this->addquote($data['name']); ?>"></input></td>
				</tr>

				</table>
				
				<div class="bk15"></div>
				<input name="doEditSubmit" type="submit" value="提交" class="button" id="dosubmit">
		</form>
	</div>
</div>

<?php include $this->getTpl('Footer','Common') ?>