<!-- Modal -->
<div class="modal fade" id="cashModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="guideTitle">提領款項</h5>
            </div>
            <div class="modal-body" id="cashModalContent">
            <form id="cashForm">
                <div class="form-group">
                    <label>銀行名稱與代號</label>
                    <select class="form-control" name="bank" id="bankSelect"></select>
                </div>
                <div class="form-group">
                    <label>分行名稱</label>
                    <input type="text" class="form-control" name="branch">
                </div>
                <div class="form-group">
                    <label>戶名</label>
                    <input type="text" class="form-control" name="name">
                </div>
                <div class="form-group">
                    <label>收款帳號</label>
                    <input type="text" class="form-control" name="address">
                </div>
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-bee-three" data-dismiss="modal">關閉</button>
                <button type="button" class="btn btn-bee-four" onclick="cashModal.doubleCheck()">完成</button>
            </div>
        </div>
    </div>
</div>

<script type="text/babel">
    basic.pushReady(function(){
        cashModal.form.submit(function(e){
            e.preventDefault();
            cashModal.doubleCheck();
        });
    });
    var cashModal = {
        activityID : $("#cashModalContent"),
        form : $("#cashForm"),
        select : $("#bankSelect"),
        backInfo : "",
        open : function(id,date){
            if(this.openDataCheck(date)) return;
            this.activityID = id;
            this.getBankCode();
            $("#cashModal").modal({
                keyboard : false,
                backdrop : "static",
                show : true
            });
        },
        openDataCheck : function(date){
            if((Date.parse(date)).valueOf() > (Date.parse("<?=date("Y-m-d H:i:s")?>")).valueOf()){
                swal("注意","你必須在活動結束後才能提領收取的款項。","warning");
                return true;
            }
            return false;
        },
        getBankCode : function(){
            basic.ajaxPost(basic.url("admin/api/account/getBankCode"))
            .done(function(e){
                if(e.status == 1){
                    cashModal.render(e.data);
                }else{
                    swal("錯誤",e.data.msg,"error");
                }
            })
        },
        render : function(e){
            this.form[0].reset();
            this.select.html("");
            e.forEach(element => {
                this.select.append(`
                    <option value="${element.code},${element.name}">（${element.code}） ${element.name}</option>
                `);
            });
        },
        submit : function(){
            var data = this.form.getFormObject();
            var bankInfo = data.bank.split(",");
            data.code = bankInfo[0];
            data.bank = bankInfo[1];
            data.activityID = this.activityID;
            if(this.checkForm()) return;
            basic.ajaxPost(basic.url("admin/api/account/setWallet"),data)
            .done(function(e){
                if(e.status == 1){
                    swal("成功","提交成功，請耐心等候資料審核與作業流程，我們將在五個工作天內匯款進您指定的帳戶。","success");
                    $("#cashModal").modal("hide");
                    wallet.loadTable();
                }else{
                    swal("錯誤",e.data.msg,"error");
                }
            });
        },
        doubleCheck : function(){
            swal({
                icon:"warning",
                title:"確認",
                text : "確定要送出匯款資料嗎？送出後就不能修改囉！",
                buttons: ["先不用","確定"]
            }).then((result)=>{
                if(result){
                    cashModal.submit();
                }
            });  
        },
        checkForm : function(){
            if(this.form.isBlank()){
                swal("error","請確認所有欄位都有填寫內容。","error");
                return true;
            }
            return false;
        }
    }

</script>