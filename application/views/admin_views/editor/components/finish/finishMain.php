<div class="container-fluid">
    <div class="row mx-auto col-12  col-xl-9 mt-5 pt-5">
        <img class="col-12 col-md-4" src="<?php echo base_url() ?>assets/beeImage/logo.png" alt="活動蜂">
        <!-- <h1 class="text-center logo col-12">活動蜂</h1> -->
        <div class="text-center mt-5 col-12 col-md-6" style="border:5px var(--main-yellow) solid;border-radius:20px">
            <div class="align-items-center">
                <h1 class="mt-3 mb-3" style="font-size:35px">你的活動建立完成囉</h1>
                <h3 class="mb-3" style="font-size:20px;font-weight:700">趕快分享給你所有的朋友</h3>
            </div>
        </div>
    </div>
    <h1 class="mb-3 mt-4 text-center">你的活動連結是</h1>
    <!-- <div class="text-center mt-3 col-8 py-4 pb-1  mx-auto" style="background:var(--main-yellow);border-radius:40px"> -->
    <div class="text-center mt-1 col-12 col-md-10 pb-1  mx-auto">
        <h1 id="activityUrl" class="text-center col-10 col-md-8 py-4 my-4 pb-1 mx-auto" style="background:var(--main-yellow);border-radius:40px"><?= $url ?></h1>
        <h1><img class="mt-1" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $url ?>" width="150"></h1>
        <div class="row mx-auto mt-2 justify-content-center">
            <button class="btn btn-bee-four col-8 col-md-3" onclick="finish.copy()">複製網址</button>
            <div class="col-0 col-md-1"></div>
            <a class="btn btn-bee-four col-8 col-md-3" href="<?= $url ?>" target="_blank">在新分頁打開</a>
        </div>
    </div>
    <div>
        <div class="row mt-3">
            <div class="col-3"></div>
            <a href="<?php echo base_url() ?>admin/account" class="btn btn-bee-one col-md-6"><h1>回個人首頁</h1></a>
            <div class="col-3"></div>
        </div>
    </div>
    <!-- copy限定textarea -->
    <textarea cols="20" rows="10" id="copyObj" style="position:relative;left:5000px;"><?= $url ?></textarea>

</div>
<script type="text/babel">

    var finish={

        copy:function(){
            var copyObj = document.getElementById("copyObj");
                copyObj.select(); // 選擇物件
                document.execCommand("Copy"); // 執行瀏覽器複製命令
                swal("已複製連結","快分享出去吧!","success");
        },
}

</script>
<!-- display: block;
        width: 100%;
        height: 70vh;
        background: url('<?php //echo base_url() 
                            ?>assets/homePageImg/background/photo.png') no-repeat center center/cover;
        z-index: -3; -->