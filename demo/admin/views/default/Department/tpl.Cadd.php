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
       <a class="on" href="javascript:;"><em>添加部门</em></a>    </div>
</div>

<div class="pad_10">
	<div class="common-form">
		<form name="myform" action="" method="post" >
			<table width="100%" class="table_form contentWrap">
				<tr>
				<td width="80">上级部门</td> 
				<td>
					<select name="info[parentid]">
					<option value="0" >无</option>
					<!--{$department}-->
					</select>
				</td>
				</tr>
				
				<tr>
					<td width="80">名称</td> 
					<td><input type="text" name="info[name]" id="realname" class="input-text" size="30" value=""></input></td>
				</tr>

				</table>
				
				<div class="bk15"></div>
				<input name="doAddSubmit" type="submit" value="提交" class="button" id="dosubmit">
		</form>
	</div>
</div>

<!--{template 'Footer','Common'}-->