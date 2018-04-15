<?php
/**
 * @name 首页模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<!--{template 'Header','Common'}-->

<link href="<!--{$system_conf['css_url']}-->jquery.treeTable.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<!--{$system_conf['js_url']}-->jquery.treetable.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#dnd-example").treeTable({
    	indent: 20
    	});
  });
  
  function checknode(obj)
  {
      var chk = $("input[type='checkbox']");
      var count = chk.length;
      var num = chk.index(obj);
      var level_top = level_bottom =  chk.eq(num).attr('level')
      for (var i=num; i>=0; i--)
      {
              var le = chk.eq(i).attr('level');
              if(eval(le) < eval(level_top)) 
              {
                  chk.eq(i).attr("checked",'checked');
                  var level_top = level_top-1;
              }
      }
      for (var j=num+1; j<count; j++)
      {
              var le = chk.eq(j).attr('level');
              if(chk.eq(num).attr("checked")=='checked') {
                  if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked",'checked');
                  else if(eval(le) == eval(level_bottom)) break;
              }
              else {
                  if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked",false);
                  else if(eval(le) == eval(level_bottom)) break;
              }
      }
  }

</script>

<div class="table-list" id="load_priv">
	<form name="myform" action="" method="post">
		<input type="hidden" name="roleid" value="<!--{$roleid}-->"></input>
		<table width="100%" cellspacing="0" id="dnd-example">
		<tbody>
			<!--{$categorys}-->
		</tbody>
		</table>
	    <div class="btn"><input type="submit"  class="button" name="dosubmit" id="dosubmit" value="提交" /></div>
	</form>
</div>

<!--{template 'Footer','Common'}-->
