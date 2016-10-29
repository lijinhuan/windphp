<?php
/**
 * @name 首页模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<!--{template 'Header','Common'}-->

<div class="subnav">
    <div class="content-menu ib-a blue line-x">
        <a href="javascript:;" class="on"><em>角色列表</em></a> 
    </div>
</div>

<div class="table-list pad-lr-10">
    <table width="100%" cellspacing="0">
        <thead>
			<tr>
			<th width="10%">ID</th>
			<th width="15%" align="left">角色名称</th>
			<th width="265" align="left">角色描述</th>
			<th width="5%" align="left">状态</th>
			<th class="text-c">管理操作</th>
			</tr>
        </thead>
		<tbody>
			<!--{loop $result $val}-->
			<tr>
			<td width="10%" align="center"><!--{$val['id']}--></td>
			<td width="15%"><!--{$val['rolename']}--></td>
			<td width="265"><!--{$val['description']}--></td>
			<td width="5%">
				<!--{if $val['disabled']}-->
				<font color="green">x</font>
				<!--{else}-->
				<font color="red">√</font>
				<!--{/if}-->
			</td>
			<td class="text-c">
			<!--{if $val['id']!=1}-->
			<a href="javascript:setting_role(<!--{$val['id']}-->, '<!--{$val['rolename']}-->')">权限设置</a> | 
			<a href="javascript:edit('<!--{$val['rolename']}-->','index.php?controller=role&action=cedit&id=<!--{$val['id']}-->')">修改</a> | 
			<a href="javascript:confirmurl('index.php?controller=role&action=Cdel&id=<!--{$val['id']}-->', '是否删除?')">删除</a>
			<!--{/if}-->
			</td>
			</tr>
			<!--{/loop}-->
		</tbody>
	</table>
	<div id="pages">
		<div class="pre">
		  共找到 <!--{$total_count}--> 条记录，
		  当前页（<!--{$current_page}-->/<!--{funcecho ceil($total_count/\Windphp\Core\Config::getSystem('page_rows'))}-->页）
		</div>
		<div class="list"><!--{$page}--></div>
	</div>
</div>
<script type="text/javascript">
function setting_role(id, name) {
	window.top.art.dialog({title:'设置《'+name+'》',id:'edit',iframe:'?controller=role&action=privSetting&roleid='+id,width:'700',height:'500'});
}
</script>


<!--{template 'Footer','Common'}-->
