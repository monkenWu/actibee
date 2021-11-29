<!-- Modal -->
<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="guideTitle">線上付款</h5>
            </div>
            <div class="modal-body" id="payContent">
                <div id="payTypeBtn">
                    <?php if(ISCREDIT):?>
                        <button class="btn my-1"  onclick="payModal.getPayment('CREDIT')"><i class="fas fa-credit-card"></i>信用卡</button>
                    <?php endif; ?>
                    <button class="btn my-1"  onclick="payModal.getPayment('ATM')"><i class="fas fa-exchange-alt"></i>ATM轉帳</button>
                    <button class="btn my-1"  onclick="payModal.getPayment('CVS')"><i class="fas fa-shopping-cart"></i>超商代碼繳費</button>
                </div>
                <div id="payView" style="display:none">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-bee-four" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>

<script type="text/babel">
    var payModal = {
        contnet : $("#payContent"),
        feeID : "",
        backInfo : "",
        payToken : "",
        payTokenID : "",
        isSuccess : false,
        submitSuccess : false,
        price : 0,
        open : function(id){
            this.feeID = id;
            this.renderModal();
            $("#payModal").modal({
                keyboard : false,
                backdrop : "static",
                show : true
            });
        },
        renderModal : function(){
            this.contnet.html(`
                <div class="text-center" id="payLoading" style="display:none">
                    <img src="${basic.url('assets/images/button-loader6.gif')}" class="img-fluid" alt="Responsive image">
                </div>
                <div id="payTypeBtn">
                    <?php if(ISCREDIT):?>
                        <button class="btn my-1"  onclick="payModal.getPayment('CREDIT')"><i class="fas fa-credit-card"></i>信用卡</button>
                    <?php endif; ?>
                    <button class="btn my-1"  onclick="payModal.getPayment('ATM')"><i class="fas fa-exchange-alt"></i>ATM轉帳</button>
                    <button class="btn my-1"  onclick="payModal.getPayment('CVS')"><i class="fas fa-shopping-cart"></i>超商代碼繳費</button>
                </div>
                <div id="payView" style="display:none">

                </div>
            `);
        },
        getPayment : function(type){
            $("#payTypeBtn").hide();
            $("#payLoading").show();
            var data = {
                payToken : this.payToken,
                payTokenID : this.payTokenID,
                feeID : this.feeID,
                type : type
            };
            if(doSubmit.isFreedom){
                data.price = this.price;
            }
            basic.ajaxPost(basic.url("pay/ecpay"),data)
            .done(function(e){
                if(e.status == 1){
                    payModal.renderPayView(e.data);
                    payModal.payToggle();
                }else{
                    swal("錯誤",e.data.msg,"error").
                    then(function(){
                        $("#payModal").modal('hide');
                    });
                }
            });
        },
        payToggle : function(){
            $("#payTypeBtn").remove();
            $("#payLoading").remove();
            $("#payView").show();
        },
        renderPayView : function(data){
            $("#payView").html(`
                <div><img src="https://www.ecpay.com.tw/Content/Themes/WebStyle20131201/images/header_logo.png" height="40" style="display:block; margin:auto;"></div><iframe src="${data.SPCheckOut}?MerchantID=${data.MerchantID}&SPToken=${data.SPToken}&PaymentType=${data.PaymentType}" frameborder="0" height="600" width="100%"></iframe>
            `);
        },
        paySuccess : function(data,feeID){
            this.isSuccess = true;
            doSubmit.sendFeeForm(JSON.parse(data),feeID);
        },
        // 檢查裝置類型
        getIsMobileAgent : function(){
            var IsMobileAgent = false;
            var userAgent = navigator.userAgent;
            var CheckMobile = new RegExp("android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino");
            var CheckMobile2 = new RegExp("mobile|mobi|nokia|samsung|sonyericsson|mot|blackberry|lg|htc|j2me|ucweb|opera mini|mobi|android|iphone");

            if (CheckMobile.test(userAgent) || CheckMobile2.test(userAgent.toLowerCase())) {
                IsMobileAgent = true;  
            }

            return IsMobileAgent;
        }
    }

    basic.pushReady(function(){
        window.addEventListener('message', function (e) {
            if(typeof(e.data) != "object"){
                payModal.paySuccess(e.data,payModal.feeID);
                //console.log('API回傳前端訂單資訊：'+e.data);
            }
        });
        
        $('#payModal').on('hide.bs.modal', function (e) {
            if(payModal.isSuccess){
                if(payModal.submitSuccess){
                    window.location.href = basic.url(`hum/success/${doSubmit.activityID}`);
                    return false;
                }else{
                    return false;
                }
            }
        });
    });

</script>