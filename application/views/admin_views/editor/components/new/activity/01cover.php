<h3>封面</h3>
<section class="mx-auto">
    <div class="mb-5 mt-5 pb-3 row justify-content-center">
        <h1 style="font-size:26px;font-weight:500;">填寫活動內容</h1>
        <div class="ml-2 pointer" style="width:30px" data-toggle="modal" data-target="#actibeeGuide" onclick='guide.changePage("skip",1)'>
            <i class="fas fa-question-circle" style="color:var(--main-green)"></i>
        </div>
    </div>

    <!-- Content-->
    <div class="row mt-5">
        <!--cover左邊:封面照片上傳_Start-->
        <div class="col-12 col-md-6 mb-5">
            <section id="cover1">
                <!--圖片上傳與預覽-->

                <form action="/somewhere/to/upload" enctype="multipart/form-data">
                    <div class="card card-shadow  pt-5 pb-5">
                        <div class="card-title text-center">
                            <h2 class="pb-1">放一張照片當活動封面吧</h2>
                        </div>

                        <div id="previewImg" class="card-body img-preview" style="height:250px;background:white">
                            <!-- <img id="lodingImg" style="display:none" width="150" src="<?php echo base_url() ?>assets/images/button-loader4.gif" alt=""> -->
                            <!-- <img id="previewImg" src="#" alt=""/> -->

                        </div>
                    </div>
                    <div class="progress">
                        <div id="imgUpdateProgress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    <label for="progressbarTWInput" class="btn btn-bee-three col-12 mt-3">
                        <input type="file" style="display:none;" data-max-size="10000" id="progressbarTWInput" accept="image/gif, image/jpeg, image/png" class="btn btn-bee-three col-12" onchange="coverPage.imgUpload(this)">
                        上傳圖片
                    </label>
                </form>
            </section>
            <div class="spinner-border text-warning" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!--cover左邊:封面照片上傳_End-->

        <!--cover右邊:活動名稱簡介_Start-->
        <div class="col-12 col-md-6">
            <section id="cover2">
                <h3 class="mb-2">活動名稱*</h3>
                <input type="text" name="checkInput" class="form-control checkInput" id="activity_title" placeholder="請輸入...">
                <div class="form-group form-input">
                    <h3 class="mt-2 mb-2">活動簡介*</h3>
                    <textarea type="text" class="form-control checkInput" id="activity_content" placeholder="請輸入&#10;活動特色、活動主旨、活動內容&#10;挑重點寫出來喔" rows="4"></textarea>
                </div>
                <div class="row mb-3">
                    <div class="col-5">
                        <h3 class="mb-2">報名開始*</h3>
                        <input type="date" class="form-control col-12 mx-auto checkInput" id="start_date" placeholder="報名開始" onchange="$(this).minMaxInput();coverPage.dateInput(this)">
                    </div>
                    <div class="col-1">
                        <!-- 空格 -->
                    </div>
                    <div class="col-5">
                        <h3 class="mb-2">報名截止*</h3>
                        <input type="date" class="form-control col-12 mx-auto checkInput" placeholder="報名截止" id="deadline" onchange='$(this).minMaxInput()'>
                    </div>
                </div>
                <div class="custom-control custom-checkbox col-12 mt-3">
                    <input type="checkbox" class="form-check-input mt-1" id="submitMaxCheck" onchange="coverPage.submitMaxToggle(this)">
                    <label for="submitMaxCheck">
                        <h1>填寫人數上限</h1>
                    </label>
                </div>
                <input type="number" class="form-control col-12 mx-auto" min="1" placeholder="填寫人數上限" id="submitMaxInput" style="display:none" onchange="$(this).minMaxInput()">
                <!-- <input type="text" id="date"> -->

            </section>
        </div>
    </div>
</section>


<!--圖片上傳與預覽JS-->
<!--    https://progressbar.tw/posts/47-->


