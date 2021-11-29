<script type="text/babel">

    basic.pushReady(function(){
        // submitForm.form.submit(function(e){
        //     e.preventDefault();
        // });
        //console.log($(":checkbox"));

        $(":checkbox").each(function(){
            // $(this).click(function(event){
            //     console.log(666);
            //     event.preventDefault();
            // },false);
            $(this).click(function(event){
                //console.log(666);
            });
        });

        //掛載檢查ans input type進dom元素
        //console.log($(".respond-input"));
        // $(".respond-input").change(submitForm.checkAnsInputType());
        //呼叫無效 再研究
        
        $(".respond-input").change(function(){
            var regex ="";
            var warningText = "";

            //沒定義就結束
            if($(this).attr("type")=="text"){
                return;
            }
            
            //確認type填入相應正則表達式
            if($(this).attr("type")=="email"){
                warningText = "請輸入有效email";
                regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            }else if($(this).attr("type")=="url"){
                warningText = "請輸入有效連結";
                regex = /[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)?/gi;
            }
            //不符合規定
            if(!regex.test($(this).val())){
                    var warningDiv = `<p style="color:red">${warningText}</p>`;
                    $(this).next().remove();//避免重複疊加，刪除原本的
                    $(this).after(warningDiv);
                    $(this).css("border","1px solid #ff3111");
                    submitForm.regexNotChecked = true;
                }else{
                    $(this).next().remove();
                    $(this).css("border","1px solid #ccc");
                    submitForm.regexNotChecked = false;
                }
            
            });

    })

    var submitForm={          
        form : $("form"),
        //是否皆符合輸入格式、類型
        regexNotChecked:false,
        /***************
        確認必填
        ***************/
        checkMust:function(){
            var validateInput = false;
            //從每個問題逐一檢查
            $(".questionDiv").each(function(){
                //確認是否有mustDIV
                if($(this).find(".respond-must").length>0){
                    //分字和選項檢查 ans是input ltext是textarea要分開檢查
                    if($(this).attr("questiontype")=="ans"){
                        if($(this).find("input").val()==""){
                            //console.log("沒填完");
                            $(this).css("border","1px solid #ff3111");
                            validateInput = true;
                        }else{
                            $(this).css("border","1px solid white");
                        }

                    }else if($(this).attr("questiontype")=="ltext"){

                        if($(this).find("textarea").val()==""){
                            //console.log("沒填完");
                            $(this).css("border","1px solid #ff3111");
                            validateInput = true;
                        }else{
                            $(this).css("border","1px solid white");
                        }
                        
                    }else if($(this).attr("questiontype")=="choose" || $(this).attr("questiontype")=="check"){
                        if($(this).find("input").serializeArray().length==0){
                            //console.log("未選");
                            $(this).css("border","1px solid #ff3111");
                            validateInput = true;
                        }else{
                            $(this).css("border","1px solid white");
                        }
                    }

                }
            });

            //如果付費為自行輸入，確認是否必填
            if($("#feeFree").length==1){
                if($("#feeFree").val()==""){
                    $("#feeFree").css("border","1px solid #ff3111");
                    validateInput = true;
                }else{
                    $("#feeFree").css("border","1px solid #ccc");
                }
            }
            
            if(validateInput){
                swal("還有東西沒填喔!","趕快完善資料吧","warning");
                return false;
            }else{
                return true;
            }

        },

        /***********
        radio可取消選取
        ******** */
        radioCancel:function(dom){
            var domName = $(dom).attr('name');
            var $radio = $(dom);
            if ($radio.data('waschecked') == true){
                $radio.prop('checked', false);
                $("input:radio[name='" + domName + "']").data('waschecked',false);
            } else {
                $radio.prop('checked', true);
                $("input:radio[name='" + domName + "']").data('waschecked',false);
                $radio.data('waschecked', true);
            }
        },

        /************************************
        檢查輸入格式是否正確(email,文字,連結)
        ************************************/
        checkAnsInputType:function(){
            if(this.regexNotChecked){
                swal("有東西輸入的格式錯誤喔!","趕快完善資料吧","warning");
                return false;
            }else{
                return true;
            }
        },


        /***************************
        準備送出表單(按下付款、送出鈕)
        ****************************/
        arr:{},//this.form.serializeArray();
        
        recordForm: function(dom,isExtraCash=false){        
            //先確認必填
            if(!this.checkMust()){
                return false
            }
            //確認輸入格式
            if(!this.checkAnsInputType()){
                return false
            }
            
            var submitContent = [];
            this.arr = this.form.serializeArray();
            this.arr.forEach(function(item, index) {
                var newRes = {};
                newRes.title = item.name;
                newRes.ans = item.value;
                submitContent.push(newRes);
            });     
            //console.log(666);
            <?php if (!$isPreview) : ?>
                swal({
                    icon:"warning",
                    title:"確定要送出表單嗎？",
                    buttons: ["先不用","確定"]
                }).then((result)=>{
                    if(result){
                        if($(dom).data("id") == undefined){
                            this.submitForm(submitContent,"",isExtraCash);
                        }else{
                            this.submitForm(submitContent,$(dom).data("id"),isExtraCash);
                        }
                    }
                });  
            <?php endif; ?>

            <?php if ($isPreview) : ?>
                swal({
                    icon:"warning",
                    title:"此為預覽模式，無法提交表單",
                    text:"是否結束預覽，回編輯模式?",
                    buttons: ["先不用","確定"]
                }).then((result)=>{
                    if(result){
                        window.close();
                    }
                });
            <?php endif; ?>
        },

        <?php if (!$isPreview) : ?>
        submitForm : function(data,id="",isExtraCash){
            doSubmit.checkSuccess(data,id,isExtraCash);
        },
        <?php endif; ?>
    }
</script>