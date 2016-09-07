<html>
<head>
</head>
<body>
	<div style="padding:20px;border:3px solid red;">
	<!--{if $data}-->
		<!--{loop $data $key $val}-->
		<p><!--{$key}--> => <!--{$val}--></p>
		<!--{/loop}-->
	<!--{/if}-->
	</div>
</body>
</html>
