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
       <a class="on" href="javascript:;"><em>修改用户</em></a>    </div>
</div>

<div class="pad_10">
	<div class="common-form">
		<form name="myform" action="" method="post" >
			<table width="100%" class="table_form contentWrap">
				<tr>
					<td width="80">帐号</td> 
					<td><input type="text" name="info[username]" id="realname" class="input-text" size="30" value="<!--{$data['username']}-->"></input></td>
				</tr>

				<tr>
					<td width="80">角色</td> 
					<td>
						<!--{loop $roles $role}-->
							<input type="checkbox" <!--{if in_array($role['id'],$data['roleid'])}-->checked=true<!--{/if}--> name="roleid[]"  value="<!--{$role['id']}-->"> <!--{$role['rolename']}-->&nbsp;&nbsp;
						<!--{/loop}-->
					</td>
				</tr>

				<tr>
					<td>密码*</td>
					<td>
						<input type="password" name="info[password]"  class="input-text" size="30" value=""></input>
					 </td>
				</tr>

				<tr>
					<td>确认密码*</td>
					<td>
						<input type="password" name="info[repassword]"  class="input-text" size="30" value=""></input>
					</td>
				</tr>

				<tr>
				<td width="80">部门</td> 
				<td>
					<select name="info[department_id]">
					<!--{$department}-->
					</select>
				</td>
				</tr>
				
				<tr>
				<td>职位*</td>
				<td>
				<input type="text" name="info[position]"  class="input-text" size="30" value="<!--{$data['position']}-->"></input>
				</td>
				</tr>
				
				
				
				<tr>
				<td>真实姓名*</td>
				<td>
				<input type="text" name="info[realname]"  class="input-text" size="30" value="<!--{$data['realname']}-->"></input>
				</td>
				</tr>
				
				<tr>
				<td>邮箱*</td>
				<td>
				<input type="text" name="info[email]"  class="input-text" size="30" value="<!--{$data['email']}-->"></input>
				</td>
				</tr>	
				
				<tr>
				<td>手机*</td>
				<td>
				<input type="text" name="info[phone]"  class="input-text" size="30" value="<!--{$data['phone']}-->"></input>
				</td>
				</tr>
				
				<tr>
				<td>QQ*</td>
				<td>
				<input type="text" name="info[qq]"  class="input-text" size="30" value="<!--{$data['qq']}-->"></input>
				</td>
				</tr>
				
				</table>
				
				<div class="bk15"></div>
				<input name="doEditSubmit" type="submit" value="提交" class="button" id="dosubmit">
		</form>
	</div>
</div>

<!--{template 'Footer','Common'}-->