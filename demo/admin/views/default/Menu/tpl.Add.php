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
        <a href="<!--{funcecho \Windphp\Core\UrlRoute::getWebUrl('Menu-List')}-->" ><em>菜单管理</em></a><span>|</span><a href="" class="on"><em>添加一级菜单</em></a>    </div>
	</div>
<div class="common-form">
	<form name="myform" id="myform" action="" method="post">
	<table width="100%" class="table_form contentWrap">
      <tbody>
      <tr>
        <th width="200">上级菜单：</th>
        <td>
        	<!--{$parent['name']}-->
		</td>
      </tr>
      <tr>
        <th> 对应的中文语言名称：</th>
        <td><input type="text" name="info[name]" id="language" class="input-text"><div id="languageTip" class="onShow">请输入对应的中文语言名称</div></td>
      </tr>
  
  
  <!--{if $level>2}-->
	<tr class='action'>
        <th>控制器名：</th>
        <td><input type="text" name="info[controller]"   id="m" class="input-text"></td>
    </tr>
	<tr class='actiondo'>
        <th>方法名：</th>
        <td><input type="text" name="info[action]" id="c" class="input-text"></td>
      </tr>
	
	<tr class='actiondo'>
        <th>附加参数：</th>
        <td><input type="text" name="info[data]" class="input-text"><div  class="onShow">如：name=lijinhua&type=2</div></td>
     </tr>
  <!--{/if}-->
      
	<tr>
        <th>是否显示菜单：</th>
        <td><input type="radio" name="info[display]" value="1" checked=""> 是<input type="radio" name="info[display]" value="0"> 否</td>
     </tr>
	  
	</tbody>
	</table>

		<div class="bk15"></div>
		<div class="btn"><input type="submit" id="dosubmit" class="button" name="dosubmit" value="提交"></div>

		<input type='hidden' name='info[parentid]' value="<!--{$parentid}-->">
       	<input type='hidden' name='info[level]' value="<!--{$level}-->">
	</form>

<!--{template 'Footer','Common'}-->