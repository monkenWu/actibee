<div class="container">
    <h1 class="mb-4"><?=$data["title"]?></h1>
    <?php if($data["transfer_start_time"] != "" && $data["transfer_end_time"] == ""):?>

        <div class="alert alert-warning" role="alert">
            【<?=$data["transfer_start_time"]?>】這份表單目前已經進入了申請付款狀態，已關閉編輯以及填寫的功能。
        </div>

    <?php elseif($data["transfer_start_time"] != "" && $data["transfer_end_time"] != ""): ?>

        <div class="alert alert-success" role="alert">
            【<?=$data["transfer_end_time"]?>】這份表單已經支付完成，已關閉編輯以及填寫的功能。
        </div>

    <?php else:?>

        <a href="<?php echo base_url("admin/editor/edit/{$data["activityID"]}") ?>"><button class="btn btn-bee-one mb-5"><i class="fas fa-pencil-alt"></i>編輯活動內容</button></a>
        <a href="<?= $data["url"] ?>" target="_blank"><button class="btn btn-bee-one mb-5"><i class="fas fa-window-restore"></i>打開頁面</button></a>

    <?php endif; ?>
    <div class="row justify-content-end">
        <button class="btn btn-bee-four mb-5 " onclick="activityManage.download()"><i class="fas fa-cloud-download-alt"></i>下載回覆細節</button>
    </div>
    <div id="bs4-table_wrapper" class="dataTables_wrapper dt-bootstrap4 mt-4">
        <table id="actibeeTable" class="table table-bordered table-striped dataTable" cellspacing="0" width="100%" role="grid" aria-describedby="bs4-table_info" style="width: 100%;">
            <thead>
                <tr>
                    <th>功能</th>
                    <th>表單編號</th>
                    <th>提交時間</th>
                    <th>付款方式</th>
                    <th>付款金額</th>
                    <th>付款狀態</th>
                    <th>付款時間</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script src="<?php echo base_url() ?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.dcjqaccordion.2.7.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/custom.js" type="text/javascript"></script>
<script type="text/babel">

    basic.pushReady(function(){
        activityManage.loadTable();
    });

    var activityManage = {
        activityID : "<?=$data["activityID"]?>",
        activityTitle : "<?=$data["title"]?>",
        loadTable : function(){
            $('#actibeeTable').DataTable({
                "language": {
                    "lengthMenu": "顯示 _MENU_ 筆資料",
                    "emptyTab le": "沒有資料",
                    "info": "目前顯示 _START_ 至 _END_ 筆的資料，共 _TOTAL_ 筆資料",
                    "infoFiltered": "，從 _MAX_ 筆資料中過濾",
                    "infoEmpty": "沒有資料能夠顯示",
                    "zeroRecords":"沒有資料，可以鍵入其他內容進行搜索",
                    "search": "搜索回覆資訊：",
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
                    url:basic.url('admin/api/account/actibeeTable'),
                    type:'POST',
                    data : {"data":activityManage.activityID}
                },
                //data:activityData,
            });
        },

        /************
        下載詳細資料
        *************/
        download:function(){
            swal({
                icon:"warning",
                title:"下載活動回覆資料?",
                buttons: ["先不用","確定"]
            }).then((result)=>{
                if(result){
                    activityManage.getActivityResult();
                }
            });
        },
        getActivityResult : function(){
            var data = {};
            basic.ajaxGetBlob(basic.url("admin/api/resource/report/"+activityManage.activityID),data)
            .done(function(e){
                swal('輸出成功','下載已開始。','success');
                const $a = document.createElement("a");
                const url = URL.createObjectURL(e);
                $a.download = `${activityManage.activityTitle}-回覆細節.xlsx`;
                $a.href = url;
                $a.click()
                setTimeout(() => URL.revokeObjectURL(url), 5000);
            });
        }
    };
</script>