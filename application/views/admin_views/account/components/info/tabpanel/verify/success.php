<div class="alert alert-success" role="alert">
    審核已經通過囉！
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
            <input name="realName" type="text" class="form-control" placeholder="<?= $realinfo["realName"] ?>" disabled>
        </div>
    </div>
    <div class="form-group row form-input">
        <label for="" class="col-sm-3 col-form-label">學號</label>
        <div class="col-sm-9 px-0">
            <input name="stuNum" type="text" class="form-control" placeholder="<?= $realinfo["stuNum"] ?>" disabled>
        </div>
    </div>
    <div class="form-group row form-input">
        <label for="" class="col-sm-3 col-form-label">所屬團體/社團</label>
        <div class="col-sm-9 px-0">
            <input name="club" type="text" class="form-control" placeholder="<?= $realinfo["club"] ?>" disabled>
        </div>
    </div>
    <div class="form-group row form-input">
        <label for="" class="col-sm-3 col-form-label">職稱</label>
        <div class="col-sm-9 px-0">
            <input name="job" type="text" class="form-control" placeholder="<?= $realinfo["job"] ?>" disabled>
        </div>
    </div>
    <div class="form-group row form-input">
        <label for="" class="col-sm-3 col-form-label">手機</label>
        <div class="col-sm-9 px-0">
            <input name="cellphone" type="text" class="form-control" placeholder="<?= $realinfo["cellphone"] ?>" disabled>
        </div>
    </div>
</form>
<form id="realInfoImgForm">
    <div class="form-group row form-input">
        <label for="" class="col-sm-3 col-form-label">學生證正面照片</label>
        <div class="col-sm-9 px-0">
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card card-shadow pt-2 pb-2">
                        <div id="realInfoDefault" class="card-body img-preview col-12" style="height:200px;background:white">
                            <img class="img-fluid" src="<?= $realinfo['img'] ?>" alt="Responsive image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row form-input">
        <label class="col-sm-3 col-form-label">上傳所屬單位佐證文件<br>(活動企劃書/年度計畫)
        </label>
        <div class="col-sm-9 px-0">
            <input name="realName" type="text" class="form-control" placeholder="文件正確" disabled>
        </div>
    </div>
</form>