<!-- Modal -->
<div class="modal fade" id="personDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="guideTitle">詳細回覆</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="personDetailContent">
            <div class="modal-footer">
                <button type="button" class="btn btn-bee-four" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>

<script type="text/babel">
    var personDetail = {
        content : $("#personDetailContent"),
        orderID : "",
        feeID : "",
        open : function(orderID,feeID){
            this.orderID = orderID;
            this.feeID = feeID;
            this.getOrderData();
            $("#personDetail").modal("show");
        },
        getOrderData : function(){
            var data = {
                orderID : this.orderID,
                feeID : this.feeID
            }
            basic.ajaxPost(basic.url("admin/api/account/getOrderData"),data)
            .done(function(e){
                if(e.status == 1){
                    personDetail.renderModal(e.data);
                }else{  
                    swal("錯誤",e.data.msg,"error");
                }
            });
        },
        renderModal : function(data){
            this.content.html("");
            data.forEach(element => {
                var tempHtml = "";
                tempHtml += `
                    <div class="mb-3">
                        <h3>${element.title}</h3>
                `;

                element.ans.forEach(ans => {
                    tempHtml += `
                        <p>${ans}</p>
                    `
                });

                tempHtml += ` 
                        <hr class="col-11" style="border-top:var(--main-green) 1px solid">
                    </div>
                `;
                this.content.append(tempHtml);
            });
        }
    };
</script>