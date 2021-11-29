<div class="alert alert-danger" role="alert">
    審核失敗，請重新填寫內容。原因：<?= $realinfo["reason"] ?>
</div>
<form id="realInfoForm">
    <div class="row mt-5 mb-5 pb-3 justify-content-center">
        <h1 class="text-center">開啟線上支付所需資料</h1>
        <div class="ml-2 pointer" style="width:30px" title="如要開通線上支付功能需上傳資料進行身分審核。">
            <i class="fas fa-question-circle" style="color:var(--main-yellow)"></i>
        </div>
    </div>
    <div class="form-group row form-input">
        <label for="" class="col-sm-3 col-form-label">真實姓名</label>
        <div class="col-sm-9 px-0">
            <input name="realName" type="text" class="form-control" placeholder="真實姓名" value="<?= $realinfo["realName"] ?>">
        </div>
    </div>
    <div class="form-group row form-input">
        <label for="" class="col-sm-3 col-form-label">學號</label>
        <div class="col-sm-9 px-0">
            <input name="stuNum" type="text" class="form-control" placeholder="學號" value="<?= $realinfo["stuNum"] ?>">
        </div>
    </div>
    <div class="form-group row form-input">
        <label for="" class="col-sm-3 col-form-label">所屬團體/社團</label>
        <div class="col-sm-9 px-0">
            <input name="club" type="text" class="form-control" placeholder="所屬團體/社團" value="<?= $realinfo["club"] ?>">
        </div>
    </div>
    <div class="form-group row form-input">
        <label for="" class="col-sm-3 col-form-label">職稱</label>
        <div class="col-sm-9 px-0">
            <input name="job" type="text" class="form-control" placeholder="職稱" value="<?= $realinfo["job"] ?>">
        </div>
    </div>
    <div class="form-group row form-input">
        <label for="" class="col-sm-3 col-form-label">手機</label>
        <div class="col-sm-9 px-0">
            <input name="cellphone" type="text" class="form-control" placeholder="手機" value="<?= $realinfo["cellphone"] ?>">
        </div>
    </div>
</form>
<form id="realInfoImgForm">
    <div class="form-group row form-input">
        <label for="" class="col-sm-3 col-form-label">學生證正面照片</label>
        <div class="col-sm-9 px-0">
            <div class="row">
                <!-- <form action="/somewhere/to/upload" enctype="multipart/form-data" class="col-12 col-sm-6"> -->
                <div class="col-12 col-sm-12">
                    <div class="card card-shadow pt-2 pb-2">
                        <div id="realInfoDefault" class="card-body img-preview col-12" style="height:200px;background:white">
                        </div>
                        <div id="realInfoPreview" class="card-body col-12" style="background:white;display:none;">
                        </div>
                    </div>
                    <label id="realInfoImgBtn" for="inputImg1" class="btn btn-bee-three col-12 mt-3">
                        <input name="stuCard1" type="file" style="display:none;" data-max-size="10000" id="inputImg1" accept="image/gif, image/jpeg, image/png" class="btn btn-bee-three col-12" onchange='realInfo.readImg(this)'>
                        上傳正面
                    </label>
                    <div style="display: flex;">
                        <button type="button" class="realInfoCtrlBtn btn btn-bee-three col-6 mt-3" onclick="realInfo.rotateCrop(-90)" style="display:none;">向左翻轉</button>
                        <button type="button" class="realInfoCtrlBtn btn btn-bee-three col-6 mt-3" onclick="realInfo.rotateCrop(90)" style="display:none;">向右翻轉</button>
                    </div>
                    <button type="button" class="realInfoCtrlBtn btn btn-bee-three col-12 mt-3" onclick="realInfo.cancelCrop()" style="display:none;">取消上傳</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form id="realInfoFileForm">
    <div class="form-group row form-input">
        <label class="col-sm-3 col-form-label">上傳所屬單位佐證文件<br>(活動企劃書/年度計畫)
        </label>
        <div class="col-sm-9 px-0">
            <!-- <label for="progressbarTWInput" class="btn btn-bee-three col-12 mt-3"> -->
            <input name="file" type="file" class="btn btn-bee-three col-12">
            <!-- </label> -->
        </div>
    </div>
</form>
<div class="container text-center">
    <button type="button" class="btn btn-bee-one col-10 col-sm-3 mt-5 mx-auto mb-3" onclick="realInfo.submit()">送出</button>
</div>