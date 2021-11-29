<div class="col-md-4 right-showcase mx-auto mt-5 text-center">
    <h3 class="mt-4 mb-4">歡迎回來 <?= $userName?></h3>
    <div class="profile-img col-6 mx-auto" style="background:url('<?=$userPhoto?>') no-repeat center center/cover;height:150px">
    </div>
    <a href="<?= base_url("admin")?>" style="color: var(--main-green)"><button class="col-md-10 mt-2 mb-3">立即進入系統</button></a>
    <p>不是你嗎? <a href="<?= base_url("login/out")?>" style="color: var(--main-green)">登出帳號</a></p>
</div>