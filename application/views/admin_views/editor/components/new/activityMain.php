<script>
     //全域變數，負責問題類型，優先載入
    var questionTypeText = {
        "ans": "單行回答",
        "ltext": "多行回答",
        "choose": "單選",
        "check": "多選",
        "list": "下拉式選單"
    }
</script>

<div id="activitySteps">
    <?php
    //載入子組件:不同載入step的頁面
    $childComponent('activity', [
        "01cover", "02activityForm", "03style"
    ]);
    ?>
</div>

<?php
//載入子組件:表單題目選項控制、金流題目控制、導覽控制
$childComponent('activity', [
    "formQuestionAndOption", "formFeeQuestion","guide"
]);
?>


<!-- 導覽小蜜蜂觸發按鈕 -->
<button type="button" class="btn btn-bee-four float-right" data-toggle="modal" data-target="#actibeeGuide">
    <img height="30px" src="<?php echo base_url() ?>assets/beeImage/logo.png" alt=""><i class="fas fa-question-circle"></i>
</button>

<!-- 記得用 <script type="text/babel"> -->
<script type="text/babel">

    //全域都可以使用basic.pushReady()
    //它可以傳入在頁面加載完成時要執行的function
    basic.pushReady(function(){
        activity.initSteps();
        //提示離開不會儲存資訊
        $(window).bind('beforeunload',function(){
            return "系統不會儲存資訊。";
    });


        
    /***************************************
     ********************stat main**************
     ************************************************/
        //預覽按鈕觸發
        $(document).on("click", "#preview_btn", function() {
            if(!activity.checkInputAll()) return;
            activity.getPreview(activityForm.record_form());
        });
    })




    var activity  = {
        /***********
        step套件初始處理
        **********/
        stepsDiv : $("#activitySteps"),
        initSteps : function(){
            this.stepsDiv.steps({
                enableAllSteps:true,
                headerTag: "h3",
                bodyTag: "section",
                transitionEffect: "slideLeft",
                autoFocus: true,
                labels: {
                    cancel: "Cancel",
                    current: "current step:",
                    pagination: "Pagination",
                    finish: "完成",
                    next: "下一步",
                    previous: "上一步",
                    loading: "Loading ..."
                },
                //確認是否都有填
                onStepChanging: function (event, currentIndex, newIndex){   
                    //第2頁到第3頁觸發
                    if(currentIndex=="1" && newIndex=="2"){

                        //確定每個input非空
                        if(!activity.checkInputAll()) return;

                        //確認title不重複
                        if(!activity.checkTitleUnique()) return;
                    }
                    var validateNum = false;
                    $(".checkInput").each(function(){
                        if ($(this).val()==""){
                            $(this).css("border","1px solid #ff3111");
                            validateNum = true;
                        }else{
                            $(this).css("border","1px solid #ccc");
                        }
                    });
                    if(validateNum){
                        swal("還有東西沒填喔!","趕快完善活動的資料吧","warning");
                        return false;
                    }else{
                        return true;
                    }


                    
                },
                // 完成按鈕事件
                onFinished: function (event, currentIndex) {
                    swal({
                        icon:"warning",
                        title:"確定要送出表單嗎？",
                        buttons: ["先不用","確定"]
                    }).then((result)=>{
                        if(result){
                            <?php if (isset($isEdit)) : ?>
                            activity.editForm(activityForm.record_form());
                            <?php else : ?>
                            activity.sumitForm(activityForm.record_form());
                            <?php endif; ?>
                        }
                    });  
                },    
            });
        },
        /**************
        提交後端完成活動新增/變更
        ********* */
        sumitForm : function(data){
            data.token = basic.ajaxToken;
            data.tokenID = basic.ajaxTokenID;
            basic.ajaxPost(basic.url("admin/api/editor/sumitForm"),data)
            .done(function(e){
                if(e.status == 1){
                    $(window).unbind('beforeunload');
                    window.location.href = basic.url(`admin/editor/finish/${e.data.actiID}`);
                }else if(e.status == 0){
                    swal("錯誤",e.data.msg,"error");
                }else{
                    swal("錯誤",e.data.msg,"error");
                    data.token = e.data.token;
                    data.tokenID = e.data.tokenID;
                }
            });
        },
        editForm : function(data){
            data.token = basic.ajaxToken;
            data.tokenID = basic.ajaxTokenID;
            data.activityID = edit.activityID;
            basic.ajaxPost(basic.url("admin/api/editor/editForm"),data)
            .done(function(e){
                if(e.status == 1){
                    $(window).unbind('beforeunload');
                    window.location.href = basic.url(`admin/editor/finish/${e.data.actiID}`);
                }else if(e.status == 0){
                    swal("錯誤",e.data.msg,"error");
                }else{
                    swal("錯誤",e.data.msg,"error");
                    data.token = e.data.token;
                    data.tokenID = e.data.tokenID;
                }
            });
        },
        getPreview : function(data){
            basic.ajaxPost(basic.url("admin/api/editor/getPreview"),data)
            .done(function(e){
                if(e.status == 1){
                    //console.log(e.data.url);
                    window.open(e.data.url);   
                }else{
                    swal("錯誤","伺服器處理出現錯誤，請重新再試","error");
                }
            });
        },
        
        /*****************
        確認是否還有空格(第2頁到第3頁)
        ****************/
        checkInputAll:function(){
            var blankInput = false;
            activityForm.allInput = $(activityForm.form).find("input");
            
            //有開啟金流順便驗證付費
            if($("#cashFlow").prop("checked")){
                $("#fee_div").find("input").each(function(){
                    activityForm.allInput.push(this);
                });
            }
            
            $(activityForm.allInput).each(function(index){
                //移除disabled input
                if($(this).prop("disabled")==false){
                    //去除必填，number for 收費金額
                    if($(this).attr("type")=="text"||$(this).attr("type")=="number"){
                        if($(this).val()==""){
                            $(this).css("border","1px solid #ff3111");
                            blankInput = true;
                        }else{
                            $(this).css("border","1px solid #ccc");
                        }
                    }

                }
                
            });
            if(blankInput){
                swal({
                    icon:"warning",
                    title:"還有東西沒填喔!",
                })
                return false;
                
            }else{
                return true;
            }
            
        },

        /****************
        確認每題題目不重複
        *****************/
        checkTitleUnique:function(){
            var arr = [];
            $(".question_tittle").each(function(){
                arr.push($(this).val()); 
            });
            var newArr=arr.sort();

            for(var i=0;i<arr.length;i++){

                if (newArr[i]==newArr[i+1]){

                   swal("題目不可重複","重複題目:"+newArr[i],"warning");
                   return false;
                }
            }

            return true;
        },

        
    }
</script>