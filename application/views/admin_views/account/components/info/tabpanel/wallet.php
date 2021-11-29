<div class="tab-pane" id="wallet" role="tabpanel">
    <div class="row mt-5 mb-5 pb-3 justify-content-center">
        <h1 class="text-center">錢包</h1>
        <div class="ml-2 pointer" style="width:30px" title="活動結束後，可至此辦理轉帳事宜，將款項轉進自己的帳戶中">
            <i class="fas fa-question-circle" style="color:var(--main-yellow)"></i>
        </div>
    </div>
    <div id="bs4-table_wrapper" class="dataTables_wrapper dt-bootstrap4 mt-4">
        <table id="walletTable" class="table table-bordered table-striped dataTable" cellspacing="0" width="100%" role="grid" aria-describedby="bs4-table_info" style="width: 100%;">
            <thead>
                <tr>
                    <th>功能</th>
                    <th>表單名稱</th>
                    <th>結束時間</th>
                    <th>申請時間</th>
                    <th>匯款時間</th>
                    <th>收款金額</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?php
//載入子組件
$childComponent('wallet', [
    "cashModal"
]);
?>

<script src="<?php echo base_url() ?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.dcjqaccordion.2.7.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/custom.js" type="text/javascript"></script>
<script type="text/babel">

    basic.pushReady(function(){
        wallet.loadTable();
    });

    var wallet = {
        loadTable : function(){
            $('#walletTable').DataTable({
                "language": {
                    "lengthMenu": "顯示 _MENU_ 筆資料",
                    "emptyTab le": "沒有資料",
                    "info": "目前顯示 _START_ 至 _END_ 筆的資料，共 _TOTAL_ 筆資料",
                    "infoFiltered": "，從 _MAX_ 筆資料中過濾",
                    "infoEmpty": "沒有收到任何款項",
                    "zeroRecords":"沒有收到任何款項",
                    "search": "搜索款項資訊：",
                    "paginate": {
                        "next": "下一頁",
                        "previous": "上一頁"
                    },
                },
                bServerSide : false,
                bStateSave : true,
                destroy: true,
                //不允許第一列進行排序
                "aoColumnDefs": [{ 
                    "bSortable": false,
                    "aTargets": [ 0 ] 
                }],
                "ajax":{
                    url:basic.url('admin/api/account/walletTable'),
                    type:'POST',
                    data : {"data":""}
                },
            });
        }
    }
</script>