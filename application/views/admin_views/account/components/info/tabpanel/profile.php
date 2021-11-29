<div class="tab-pane active show" id="profile" role="tabpanel">
    <form id="profileForm">
        <h1 class="text-center mt-5 mb-5 pb-3">個人資料修改</h1>
        <div class="form-group row form-input">
            <div class="row col-12">
                <div class="form-group col-xl-6 col-lg-8 col-md-12">
                    <div class="card card-shadow pt-2 pb-2">
                        <div id="profileDefault" class="card-title text-center" style="height : 300px">
                            <img class="img-fluid" src="<?=$profile['userPhoto']?>" alt="Responsive image">
                        </div>
                        <div id="profilePreview" class="card-body col-12" style="background:white;display:none;">
                        </div>
                    </div>
                    <label id="profileImgBtn" for="profileImg" class="btn btn-bee-three col-12 mt-3">
                        <input name="stuCard1" type="file" style="display:none;" data-max-size="10000" id="profileImg" accept="image/gif, image/jpeg, image/png" class="btn btn-bee-three col-12" onchange='profile.readImg(this)'>
                        上傳大頭貼
                    </label>
                    <div class="row profileCtrlBtn" style="display:none;">
                        <button type="button" class="btn btn-bee-three col-6 mt-3" onclick="profile.rotateCrop(-90)">向左翻轉</button>
                        <button type="button" class="btn btn-bee-three col-6 mt-3" onclick="profile.rotateCrop(90)">向右翻轉</button>
                        <button type="button" class="btn btn-bee-three col-12 mt-3" onclick="profile.cancelCrop()" >取消上傳</button>
                    </div>
                </div>
                <div class="form-group row form-input col-xl-6 col-lg-4 col-md-12">
                    <label for="" class="col-sm-3 col-form-label">暱稱</label>
                    <div class="col-sm-9 px-0">
                        <input name="nickName" type="text" class="form-control" value="<?=$profile['userName']?>" placeholder="暱稱">
                    </div>
                </div>
            </div>
        </div>
        <div class="container text-center">
            <button type="button" class="btn btn-bee-one col-10 col-sm-3 mt-5 mx-auto mb-3" onclick="profile.submit()">送出</button>
        </div>
    </form>
</div>
<script type="text/babel">
    var profile = {
        form : $("#profileForm"),
        uploadCrop : $('#profilePreview'),
        defaultDiv : $("#profileDefault"),
        profileCtrlBtn : $(".profileCtrlBtn"),
        profileImgBtn : $("#profileImgBtn"),
        readImg : function(input){
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    profile.setCrop(e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }else{
                swal("錯誤","你所使用的瀏覽器並不支援檔案讀取介面。","error");
            }
        },
        initCrop : function(){
            this.uploadCrop.croppie({
                viewport: {
                    width: "300",
                    height: "300",
                    type: 'circle'
                },
                boundary: {
                    width: "100%",
                    height: "300"
                },
                enableOrientation: true
            });
        },
        setCrop : function(base64){
            this.uploadCrop.croppie('bind', {
                url: base64
            });
            this.cropToggle();
        },
        cropToggle : function(){
            this.uploadCrop.toggle();
            this.defaultDiv.toggle();
            this.profileCtrlBtn.toggle();
            this.profileImgBtn.toggle();
        },
        cancelCrop : function(){
            this.uploadCrop.croppie("destroy");
            this.cropToggle();
            this.initCrop();
        },
        rotateCrop : function(degrees){
            this.uploadCrop.croppie("rotate",degrees);
        },
        getCrop : function(fun){
            this.uploadCrop.croppie('result', 'base64').then(function (resp) {
                fun(resp);
            });
        },
        submit : function(){
            var data = this.form.getFormObject();
            if(this.checkForm()) return;
            this.getCrop(function(base64){
                data.img = base64 != "data:," ? base64 : "" ;
                data.token = basic.ajaxToken;
                basic.ajaxPost(basic.url("admin/api/account/editProfile"),data)
                .done(function(e){
                    if(e.status == 1){
                        swal("成功","更新成功！","success").then(function(){
                            location.reload();
                        });
                        return;
                    }
                    swal("錯誤",e.data.msg,"error");
                });
            });
        },
        checkForm : function(){
            if(this.form.isBlank(["stuCard1"])){
                swal("error","請確認所有欄位都有填寫內容。","error");
                return true;
            }
            return false;
        }
    }
    basic.pushReady(function(){
        profile.initCrop();
        profile.form.submit(function(e){
            e.preventDefault();
            profile.submit();
        });
    });
</script>