$(function(){	

		$("#form1").validate({
			submitHandler:function(form) {
				form.submit();
			},
			rules:{
				username:{
					required:true,
					minlength:2
				},
				nickname:{
					required:true,
					minlength:2
				},
				become:'required',
				salaryss:{
					number:true,
				},
				salaryzz:{
					number:true,
				},
				housing:{
					number:true,
				},
				traffic:{
					number:true,
				},
				catering:{
					number:true,
				},
				phone:{
					number:true,
				},
				department:'required',
				nibs:'sel_required',
				name:{
					required:true,
					minlength:2
				},
				sex:'required',
				identity_card:{
					required:true,
					minlength:15,
					maxlength:18
				},
				mobile:{
					required:true,
					number:true,
					minlength:11,
					maxlength:11
				},
				education1:'sel_required',
				urgency:'required',
				phone_ugy:{
					required:true,
					number:true,
					minlength:11,
					maxlength:11
				},
//				bankaddress:'required',
//				bank_account:{
//					required:true,
//				},
			},
			messages:{				
				username:{
					required:'请输入用户名',
					minlength:'用户名至少2个字符'
				},
				nickname:{
					required:'请输入昵称',
					minlength:'用户名至少2个字符'
				},	
				become:'选择转正状态',	
				salaryss:{
					number:'请输入数字'
				},	
				salaryzz:{
					number:'请输入数字'
				},	
				housing:{
					number:'请输入数字'
				},	
				traffic:{
					number:'请输入数字'
				},	
				catering:{
					number:'请输入数字'
				},	
				phone:{
					number:'请输入数字'
				},	
				department:'请选择部门',	
				nibs:'请选择上司',	
				name:{
					required:'请输入姓名',
					minlength:'用户名至少2个字符'
				},	
				sex:'请选择性别',	
				identity_card:{
					required:'请输入身份证号码',
					minlength:'15位号码',
					maxlength:'18位号码'
				},
				mobile:{
					required:'请输入手机号码',
					number:'手机号码格式错误',
					minlength:'11位手机号码',
					maxlength:'11位手机号码'
				},
				education1:'请选择学历',
				urgency:'请输入紧急联系人',
				phone_ugy:{
					required:'请输入紧急联系电话',
					number:'手机号码格式错误',
					minlength:'11位手机号码',
					maxlength:'11位手机号码'
				},
//				bankaddress:'请输入银行名称',
//				bank_account:{
//					required:'请输入银行卡号',
//				},
			},
			errorClass:'cerror',
		});
		
		$.validator.addMethod("sel_required", function(value,element){
			if(value == 0 || value == ''){
				return false;
			}else{
				return true;
			}
			
		});
		
		function val_department(){
			var dep_val = $('#department').val();
			if(dep_val == 0 || dep_val == ''){
				$('#department').next().append('<label id="department-error" class="cerror" for="department">请选择部门</label>');
				return false;
			}else{
				$('#department').next('cerror').remove();
				return true;
			}
		}
});
