<?php
/**
 * @name 模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<!--{template 'Header','Common'}-->
<div class="subnav">
    <div class="content-menu ib-a blue line-x">
       <a class="on" href="javascript:;"><em>添加角色</em></a>    </div>
</div>

<div class="pad_10">
	<div class="common-form">
		<form  action="" method="post" >
			<table width="100%" class="table_form contentWrap">
				<tbody>
					<tr>
					<td>角色名称</td> 
					<td><input type="text" name="info[rolename]" value="" class="input-text" id="rolename"></td>
					</tr>
					<tr>
					<td>角色描述</td>
					<td><textarea name="info[description]" rows="2" cols="20" id="description" class="inputtext" style="height:100px;width:500px;"></textarea></td>
					</tr>
					<tr>
					<td>是否启用</td>
					<td><input type="radio" name="info[disabled]" value="0" checked=""> 启用  <label><input type="radio" name="info[disabled]" value="1">禁止</label></td>
					</tr>
				</tbody>
			</table>
		    <div class="bk15"></div>
		    <input name="doAddSubmit" type="submit" value="提交" class="button">
		</form>
	</div>
</div>

<!--{template 'Footer','Common'}-->