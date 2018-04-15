<?php
/**
 * @name 首页模板
 * @author jinhuan.li 2014-11-13
 */
if(!defined('WINDPHP')) {exit('access error !');}
?>
<?php include $this->getTpl('Header','Common') ?>
<style type="text/css">
.objbody{overflow:hidden}
.btns{background-color:#666;}
.btns{position: absolute; top:116px; right:30px; z-index:1000; opacity:0.6;}
.btns2{background-color:rgba(0,0,0,0.5); color:#fff; padding:2px; border-radius:3px; box-shadow:0px 0px 2px #333; padding:0px 6px; border:1px solid #ddd;}
.btns:hover{opacity:1;}
.btns h6{padding:4px; border-bottom:1px solid #666; text-shadow: 0px 0px 2px #000;}
.btns .pd4{ padding-top:4px; border-top:1px solid #999;}
.pd4 li{border-radius:0px 6spx 0px 6px; margin-top:2px; margin-bottom:3px; padding:2px 0px;}
.btns .pd4 li span{padding:0px 6px;}
.pd{padding:4px;}
.ac{background-color:#333; color:#fff;}
.hvs{background-color:#555; cursor: pointer;}
.bg_btn{background: url(<?php echo $this->addquote($system_conf['image_url']); ?>admin_img/icon2.jpg) no-repeat; width:32px; height:32px;}
</style>

<div class="header">
	<div class="logo lf"><a href="index.php" ><span class="invisible">管理系统</span></a></div>
    <div class="rt-col" style="width:auto;">
    	<div class="tab_style white cut_line text-r">
   				<a href="<?php echo  \Windphp\Core\UrlRoute::getWebUrl('Index-Run');?>" style="color:white;margin-right:30px;">后台首页</a>   您好！<?php echo $this->addquote($user['realname']); ?>  [<?php echo implode(',',$user['rolename'])?>]<span>|</span><a href="?controller=Login&action=Out">[退出]</a>
    	</div>	
    </div>
    <div class="col-auto">
    	<div class="log white cut_line">
    	    
    	</div>
        <ul class="nav white" id="top_menu">
        <?php if(is_array($top_menu)) foreach($top_menu AS $tmenu) { ?>
		     <li id="_M<?php echo $this->addquote($tmenu['id']); ?>" class="top_menu"><a href="javascript:_M(<?php echo $this->addquote($tmenu['id']); ?>,'javascript://')"  hidefocus="true" style="outline:none;"><?php echo $this->addquote($tmenu['name']); ?></a></li>    	
        <?php } ?>
        </ul>
    </div>
</div>
<div id="content">
	<div class="col-left left_menu">
    	<div id="Scroll"><div id="leftMain"></div></div>
        <a href="javascript:;" id="openClose" style="outline-style: none; outline-color: invert; outline-width: medium;" hideFocus="hidefocus" class="open" title="展开与关闭"><span class="hidden">展开</span></a>
    </div>
	<div class="col-1 lf cat-menu" id="display_center_id" style="display:none" height="100%">
			<div class="content">
        		<iframe name="center_frame" id="center_frame" src="" frameborder="false" scrolling="auto" style="border:none" width="100%" height="auto" allowtransparency="true"></iframe>
            </div>
    </div>
    <div class="col-auto mr8">
	    <div class="crumbs">
	    	当前位置：<span id="current_pos">后台首页</span>
	    </div>
    	<div class="col-1">
    	
        	<div class="content" style="position:relative; overflow:hidden">
                <iframe name="right" id="rightMain" src="index.php?controller=Index&action=Main" frameborder="false" scrolling="auto" style="border:none; margin-bottom:30px" width="100%" height="auto" allowtransparency="true"></iframe>
        	</div>
        </div>
    </div>
</div>

<div class="scroll"><a href="javascript:;" class="per" title="使用鼠标滚轴滚动侧栏" onclick="menuScroll(1);"></a><a href="javascript:;" class="next" title="使用鼠标滚轴滚动侧栏" onclick="menuScroll(2);"></a></div>



<script type="text/javascript"> 
if(!Array.prototype.map)
Array.prototype.map = function(fn,scope) {
  var result = [],ri = 0;
  for (var i = 0,n = this.length; i < n; i++){
	if(i in this){
	  result[ri++]  = fn.call(scope ,this[i],i,this);
	}
  }
return result;
};

var getWindowSize = function(){
return ["Height","Width"].map(function(name){
  return window["inner"+name] ||
	document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
});
}
window.onload = function (){
	if(!+"\v1" && !document.querySelector) { // for IE6 IE7
	  document.body.onresize = resize;
	} else { 
	  window.onresize = resize;
	}
	function resize() {
		wSize();
		return false;
	}
}
function wSize(){
	//这是一字符串
	var str=getWindowSize();
	var strs= new Array(); //定义一数组
	strs=str.toString().split(","); //字符分割
	var heights = strs[0]-150,Body = $('body');$('#rightMain').height(heights);   
	//iframe.height = strs[0]-46;
	if(strs[1]<980){
		$('.header').css('width',980+'px');
		$('#content').css('width',980+'px');
		Body.attr('scroll','');
		Body.removeClass('objbody');
	}else{
		$('.header').css('width','auto');
		$('#content').css('width','auto');
		Body.attr('scroll','no');
		Body.addClass('objbody');
	}
	
	var openClose = $("#rightMain").height()+39;
	$('#center_frame').height(openClose+9);
	$("#openClose").height(openClose+30);	
	$("#Scroll").height(openClose-20);
	windowW();
}
wSize();
function windowW(){
	if($('#Scroll').height()<$("#leftMain").height()){
		$(".scroll").show();
	}else{
		$(".scroll").hide();
	}
}
windowW();

$(function(){
	//默认载入左侧菜单
	$("#leftMain").load("index.php?controller=Index&action=Left&menuid=<?php echo $this->addquote($top_menu[0]['id']); ?>");
})


//左侧开关
$("#openClose").click(function(){
	if($(this).data('clicknum')==1) {
		$("html").removeClass("on");
		$(".left_menu").removeClass("left_menu_on");
		$(this).removeClass("close");
		$(this).data('clicknum', 0);
		$(".scroll").show();
	} else {
		$(".left_menu").addClass("left_menu_on");
		$(this).addClass("close");
		$("html").addClass("on");
		$(this).data('clicknum', 1);
		$(".scroll").hide();
	}
	return false;
});

function _MP(menuid,targetUrl) {
	$("#rightMain").attr('src', targetUrl+'&menuid='+menuid);
	$('.sub_menu').removeClass("on fb blue");
	$('#_MP'+menuid).addClass("on fb blue");
	$.get("?action=Index&action=CurrentPos&menuid="+menuid, function(data){
		$("#current_pos").html(data+'<span id="current_pos_attr"></span>');
	});
	$("#current_pos").data('clicknum', 1);
}



function _M(menuid,targetUrl) {
	if(menuid!=8) {
		$("#leftMain").load("index.php?action=Index&action=Left&menuid="+menuid, {limit: 25}, function(){
		   windowW();
		 });
	} else {
		$("#leftMain").load("index.php?action=Index&action=Left&menuid="+menuid, {limit: 25}, function(){
		   windowW();
		 });
	}
	//$("#rightMain").attr('src', targetUrl);
	$('.top_menu').removeClass("on");
	$('#_M'+menuid).addClass("on");
	$.get("?controller=Index&action=CurrentPos&menuid="+menuid, function(data){
		$("#current_pos").html(data);
	});
	//当点击顶部菜单后，隐藏中间的框架
	$('#display_center_id').css('display','none');
	//显示左侧菜单，当点击顶部时，展开左侧
	$(".left_menu").removeClass("left_menu_on");
	$("#openClose").removeClass("close");
	$("html").removeClass("on");
	$("#openClose").data('clicknum', 0);
	$("#current_pos").data('clicknum', 1);
}



function menuScroll(num){
	var Scroll = document.getElementById('Scroll');
	if(num==1){
		Scroll.scrollTop = Scroll.scrollTop - 60;
	}else{
		Scroll.scrollTop = Scroll.scrollTop + 60;
	}
}
</script>

<?php include $this->getTpl('Footer','Common') ?>
