<div class="col-md-4 right-showcase mx-auto mt-5 text-center">
    <h3 class="mt-4 mb-4">立即開始嶄新體驗</h3>
    <form id="homeLogin">
        <input type="text" name="account" class="col-md-10 mt-2 form-control mx-auto" placeholder="帳號">
        <input type="password" name="password" class="col-md-10 mt-2 form-control mx-auto" placeholder="密碼">
        <p id="loginStatus" style="color: red"></p>
        <button class="col-md-10 mt-2 mb-3">登入</button>
    </form>
    <p>沒使用過嗎？ <a href="<?=base_url("signup")?>?" style="color: var(--main-green)">即刻加入！</a></p>
    <a href="#" style="color: var(--main-green)">忘記密碼</a>
</div>
<script type="text/babel">
    var login = {
        status : $("#loginStatus"),
        form : $("#homeLogin"),
        submitCatch : function(){
            this.form.submit(function(event){
                event.preventDefault();
                login.submitForm();
            });
        },
        submitForm : function(){
            var data = $("form").getFormObject();
            if(this.checkForm(data)) return;
            basic.ajaxPost(basic.url('login/checkLogin'),data)
            .done(function(e){
                if(e.status == 1){
                    window.location.href=basic.url();
                }else if(e.status == 2){
                    swal({
                        icon:"warning",
                        title:"無法登入",
                        text : "這個帳號還沒進行信箱認證，盡速前往信箱收取認證信即可享有活動蜂功能！",
                        buttons: ["重寄認證信","先去信箱確認"]
                    }).then((result)=>{
                        if(!result){
                            login.reMail();
                        }
                    });  
                }else{
                    login.status.html("帳號或密碼錯誤，請再試一次。");
                }
            });
        },
        reMail : function(){
            var data = this.form.getFormObject();
            if(this.checkForm(data)) return;
            basic.ajaxPost(basic.url('login/reMail'),data)
            .done(function(e){
                if(e.status == 1){
                    swal("成功","寄出成功！請盡速前往信箱收信！","success");
                }else if(e.status == 2){
                    login.status.html("帳號或密碼錯誤，請再試一次。");
                }else{
                    swal("失敗","寄出失敗，請再試一次。","error");
                }
            });
        },
        checkForm : function(formData){
            if(formData.account == ""){
                this.status.html("帳號不可為空");
                return true;
            }else if (formData.password == ""){
                this.status.html("密碼不可為空");
                return true;
            }
            return false;
        }
    }
    basic.pushReady(function(){
        login.submitCatch();
    });
</script>