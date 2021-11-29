<!-- Content_Start -->
<!-- state start-->
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">
        <div class="panel profile-cover">
            <div class="profile-cover__img">
                <div class="profile-img" onclick='account.photoEdit()'></div>
                <!-- <img src="<?php echo base_url() ?>assets/beeImage/people.png" alt="" width=""> -->
                <h3 class="h3"><?= $userName ?></h3>
            </div>
            <div class="profile-cover__action bg--img" data-overlay="0.3">
                <a href="<?php echo base_url("admin/account/info") ?>">
                    <button class="btn btn-rounded btn-bee-two">
                        <i class="fas fa-user"></i>
                        <span>帳戶資料管理</span>
                    </button>
                </a>
            </div>
            <div class="profile-cover__info">
                <ul class="nav" id="activityInfo">

                </ul>
            </div>
        </div>
        <div class="panel">
            <div class="panel-heading">
                <h1 class="mt-3 ml-2">活動管理</h1>
            </div>
            <div class="panel-body">
                <div class="container">
                    <div class="row" id="activityView">
                        <div class="col-sm-6 col-md-4 mt-3">
                            <div class="card col-12 card-shadow activity-item new-activity align-middle">
                                <a href="<?php echo base_url() ?>admin/editor">
                                    <div class=" card-body text-center px-0">
                                        <div class="my-5">
                                            <span class="">
                                                <i class="fa fa-plus-square fa-5x new-form-btn" aria-hidden="true"></i>
                                            </span>
                                            <h6 class="col-12 px-0">新建活動表單</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- state end-->
<!-- Content_right_End -->

<script type="text/babel">
    basic.pushReady(function(){
    account.getAccountData();
});
var account={
    view : $("#activityView"),
    info: $("#activityInfo"),
    photoEdit:function(){
        swal({
            icon:"info",
            title:"需要更新大頭貼嗎？",
            buttons: ["先不用","確定"]
        }).then((result)=>{
            if(result){
                window.location.href="<?php echo base_url('admin/account/info') ?>";
            }     
        });
    },
    getAccountData : function(){
        basic.ajaxPost(basic.url("admin/api/account/getAccountData"))
        .done(function(e){
            if(e.status==1){
                account.renderView(e.data.view);
                account.renderInfo(e.data.info);
            }else{
                swal("錯誤",e.data.msg,"error");
            }
        });
    },
    renderView : function(data){
        data.forEach(element => {
            this.view.append(`
                <div class="col-sm-6 col-md-4 mt-3">
                    <div class="card col-12 activity-item p-0">
                        <div class="activityImg" style="background: url('https://i.imgur.com/${element.img}') no-repeat center center/cover"></div>
                        <div class="card-body text-center">
                            <h6 class="card-title" title="${element.name}">${element.name}</h6>
                            <a href="${basic.url("admin/account/activity/"+element.id)}" class="btn btn-bee-one activityBtn">活動管理</a>
                        </div>
                    </div>
                </div>
            `)
        });
    },
    renderInfo : function(data){
        this.info.html(`
            <li>
                <strong>${data.count}</strong>活動
            </li>
            <li>
                <strong>${data.payment}</strong>待入帳
            </li>
            <li>
                <strong>${data.success}</strong>已入帳
            </li>
        `);
    }
}
</script>