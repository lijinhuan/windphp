<?php
/**
 * @name 首页模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<?php include $this->getTpl('Header','Common') ?>

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
			<?php if(is_array($result)) foreach($result AS $val) { ?>
			<tr>
			<td width="10%" align="center"><?php echo $this->addquote($val['id']); ?></td>
			<td width="15%"><?php echo $this->addquote($val['rolename']); ?></td>
			<td width="265"><?php echo $this->addquote($val['description']); ?></td>
			<td width="5%">
				<?php if($val['disabled']) { ?>
				<font color="green">x</font>
				<?php } else { ?>
				<font color="red">√</font>
				<?php } ?>
			</td>
			<td class="text-c">
			<?php if($val['id']!=1) { ?>
			<a href="javascript:setting_role(<?php echo $this->addquote($val['id']); ?>, '<?php echo $this->addquote($val['rolename']); ?>')">权限设置</a> | 
			<a href="javascript:edit('<?php echo $this->addquote($val['rolename']); ?>','index.php?controller=role&action=cedit&id=<?php echo $this->addquote($val['id']); ?>')">修改</a> | 
			<a href="javascript:confirmurl('index.php?controller=role&action=Cdel&id=<?php echo $this->addquote($val['id']); ?>', '是否删除?')">删除</a>
			<?php } ?>
			</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<div id="pages">
		<div class="pre">
		  共找到 <?php echo $total_count;?> 条记录，
		  当前页（<?php echo $current_page;?>/<?php echo $total_count;?>页）
		</div>
		<div class="list"><?php echo $page;?></div>
	</div>
</div>
<script type="text/javascript">
function setting_role(id, name) {
	window.top.art.dialog({title:'设置《'+name+'》',id:'edit',iframe:'?controller=role&action=privSetting&roleid='+id,width:'700',height:'500'});
}
</script>


<?php include $this->getTpl('Footer','Common') ?>
