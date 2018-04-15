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
        <a href="javascript:;" class="on"><em>部门列表</em></a> 
        <a href="javascript:edit('添加部门','?controller=Department&action=Cadd');"  ><em>添加部门</em></a> 
    </div>
</div>

<form name="myform" controller="" method="post">
<div class="pad-lr-10">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            
            <th width="100">id</th>
            <th>部门名称</th>
			<th>部门管理</th>
            </tr>
        </thead>
	<tbody>
    <?php echo $categorys;?>
	</tbody>
    </table>
  
  </div>
</div>
</div>
</form>


<?php include $this->getTpl('Footer','Common') ?>