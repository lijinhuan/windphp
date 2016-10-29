<?php
/**
 * @name 首页模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<?php include $this->getTpl('Header','Common') ?>
<script language="javascript" type="text/javascript" src="<?php echo $this->addquote($system_conf['js_url']); ?>laydate/laydate.js"></script>

<div class="subnav">
    <div class="content-menu ib-a blue line-x">
        <a href="javascript:;" class="on"><em>日志列表</em></a> 
    </div>
</div>

<div class="table-list pad-lr-10">
	<table width="100%" cellspacing="0" class="search-form">
	    <tbody>
			<tr>
				<td>
				<div class="explain-col">
					 <form method='get' action=''>						
						<input name="controller" type="hidden" value="<?php echo $controller;?>" >
						<input name="action" type="hidden" value="<?php echo $action;?>" >
						<strong> 操作员uid： </strong>
						<input name="uid" type="text" value="<?php echo  \Windphp\Web\Request::getInput('uid');?>" class="input-text">
						<strong> 开始时间： </strong>
						<input onclick="laydate({istime:true,format:'YYYY-MM-DD hh:mm:ss'})" name="start_time" type="text" value="<?php echo  \Windphp\Web\Request::getInput('start_time');?>" class="input-text">
						<strong> 结束时间： </strong>
						<input onclick="laydate({istime:true,format:'YYYY-MM-DD hh:mm:ss'})" name="end_time" type="text" value="<?php echo  \Windphp\Web\Request::getInput('end_time');?>" class="input-text">
						<input type="submit" name="dosearch" class="button" value="搜索">
					 </form>
				</div>
				</td>
			</tr>
	    </tbody>
	</table>


    <table width="100%" cellspacing="0">
        <thead>
			<tr>
			<th width="5%">ID</th>
			<th width="8%" align="center">操作员id</th>
			<th width="10%" align="center">操作员名称</th>
			<th width="8%" align="center">控制器-方法</th>
			<th width="8%" align="center">操作菜单</th>
			<th width="8%" align="center">get</th>
			<th width="8%" align="center">post</th>
			<th width="8%" align="center">file</th>
			<th width="8%" align="center">ip</th>
			<th width="20%" align="center">时间</th>
			</tr>
        </thead>
		<tbody>
			<?php if(is_array($result)) foreach($result AS $val) { ?>
			<tr>
			<td width="5%"><?php echo $this->addquote($val['id']); ?></td>
			<td width="8%" align="center"><?php echo $this->addquote($val['uid']); ?></td>
			<td width="10%" align="center"><?php echo $this->addquote($val['realname']); ?></td>
			<td width="8%" align="center"><?php echo $this->addquote($val['controller']); ?>-<?php echo $this->addquote($val['action']); ?></td>
			<td width="8%" align="center"><?php echo $this->addquote($val['menuname']); ?></td>
			<td width="5%" align="center"><?php echo $this->addquote($val['get']); ?></td>
			<td width="5%" align="center"><?php echo $this->addquote($val['post']); ?></td>
			<td width="5%" align="center"><?php echo $this->addquote($val['file']); ?></td>
			<td width="8%" align="center"><?php echo $this->addquote($val['ip']); ?></td>
			<td width="20%" align="center"><?php echo  date('Y-m-d H:i:s',$val['addtime']);?></td>
			
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
<?php include $this->getTpl('Footer','Common') ?>