<script type="text/babel">


    /***************
    01cover
    ****************/
    var coverPage = {
        apiUrl : 'https://api.imgur.com/3/image',
        imgData : {},
        returnData : {},
        /***********
        圖片上傳imgur
        ************/
        imgUpload : function(el){
            // $("#lodingImg").toggle();//顯示載入動畫
            var files = $(el).get(0).files;
            var size = $(el).data("max-size");
            if (this.checkImg(files,size)) {
                // Begin file upload
                console.log("Uploading file to Imgur..");
                var formData = new FormData();
                formData.append("image", files[0]);
                this.imgData = formData;
                this.doUpload();
            }
        },
        checkImg : function(file,size){
            if (file.length) {
                // Reject big files
                if (file[0].size > size * 1024) {
                    swal("錯誤","圖片大小大於10MB。","error");
                    return false;
                }
                return true;
            }else{
                swal("錯誤","未知的上傳錯誤，請重新再試。","error");
                return false;
            }
        },
        doUpload : function(){
            $("#imgUpdateProgress").text("0%");
            $("#imgUpdateProgress").css("width","0%");
            // Image URL available at response.data.link
            $.ajax({
                async: true,
                crossDomain: true,
                processData: false,
                contentType: false,
                type: 'POST',
                url: coverPage.apiUrl,
                headers: {
                    Authorization: 'Client-ID b4e593853c7b6fc',
                    Accept: 'application/json'
                },
                mimeType: 'multipart/form-data',
                data : coverPage.imgData,
                xhr : function(){
                    var xhr = new window.XMLHttpRequest();
                    $("#imgUpdateProgress").addClass("progress-bar-animated").addClass("progress-bar-striped").removeClass("bg-success");
                    xhr.upload.addEventListener("progress", function(progressEvent){ // 監聽ProgressEvent
                        if (progressEvent.lengthComputable) {
                            var percentComplete = progressEvent.loaded / progressEvent.total;
                            var percentVal = Math.round(percentComplete*100)+"%";
                            $("#imgUpdateProgress").text(percentVal);
                            $("#imgUpdateProgress").css("width",percentVal);
                        }
                    }, false);
                    return xhr;
                }
            })
            .done(function(response) {
                $("#imgUpdateProgress").removeClass("progress-bar-animated").removeClass("progress-bar-striped").addClass("bg-success");
                coverPage.returnData = JSON.parse(response);
                var imgUrl = JSON.parse(response).data["link"];
                var imgBackground = "url("+imgUrl+") no-repeat center center/cover";
                $("#previewImg").css("background",imgBackground);
            }).fail(function(e) {
                swal('執行失敗',"上傳失敗，請重新再試。",'error');
                $("#previewImg").css('background',"")
            });
        },

        /***************
        日期輸入限制
        ****************/
        //不能輸入比今天早的時間
        notBeforeToday:function(){
            var date = new Date();
            var seperator1 = "-";
            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var strDate = date.getDate();
            if (month >= 1 && month <= 9) {
                month = "0" + month;
            }
            if (strDate >= 0 && strDate <= 9) {
                strDate = "0" + strDate;
            }
            var currentdate = year + seperator1 + month + seperator1 + strDate;
            $("#start_date").prop("min",currentdate);
            $("#deadline").prop("min",currentdate);
        },

        dateInput: function(el){
            if($(el).val()>$("#deadline").val()){
                //console.log("big");
                //如果比結束時間晚會清空他
                $("#deadline").val("");
            }
            console.log("dateInput");
            console.log($(el).val());
            $("#deadline").prop("min",$(el).val());
            
        },

        /**************
        人數上限toggle
        *********** */
        submitMaxToggle:function(dom){
            var checkBox = dom;
            if(!($(checkBox).prop("checked"))){
                $("#submitMaxInput").hide();
                $("#submitMaxInput").toggleClass("checkInput");

            }else{
                $("#submitMaxInput").show();
                $("#submitMaxInput").toggleClass("checkInput");
            }
        },

        /********************
         * 開啟Boostrap datepicker
         * ******************* */
        datepickerInit:function(){
            $("#date").datepicker();
            // $("#deadline").datepicker();
        },
    };

    basic.pushReady(function(){
        coverPage.notBeforeToday();
        // coverPage.datepickerInit();
    });
</script>