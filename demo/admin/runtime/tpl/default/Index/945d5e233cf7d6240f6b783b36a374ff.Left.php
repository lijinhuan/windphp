<?php
/**
 * @name 模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<?php if(is_array($datas)) foreach($datas AS $_value) { ?>
	<h3 class="f14"><span class="switchs cu on" title="展开收缩"></span><?php echo $this->addquote($_value['name']); ?></h3>
	<ul>
    <?php if(is_array($_value['sub_array'])) foreach($_value['sub_array'] AS $_m) { ?>
		<li id="_MP<?php echo $this->addquote($_m['id']); ?>" class="sub_menu">
			<a href="javascript:_MP(<?php echo $this->addquote($_m['id']); ?>,'<?php echo $this->addquote($_m['url']); ?>');" hidefocus="true" style="outline:none;"><?php echo $this->addquote($_m['name']); ?></a>
		</li>
	<?php } ?>
	</ul>
<?php } ?>	
	
	
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
