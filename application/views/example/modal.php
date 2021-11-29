<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">彈跳視窗</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="userName"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>

<script type="text/babel">

    //捕捉開啟動作
    $('#modal').on('show.bs.modal', function (e) {
        modal.setName(viewData.modal[0]);
    })

    //定義這個元件的專屬物件
    var modal = {
        userName : $("#userName"),
        setName : function(str){
            this.userName.html(`
                目前登入的使用者名稱是：${str}
            `);
        } 
    }
</script>