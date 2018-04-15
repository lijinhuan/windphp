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
		<th width="6%">用户ID</th>
		<th width="7%" align="left" >用户名称</th>
		<th width="8%" align="left" >用户角色</th>
		<th width="8%"  align="left" >最后登录ip</th>
		<th width="14%"  align="left" >最后登录时间</th>
		<th width="10%"  align="left" >邮箱</th>
		<th width="6%" align="left" >部门</th>
		<th width="9%" align="left" >手机</th>
		<th width="10%"  align="left" >qq</th>
		<th width="8%">真实用户名</th>
		<th width="8%">职位</th>
		<th width="35%" >操作</th>
		</tr>
        </thead>
		<tbody>
			<?php if(is_array($result)) foreach($result AS $info) { ?>
			<tr>
			<td width="6%" align="center"><?php echo $this->addquote($info['id']); ?></td>
			<td width="7%" ><?php echo $this->addquote($info['username']); ?></td>
			<td width="8%" ><?php echo $this->addquote($info['rolename']); ?></td>
			<td width="8%" ><?php echo $this->addquote($info['lastloginip']); ?></td>
			<td width="14%"  ><?php if($info['lastlogintime']) { ?><?php echo  date('Y-m-d H:i:s',$info['lastlogintime']);?><?php } ?></td>
			<td width="10%"><?php echo $this->addquote($info['email']); ?></td>
			<td width="6%" align="left" ><?php echo $this->addquote($info['department_name']); ?></td>
			<td width="9%" align="left" ><?php echo $this->addquote($info['phone']); ?></td>
			<td width="10%"  align="left" ><?php echo $this->addquote($info['qq']); ?></td>
			<td width="8%"  align="center"><?php echo $this->addquote($info['realname']); ?></td>
			<td width="8%"  align="center"><?php echo $this->addquote($info['position']); ?></td>
			<td width="35%" align="center">
				<a href="javascript:edit('<?php echo $this->addquote($info['realname']); ?>', 'index.php?controller=administrators&action=cedit&id=<?php echo $this->addquote($info['id']); ?>')">修改</a> | 
				<a href="javascript:confirmurl('index.php?controller=administrators&action=cdel&id=<?php echo $this->addquote($info['id']); ?>', '是否删除该管理员?')">删除</a>
			</td>
		</tr>
			<?php } ?>
		</tbody>
	</table>
	<div id="pages">
		<div class="pre">
		  共找到 <?php echo $total_count;?> 条记录，
		  当前页（<?php echo $current_page;?>/<?php echo  ceil($total_count/\Windphp\Core\Config::getSystem('page_rows'));?>页）
		</div>
		<div class="list"><?php echo $page;?></div>
	</div>
</div>
<?php include $this->getTpl('Footer','Common') ?>
