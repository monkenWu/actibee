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
</div>
<form>
    <div class="form-group row form-input">
        <label class="col-sm-3 col-form-label">學校</label>
        <div class="col-sm-9 px-0">
            <select id="schoolSelect" name="school" class="form-control" onchange="signup.setMailInput($(this).val())">
            </select>
        </div>
    </div>
    <div class="form-group row form-input">
        <label for="inputEmail" class="col-sm-3 col-form-label">學生信箱</label>
        <div class="col-sm-9 ">
            <div class="row">
                <input name="stuNum" type="email" class="form-control col-4" placeholder="學號">
                <i class="fas fa-at fa-2x mt-1 mx-2"></i>
                <input type="text" id="schoolMail" class="form-control col-6" disabled>
            </div>
        </div>
    </div>
    <div class="form-group row form-input">
        <label for="id" class="col-sm-3 col-form-label">暱稱</label>
        <div class="col-sm-9 px-0">
            <input name="nickname" type="text" class="form-control" id="id" placeholder="暱稱">
        </div>
    </div>
    <div class="form-group row form-input">
        <label for="inputPassword1" class="col-sm-3 col-form-label">密碼</label>
        <div class="col-sm-9 px-0">
            <input name="password" type="password" class="form-control" id="inputPassword1" placeholder="密碼">
        </div>
    </div>
    <div class="form-group row form-input">
        <label for="checkPassword" class="col-sm-3 col-form-label">再次輸入密碼</label>
        <div class="col-sm-9 px-0">
            <input name="checkPassword" type="password" class="form-control" id="checkPassword" placeholder="確認密碼">
        </div>
    </div>
</form>
<div class="container text-center">
    <button type="button" class="btn btn-bee-one col-5 mx-auto mb-3" onclick="signup.submitSignup()">註冊</button>
    <p>擁有帳號了嗎？ <a href="<?= base_url('login') ?>?" style="color: var(--main-green)">立即登入！</a></p>
</div>
<!-- <div class="container">
        <div class="row justify-content-center">
            <div class="form-group mr-5">
                <i class="fab fa-facebook-square fa-3x"></i>
            </div>
            <div class="form-group">
                <i class="fab fa-google fa-3x"></i>
            </div>
        </div>
    </div> -->


<script type="text/babel">
    var signup = {
        formVal:{},
        formCorrect:true,
        token:"",
        schoolList:[],
        schoolSelect:$("#schoolSelect"),
        mailInput:$("#schoolMail"),
        getSchooList:function(){
            basic.ajaxPost(basic.url("api/signup/getSchool"))
            .done(function(e){
                if(e.status == 1){
                    signup.schoolList = e.data;
                    signup.renderSchoolList();
                }else{
                    swal("錯誤","無法獲取正確的學校清單","error");
                }
            });
        },
        renderSchoolList:function(){
            this.schoolSelect.empty();
            this.schoolList.select.forEach(element => {
                this.schoolSelect.append(`
                    <option value="${element.value}">${element.text}</option>
                `);
            });
            this.setMailInput(this.schoolList.select[0].value);
        },
        setMailInput:function(index){
            this.mailInput.attr("placeholder",this.schoolList.email[index]);
        },
        submitSignup:function(){
            this.formCorrect = true;
            this.formVal = $("form").getFormObject();
            //console.log("submitSignup");
            this.checkNoBlank();
            this.checkPassword();
            if(!this.formCorrect){
                swal("輸入格式錯誤!","趕快完善註冊資料吧","warning");
                return false;
            }else{
                this.submit();
            }
        },
        checkNoBlank:function(){
            var blankInput = false;
            var allInput = $("form").find("input");
            $(allInput).each(function(index){
                //判斷是否為disable
                if($(this).prop("disabled")==false){
                    if($(this).val()==""){
                        $(this).css("border","1px solid #ff3111");
                        blankInput = true;
                    }else{
                        $(this).css("border","1px solid #ccc");
                    } 
                }
            });
            if(blankInput){
                this.formCorrect = false;
            }
        },
        checkPassword:function(){
            var formVal = this.formVal;
            if (formVal.password!=formVal.checkPassword){
                this.formCorrect = false;
                $("#checkPassword").css("border","1px solid #ff3111");
                $("#checkPassword").next().remove();
                $("#checkPassword").after(`<p style="color:#ff3111">密碼不一樣</p>`);
                // swal("輸入格式錯誤!","趕快完善註冊資料吧","warning");
                // return false;
            }else{
                // console.log($("#checkPassword").next());
                $("#checkPassword").css("border","1px solid #ccc");
                $("#checkPassword").next().remove();
            }
        },
        submit:function(){
            basic.ajaxPost(basic.url("api/signup/captcha"),{recaptcha_response : basic.reCAPTCHA})
            .done(function(e){
                if(e.status == 1){
                    signup.token = e.data.token;
                    signup.sendData();
                }else{
                    swal("錯誤",e.data.msg,"error")
                    .then(function(){
                        location.reload();
                    });
                }
            });
        },
        sendData:function(){
            this.formVal["token"] = this.token;
            basic.ajaxPost(basic.url("api/signup/doSignup"),this.formVal)
            .done(function(e){
                if(e.status == 1){
                    swal("請到您的學生信箱收取認證信","認證信將在5分鐘內寄達，若未收到請稍後","success")
                    .then(function(){
                        window.location.href=basic.url("login");
                    });
                    return;
                }else if(e.status == 2){
                    basic.setReCAPTCHA();
                }
                swal("錯誤",e.data.msg,"error");

            });
        }
    }

    basic.pushReady(function(){
        signup.getSchooList();
    });
</script>