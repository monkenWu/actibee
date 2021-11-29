<?php
    //載入子組件
    if($isCash){
        $childComponent('', [
            "payModal"
        ]);
    }
?>
<script type="text/babel">
    basic.pushReady(function(){
        $("form").submit(function(e){
            e.preventDefault();
        });
    });
    var doSubmit = {
        activityID : "<?=$activityID?>",
        isCash : <?=$isCash?"true":"false"?>,
        formDataID : "<?=$formDataID?>",
        formData : [],
        isFreedom : <?=$isFreedom?"true":"false"?>,
        checkSuccess : function(formData,id="",isExtraCash){
            this.formData = formData;
            console.log(isExtraCash);
            if(this.isCash && !isExtraCash){
                this.verifyPayForm(id);
            }else{      
                this.sendForm();
            }
        },
        verifyPayForm : function(id,price=0){
            var sendData = {
                activityID : this.activityID,
                formDataID : this.formDataID,
            }
            basic.ajaxPost(basic.url("api/hum/verifyPayForm"),sendData)
            .done(function(e){
                if(e.status == 1){
                    payModal.payToken = e.data.payToken;
                    payModal.payTokenID = e.data.payTokenID;
                    if(doSubmit.isFreedom){
                        payModal.price = $("#feeFree").val();
                    }
                    payModal.open(id);
                }else if(e.status == 3){
                    swal("錯誤",e.data.msg,"error")
                    .then(function(e){
                        window.location.reload();
                    });
                }else{
                    swal("錯誤",e.data.msg,"error");
                }
            })
        },
        sendFeeForm : function(payData,feeID){
            var sendData = {
                form : this.formData,
                activityID : this.activityID,
                formDataID : this.formDataID,
                tokenID : basic.ajaxTokenID,
                token : basic.ajaxToken,
                payData : payData,
                feeID : feeID
            }
            basic.ajaxPost(basic.url("api/hum/sendPayForm"),sendData)
            .done(function(e){
                if(e.status == 1){
                    payModal.submitSuccess = true;
                    return;
                }else if (e.status == 2){
                    basic.ajaxToken = e.data.token;
                    basic.ajaxTokenID = e.data.tokenID;
                    payModal.submitSuccess = false;
                    payModal.isSuccess = false;
                }else if(e.status == 3){
                    swal("錯誤",e.data.msg,"error")
                    .then(function(e){
                        window.location.reload();
                    });
                }else{
                    swal("錯誤",e.data.msg,"error");
                }
            })
        },
        sendForm : function(){
            var sendData = {
                form : this.formData,
                activityID : this.activityID,
                formDataID : this.formDataID,
                tokenID : basic.ajaxTokenID,
                token : basic.ajaxToken
            }
            basic.ajaxPost(basic.url("api/hum/sendForm"),sendData)
            .done(function(e){
                if(e.status == 1){
                    window.location.href = basic.url(`hum/success/${e.data.actiID}`);
                    return;
                }else if (e.status == 2){
                    basic.ajaxToken = e.data.token;
                    basic.ajaxTokenID = e.data.tokenID;
                }else if(e.status == 3){
                    swal("錯誤",e.data.msg,"error")
                    .then(function(e){
                        window.location.reload();
                    });
                }else{
                    swal("錯誤",e.data.msg,"error");
                }
            })
        }
    }
</script>