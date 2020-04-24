// 右上角用户名显示
$('#userName').text(window.localStorage.getItem('userName'))
// 时间戳处理
function formatNumber(n) {
	n = n.toString()
	return n[1] ? n : '0' + n
}
// 时间格式转换
function formatTime(date) {
	if(date == 0 || date == null){
		return '-'
	}
	var date = new Date(date * 1000);
	var year = date.getFullYear()
	var month = date.getMonth() + 1
	var day = date.getDate()
	var hour = date.getHours()
	var minute = date.getMinutes()
	var second = date.getSeconds()
	return [year, month, day].map(formatNumber).join('-') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}
// 修改信息
function changeUserInfo(){
	var roleList = []
	$.ajax({
		async: false,
		type: "post",
		url: reqDomain + "/rbac/rbac/role_list",
		dataType: "json",
		success: function (data) {
			if(data.code == 200){
				roleList = data.result
			}
		},
	});
	$.ajax({
    type: "post",
    url: reqDomain + "/index/index/user_info",
		dataType: "json",
		beforeSend: function() {
			layer.msg('数据请求中...', {icon: 16,shade: [0.3,'#fff']});
		},
    success: function (data) {
      if(data.code == 200){
				layer.closeAll();
				(function(){
					var str = '<div class="changeMask">'+
										'<div class="changeUserInfo">'+
											'<div class="popover-title">修改用户'+
												'<button type="button" class="close pullright">&times;</button>'+
											'</div>'+
											'<div class="popover-content">'+
												'<table>'+                  
													'<tr>'+
														'<td>用户名：</td>'+
														'<td><input type="text" id="user_name" class="form-control"></td>'+
													'</tr>'+
													'<tr>'+
														'<td>真实姓名：</td>'+
														'<td><input type="text" id="real_name" class="form-control"></td>'+
													'</tr>'+
													'<tr>'+
														'<td>电话：</td>'+
														'<td><input type="text" id="tel" class="form-control"></td>'+
													'</tr>'+
													'<tr>'+
														'<td>邮箱：</td>'+
														'<td><input type="text" id="email" class="form-control"></td>'+
													'</tr>'+
													'<tr>'+
														'<td>性别：</td>'+
														'<td><select id="gender" class="form-control">'+
															'<option value="0">男</option>'+
															'<option value="1">女</option>'+
														'</select></td>'+
													'</tr>'+
													'<tr>'+
														'<td>用户角色：</td>'+
														'<td id="roleId">';
														if(roleList.length){
															for(var i=0;i<roleList.length;i++){
																str += '<label><input type="checkbox" id="'+roleList[i].id+'">'+roleList[i].role_name+'</label>';
															}
														} 
						str +=         '</td>'+
													'</tr>'+
												'</table>'+
												'<button class="btn btn-default saveBtn">保存</button>'+
											'</div>'+
										'</div>'+
									'</div>';
					$('body').append(str)
					$('#user_name').val(data.result.user_name);
					$('#real_name').val(data.result.real_name);
					$('#tel').val(data.result.tel);
					$('#email').val(data.result.email);
					$('#gender').val(data.result.gender);
					var role_id = data.result.role_id.split(',')
					for(var i=1;i<role_id.length-1;i++){
						$('#roleId input[type=checkbox]').each(function(){
							if($(this).attr('id') == role_id[i]){
								$(this).attr('checked',true)
							}
						})
					}
				})()
			}
			// 保存密码修改
			$('.saveBtn').click(function(){
				if($('#user_name').val() == ''){
					layer.msg('用户名不能为空')
				}else if($('#real_name').val() == ''){
					layer.msg('真实姓名不能为空')
				}else if($('#roleId input:checked').attr('id') == undefined){
					layer.msg('请选择最少一个角色')
				}else{
					var roleIdStr = ','
					$('#roleId input').each(function(){
						if($(this).prop('checked') == true){
							roleIdStr += $(this).attr('id') + ','
						}
					})
					$.ajax({
						type: "post",
						url: reqDomain + "/rbac/rbac/user_save",
						data: {
							id: tableRow.id,
							user_name: $('#user_name').val(),
							real_name: $('#real_name').val(),
							tel: $('#tel').val(),
							email: $('#email').val(),
							gender: $('#gender').val(),
							role_id: roleIdStr
						},
						dataType: "json",
						success: function (data) {
							if(data.code == 200){
								layer.msg('修改成功');
								$('.changeMask').remove();
							}
						}
					});
				}
			})
			// 关闭弹框
			$('.close').click(function(){
				$('.changeMask').remove();
			})
    }
  });
}
// 修改密码
function changePassword(){
	(function(){
		var str = '<div class="changeMask">'+
							'<div class="changePassword">'+
								'<div class="popover-title">修改密码'+
									'<button type="button" class="close pullright">&times;</button>'+
								'</div>'+
								'<div class="popover-content">'+
									'<table>'+
										'<tr>'+
											'<td>旧密码：</td>'+
											'<td><input type="text" id="old_password" class="form-control"></td>'+
										'</tr>'+
										'<tr>'+
											'<td>新密码：</td>'+
											'<td><input type="text" id="new_password" class="form-control"/></td>'+
										'</tr>'+
									'</table>'+
									'<button class="btn btn-default saveBtn">保存</button>'+
								'</div>'+
							'</div>'+
						'</div>';
		$('body').append(str)
	})()
	// 保存密码修改
	$('.saveBtn').click(function(){
		if($('#old_password').val() == ''){
			layer.msg('旧密码不能为空');
		}else if($('#new_password').val() == ''){
			layer.msg('新密码不能为空');
		}else{
			$.ajax({
				type: "post",
				url: reqDomain + "/index/index/user_password_change",
				data: {
					old_password: hex_md5($('#old_password').val()),
					new_password: hex_md5($('#new_password').val())
				},
				dataType: "json",
				success: function (data) {
					if(data.code == 200){
						layer.msg('密码修改成功，即将退出重新登录');
						setTimeout(function(){
							window.location.href = 'index.html'
						}, 500);
					}
				}
			});
		}
	})
	// 关闭弹框
	$('.close').click(function(){
		$('.changeMask').remove();
	})
}
// 退出登录
function logout(){
	layer.confirm('确定要退出登录么？',function(){
		$.ajax({
			type: "post",
			url: reqDomain + "/index/index/user_login_out",
			success: function (data) {
				if(data.code == 200){
					window.location.href = 'index.html'
				}
			}
		});
	})
}
// 报警显示
function alarm(){
	$.ajax({
		type: "post",
		url: reqDomain + "/index/equipment/alarm_info",
		dataType: "json",
		success: function (data) {
			if(data.code == 200){				
				if(data.result.length == 0){
					$('#alarm-header-list').html('<li><a href="#">暂无报警</a></li>');
				}else{
					$('#alarmNum').text(data.result.length);
					var str = ''
					for(var i=0;i<data.result.length;i++){
						str += '<li><a href="#">'+data.result[i].name+'（'+parseInt(data.result[i].box_id).toString(16)+'）：'+(data.result[i].alarm_cold==1?'制冷报警':'')+'</a></li>'
					}
					$('#alarm-header-list').html(str)
				}
			}
		}
	});
}