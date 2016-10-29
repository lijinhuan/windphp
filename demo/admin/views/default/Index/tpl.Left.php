<?php
/**
 * @name 模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<!--{loop $datas  $_value}-->
	<h3 class="f14"><span class="switchs cu on" title="展开收缩"></span><!--{$_value['name']}--></h3>
	<ul>
    <!--{loop $_value['sub_array'] $_m}-->
		<li id="_MP<!--{$_m['id']}-->" class="sub_menu">
			<a href="javascript:_MP(<!--{$_m['id']}-->,'<!--{$_m['url']}-->');" hidefocus="true" style="outline:none;"><!--{$_m['name']}--></a>
		</li>
	<!--{/loop}-->
	</ul>
<!--{/loop}-->	
	
	
<script type="text/javascript">
$(".switchs").each(function(i){
	var ul = $(this).parent().next();
	$(this).click(
	function(){
		if(ul.is(':visible')){
			ul.hide();
			$(this).removeClass('on');
				}else{
			ul.show();
			$(this).addClass('on');
		}
	})
});
</script>
