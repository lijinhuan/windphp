<?php
/**
 * @name 首页模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<?php include $this->getTpl('Header','Common') ?>
<div class="pad_10">
<div class="subnav">
    <div class="content-menu ib-a blue line-x">
    <a class="add fb" href="javascript:edit('添加联动菜单','<?php echo  \Windphp\Core\UrlRoute::getWebUrl('Linkage-Cadd-parentid-'.$parentid.'-keyid-'.$keyid);?>')"><em>添加联动菜单</em></a>　    <a href="javascript:;" class="on"><em>联动菜单</em></a>    </div>
</div>
<div class="bk10"></div>
<form name="myform" controller="" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
		<tr>
		<th width="5%">排序</th>
		<th width="10%">ID</th>
		<th width="20%" align="left" >名称</th>
		<th width="30%" align="left" >描述</th>
		
		<th width="20%" >操作</th>
		</tr>
        </thead>
        <tbody>
		<?php if(is_array($result)) foreach($result AS $linkage) { ?>
		<tr>
		<td width="5%" align="center">
			<input type="text" size="3" value="<?php echo $this->addquote($linkage['listorder']); ?>">
		</td>
		<td width="10%" align="center"><?php echo $this->addquote($linkage['id']); ?></td>
		<td width="20%" ><?php echo $this->addquote($linkage['name']); ?></td>
		<td width="30%" ><?php echo $this->addquote($linkage['description']); ?></td>
		
		<td width="20%" class="text-c">
			<?php if($linkage['is_last_node']) { ?>
			<a href="?controller=Linkage&action=Submenu&keyid=<?php echo $keyid;?>&parentid=<?php echo $this->addquote($linkage['id']); ?>">管理子菜单</a> |
			<?php } ?>
			<a href="javascript:void(0);" onclick="edit('添加子菜单','<?php echo  \Windphp\Core\UrlRoute::getWebUrl('Linkage-Cadd-parentid-'.$linkage['id'].'-keyid-'.$keyid);?>')">添加子菜单</a>  |
			<a href="javascript:void(0);" onclick="edit('编辑','<?php echo  \Windphp\Core\UrlRoute::getWebUrl('Linkage-Cedit-id-'.$linkage['id'].'-parentid-'.$parentid.'-keyid-'.$keyid);?>')">编辑</a> |
			 <a href="javascript:confirmurl('index.php?controller=linkage&action=cdel&id=<?php echo $this->addquote($linkage['id']); ?>', '是否删除?')">删除</a>
		</td>
		</tr>
		<?php } ?>
</tbody>
</table>
<div class="btn"><input type="submit" class="button" name="dosubmit" value="排序" /></div>  </div>
</div>
</div>
</form>

<?php include $this->getTpl('Footer','Common') ?>
