<div class="container-fluid text-center footer">
    <div class="d-flex footer-btn-wrapper">
        <h3 class="col-md-8 left">心動了吧！立即體驗！</h3>



        <?php if ($isLogin) : ?>
            <a class="col-md-4 right" href="<?= base_url("admin") ?>">
                <h3>即刻使用</h3>
            </a>
        <?php else : ?>
            <a class="col-md-4 right" href="<?= base_url("signup") ?>">
                <h3 >即刻加入</h3>
            </a>
        <?php endif; ?>
    </div>
    <div class="footer-center pt-4 pb-4">
        <div class="d-flex text-center justify-content-center ">
            <!-- <i class="fab fa-facebook-square fa-3x col-md-2"></i>
            <i class="fab fa-line fa-3x col-md-2"></i>
            <i class="fab fa-instagram fa-3x col-md-2"></i> -->
        </div>
        <p class="mt-2">
            actibee.tw
        </p>
    </div>
    <div class="footer-end">
        <p class="pt-2">Copyright @ 2019 actibee.tw All Rights Reserved.</p>
        <p>高雄師範大學 軟體工程與管理學系 LAB-307，負責人：吳孟賢，連絡電話：0971857517，信箱：610877102@mail.nknu.edu.tw</p>
    </div>
</div>