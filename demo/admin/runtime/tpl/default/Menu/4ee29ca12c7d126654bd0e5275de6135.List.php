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
        <a href="<?php echo  \Windphp\Core\UrlRoute::getWebUrl('Menu-List');?>" class="on"><em>一级菜单列表</em></a><span>|</span><a href="<?php echo  \Windphp\Core\UrlRoute::getWebUrl('Menu-Add');?>"><em>添加一级菜单</em></a>    </div>
</div>





<form name="myform" action="" method="post">
<div class="pad-lr-10">
<?php if($parent) { ?>
<div class="explain-col">查看 【<?php echo $this->addquote($parent['name']); ?>】 下的所有菜单</div>
<div class="bk10"></div>
<?php } ?> 
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="80">排序</th>
            <th width="100">id</th>
            <th>菜单名称</th>
			<th>菜单管理</th>
            </tr>
        </thead>
	<tbody>
    <?php echo $categorys;?>
	</tbody>
    </table>
  
    <div class="btn"><input type="submit" class="button" name="dosubmit" value="排序" /></div>  </div>
</div>
</div>
</form>


<?php include $this->getTpl('Footer','Common') ?>