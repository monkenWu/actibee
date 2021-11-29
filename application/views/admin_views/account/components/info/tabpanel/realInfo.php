<div class="tab-pane" id="realInfo" role="tabpanel">
    <?php
        if($realinfo["verify"] == 3){
            $load = ["init"];
        }else if($realinfo["verify"] == 1){
            $load = ["success"];
        }else if($realinfo["verify"] == 0){
            $load = ["wait"];
        }else if($realinfo["verify"] == 2){
            $load = ["err"];
        }
        $childComponent("verify",$load);
    ?>
</div>
<?php if($realinfo["verify"] == 3 || $realinfo["verify"] == 2 ): ?>
<script type="text/babel">
    var realInfo = {
        form : $("#realInfoForm"),
        fileForm : $("#realInfoFileForm"),
        imgForm : $("#realInfoImgForm"),
        uploadCrop : $("#realInfoPreview"),
        defaultDiv : $("#realInfoDefault"),
        ctrlBtn : $(".realInfoCtrlBtn"),
        imgBtn : $("#realInfoImgBtn"),
        readImg:function(input){
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    realInfo.setCrop(e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }else{
                swal("錯誤","你所使用的瀏覽器並不支援檔案讀取介面。","error");
            }
        },
        initCrop : function(){
            this.uploadCrop.croppie({
                viewport: {
                    width: 280,
                    height: 175,
                    type: 'square'
                },
                boundary: {
                    width: "100%",
                    height: 250
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
            this.ctrlBtn.toggle();
            this.imgBtn.toggle();
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
            if(this.checkForm())return;
            var file = new FormData(this.fileForm[0]);
            basic.ajaxPostFile(basic.url("admin/api/account/editRealInfoFirst"),file)
            .done(function(e){
                if(e.status == 1){
                    realInfo.updateSecond(e.data.fileName);
                }else{
                    swal("錯誤",e.data.msg,"error");
                }
            });
        },
        updateSecond : function(fileName){
            var data = this.form.getFormObject();
            data.fileName = fileName;
            this.getCrop(function(base64){
                data.img = base64 != "data:," ? base64 : "" ;
                data.token = basic.ajaxToken;
                basic.ajaxPost(basic.url("admin/api/account/editRealInfoSecond"),data)
                .done(function(e){
                    if(e.status == 1){
                        swal("成功","上傳成功！","success").then(function(){
                            window.location.href=basic.url("admin/account/info#realInfo");
                            location.reload();
                        });
                        return;
                    }
                    swal("錯誤",e.data.msg,"error");
                });
            });
        },
        checkForm : function(){
            if(this.form.isBlank()){
                swal("error","請確認所有欄位都填寫了內容。","error");
                return true;
            }
            if(this.imgForm.isBlank()){
                swal("error","請確認是否上傳了學生證。","error");
                return true;
            }
            if(this.fileForm.isBlank()){
                swal("error","請確認是否上傳了證明文件。","error");
                return true;
            }
            return false;
        }
    }

    basic.pushReady(function(){
        realInfo.initCrop();
        realInfo.form.submit(function(e){
            e.preventDefault();
            realInfo.submit();
        });
    });

</script>
<?php endif;?>