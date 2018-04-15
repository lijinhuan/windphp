<?php if(!defined('WINDPHP')) {exit('access error !');}?>
<script>
function edit(title,url) {
	window.top.art.dialog({title:title,id:'edit',iframe:url,width:'700',height:'390'},function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close();window.location.reload();});
}
</script>
</body>
</html>

