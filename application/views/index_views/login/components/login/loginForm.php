<div class="row">
    <div id="loginLeft login-content" class="col-5 px-0">
        <div class="login-top pt-4 pb-5">
            <div class="container-fluid ">
                <div class="d-flex justify-content-center mb-3">
                    <a href="<?php echo base_url() ?>">
                        <img src="<?php echo base_url() ?>assets/beeImage/logo.png" alt="活動蜂" class="mr-3" height="70">
                    </a>
                    <div class="text-center mt-2">
                        <h1 style="font-size:50px">Actibee</h1>
                        <h3 style="font-weight:700">活動蜂</h3>
                    </div>
                </div>
                <h1 class="text-center">製作表單、輕鬆收費 </h1>
            </div>
        </div>
        <div class="login-form">
            <form style="padding-top:20px;padding-bottom:50px" class="col-8 mx-auto">
                <div class="form-group">
                    <label>學校信箱</label>
                    <input type="text" name="account" class="form-control" placeholder="請輸入註冊時的信箱">
                </div>
                <div class="form-group">
                    <label>密碼</label>
                    <input type="password" name="password" class="form-control" placeholder="密碼">
                </div>
                <button type="submit" class="btn btn-flat m-b-30 m-t-30" style="">登入</button>
                <a href="<?php echo base_url() ?>signup">
                    <button type="button" class="btn btn-bee-one btn-flat m-b-30 m-t-30 " style="">註冊</button>
                </a>
            </form>
        </div>
    </div>
    <div id="loginRight" class="col-7 px-0">
        <div class="login-img"></div>
    </div>
</div>


<script type="text/babel">
    $("form").submit(function(event){
        event.preventDefault();
        login.submitForm();
    });
    var login = {
        submitForm : function(){
            var data = $("form").getFormObject();
            if(this.checkForm(data)) return;
            basic.ajaxPost(basic.url('login/checkLogin'),data)
            .done(function(e){
                if(e.status == 1){
                    window.location.href='admin/account';
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
                    swal("帳號或密碼錯誤","請再試一次","error");
                }
            });
        },
        reMail : function(){
            var data = $("form").getFormObject();
            if(this.checkForm(data)) return;
            basic.ajaxPost(basic.url('login/reMail'),data)
            .done(function(e){
                if(e.status == 1){
                    swal("成功","寄出成功！請盡速前往信箱收信！","success");
                }else if(e.status == 2){
                    swal("帳號或密碼錯誤","請再試一次","error");
                }else{
                    swal("失敗","寄出失敗，請再試一次。","error");
                }
            });
        },
        checkForm : function(formData){
            if(formData.account == ""){
                swal("請確認","帳號不可為空","warning");
                return true;
            }else if (formData.password == ""){
                swal("請確認","密碼不可為空","warning");
                return true;
            }
            return false;
        }
    }
</script>