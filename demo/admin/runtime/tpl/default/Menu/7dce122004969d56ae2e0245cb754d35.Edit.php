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
          <a href="<?php echo  \Windphp\Core\UrlRoute::getWebUrl('Menu-List');?>" ><em>菜单管理</em></a><span>|</span><a href="<?php echo  \Windphp\Core\UrlRoute::getWebUrl('Menu-Add');?>"><em>添加一级菜单</em></a><span>|</span> <a href="javascript://" class="on"><em>修改菜单</em></a>    
    </div>
</div>

<div class="common-form">
<form name="myform" id="myform" action="" method="post">
<table width="100%" class="table_form contentWrap">
  <tbody>
      <tr>
        <th> 对应的中文语言名称：</th>
        <td><input type="text" name="info[name]" value="<?php echo $this->addquote($menu['name']); ?>" id="language" class="input-text"><div id="languageTip" class="onShow">请输入对应的中文语言名称</div></td>
      </tr>
   
  	 <?php if($menu['level']>1) { ?>
  	 <tr>
        <th> 上级菜单：</th>
        <td>
        <select name="info[parentid]" style="width:200px;">
 			<?php echo $menu_list;?>
		</select>
        </td>
      </tr>
  	  <?php } ?>
  
   <?php if($menu['level']>2) { ?>
	<tr class='action'>
        <th>action名：</th>
        <td><input type="text" name="info[controller]"  value="<?php echo $this->addquote($menu['controller']); ?>" id="m" class="input-text"></td>
    </tr>
	<tr class='actiondo'>
        <th>do名：</th>
        <td><input type="text" name="info[action]"  value="<?php echo $this->addquote($menu['action']); ?>" id="c" class="input-text"></td>
      </tr>
	
	<tr class='actiondo'>
        <th>附加参数：</th>
        <td><input type="text"  value="<?php echo $this->addquote($menu['data']); ?>" name="info[data]" class="input-text"><div  class="onShow">如：name=lijinhua&type=2</div></td>
     </tr>
    <?php } ?>
      
	<tr>
        <th>是否显示菜单：</th>
        <td><input type="radio" name="info[display]" value="1" <?php if($menu['display']) { ?>checked=""<?php } ?>> 是<input type="radio" name="info[display]" value="0" <?php if(!$menu['display']) { ?>checked=""<?php } ?>> 否</td>
      </tr>
	  
</tbody></table>

</div>


<div class="bk15"></div>


<div class="btn">
<input type="submit" id="dosubmit"  class="button" name="dosubmit" value="提交">
<a class="button" style="padding: 5px;margin-left:20px;"  href="<?php echo $back_url;?>">返回列表</a>
</div>





</form>

<?php include $this->getTpl('Footer','Common') ?>