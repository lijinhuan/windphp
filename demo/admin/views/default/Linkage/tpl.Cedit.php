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
       <a class="on" href="javascript:;"><em>修改联动菜单</em></a>    </div>
</div>

<div class="pad_10">
	<div class="common-form">
		<form  action="" method="post" >
			<table width="100%" class="table_form contentWrap">
				<tbody>
					<tr>
					<td>菜单名称</td> 
					<td><input type="text" name="info[name]" value="<!--{$data['name']}-->" class="input-text" ></td>
					</tr>
					<tr>
					<td>菜单描述</td>
					<td><textarea name="info[description]" rows="2" cols="20"  class="inputtext" style="height:100px;width:500px;"><!--{$data['description']}--></textarea></td>
					</tr>
					<!--{if isset($_GET['parentid']) && isset($_GET['keyid']) }-->
					
					<td>排序</td> 
					<td>
						<input type="text" name="info[listorder]" value="<!--{$data['listorder']}-->" class="input-text" >
						<input type="hidden" name="info[keyid]" value="<!--{funcecho intval(\Windphp\Web\Request::getInput('keyid'))}-->" class="input-text" >
						<input type="hidden" name="info[parentid]" value="<!--{funcecho intval(\Windphp\Web\Request::getInput('parentid'))}-->" class="input-text" >
					</td>
					<!--{/if}-->
				</tbody>
			</table>
		    <div class="bk15"></div>
		    
		    <input name="doEditSubmit" id="dosubmit" type="submit" value="提交" class="button">
		</form>
	</div>
</div>

<!--{template 'Footer','Common'}-->