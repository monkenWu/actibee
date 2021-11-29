<section class="container-1">
    <div class="showcase1 ">
        <div class="background">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 left-showcase mt-5">
                        <h1 class="pt-3">即刻擁有 獨一無二的活動助手</h1>
                        <hr>
                        <h4 style="font-size:25px">製作表單、輕鬆收費 —— 替活動加分，就是這麼簡單！</h4>
                        <p class="mt-2">擔任活動總召的你，還在為活動收費、名單管理傷透腦筋嗎？</p>
                        <p>立即加入活動蜂的行列，快速地製作表單、串接線上金流、整合管理名單......多種特色服務 一次到位！提升形象、便捷管理，聰明活動蜂就是你！</p>
                        <a href="#intro1"><button class="mx-auto mt-4 col-md-6">想了解我們？還不下滑看看！</button></a>
                    </div>
                    <?php
                        $childComponent("loginArea",[
                            $isLogin ? "memberInfo" : "login"
                        ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
