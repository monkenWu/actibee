<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <?php $this->load->view("basic/headLoad") ?>
    <?php echo $headComponents ?? "" ?>
    <style>
        /* 定義按鈕顏色 */
        .btn {
            background-color: <?= $form["style"]["data"][1] ?>;
            color: white;
        }

        .btn:hover {
            background-color: <?= $form["style"]["data"][0] ?>;
        }

        .respond-must {
            color: red;
        }

        /*checkbox style*/
        input[type="checkbox"] {
            border: <?= $form["style"]["data"][1] ?> solid 1px;
        }

        input[type="checkbox"]:checked {
            background: <?= $form["style"]["data"][1] ?>;
        }

        /*radio style*/
        input[type="radio"] {
            border: <?= $form["style"]["data"][1] ?> solid 1px;
        }

        input[type="radio"]:checked {
            background: <?= $form["style"]["data"][1] ?>;
        }

        .previewIcon {
            z-index: 3;
            width: 150px;
            transform: translate(75px, 20px);
            position: fixed;
            right: 50%;
            top: 20px;
            opacity: .85;
        }
    </style>
</head>

<body>

    <div class="container bg-light col-12 col-md-6 mt-2 px-0">

        <?php if ($isPreview) : ?>
            <!-- 預覽顯示提醒 -->
            <div class="previewIcon btn px-2 py-1 text-center" onclick='
            swal({
                icon:"warning",
                title:"此為預覽模式，無法提交表單",
                text:"是否結束預覽，回編輯模式?",
                buttons: ["先不用","確定"]
            }).then((result)=>{
                if(result){
                    window.close();
                }
            });
            '><i class="fas fa-eye fa-2x"></i>
                <h2 class="my-1" style="color: var(--main-white)">預覽</h2>
            </div>
        <?php endif; ?>

        <form>
            <div class="card">
                <!--上方顏色 -->
                <div class="col-12 pt-5" style="background:<?= $form["style"]["data"][0] ?>"></div>
                <?php if (count($form["cover"]) > 0) : ?>
                    <div style="
                        display: block;
                        width: 100%;
                        height: 25rem;
                        background: url('https://i.imgur.com/<?= $form["cover"][0] ?>') no-repeat center center/cover;
                    "></div>
                    <!-- <img id="coverImg" src="https://i.imgur.com/<?= $form["cover"][0] ?>" alt="" /> -->
                <?php endif; ?>
                <div class="card-title text-center mt-3">
                    <h1 class="mb-3"><?= $form["activity"]["title"] ?></h1>
                    <p>活動簡介：<br><?= $form["activity"]["content"] ?></p>
                    <p>開始時間：<?= $form["activity"]["start_date"] ?></p>
                    <p>結束時間：<?= $form["activity"]["deadline"] ?></p>
                </div>
                <div class="card-body col-11 mx-auto">
                    <!-- 處理表單內容 -->
                    <form>

                        <?php $checkID = 0; ?>
                        <?php $radioID = 0; ?>

                        <!-- 金流必填email -->
                        <?php if ($form["activity"]["cashFlow"] == true) : ?>
                            <div class="ans-div pb-3 mt-3 questionDiv" questionType="ans">
                                <h2 class="respond-title mb-1">請提供您的信箱（用於活動付款通知信）</h2>
                                <p class="respond-must">
                                    必填
                                </p>
                                <input type="email" name="請提供您的信箱（用於活動付款通知信）" class="form-control col-12 respond-input" placeholder="回答">
                            </div>
                        <?php endif; ?>



                        <?php foreach ($form["form"] as $item) : ?>

                            <?php if ($item["type"] == 'ltext') : ?>

                                <div class="ltext-div pb-3 mt-3 questionDiv" questionType="ltext">
                                    <h2 class="respond-title mb-1"><?= $item["data"]["title"] ?></h2>

                                    <?php if ($item["must"]) : ?>
                                        <p class="respond-must">
                                            必填
                                        </p>
                                    <?php endif; ?>

                                    <textarea cols="50" rows="3" type="<?= $item["data"]["verify"] ?>" name="<?= $item["data"]["title"] ?>" class="form-control col-12 respond-input" placeholder="回答"></textarea>
                                </div>

                            <?php elseif ($item["type"] == 'ans') : ?>

                                <div class="ans-div pb-3 mt-3 questionDiv" questionType="ans">
                                    <h2 class="respond-title mb-1"><?= $item["data"]["title"] ?></h2>

                                    <?php if ($item["must"]) : ?>
                                        <p class="respond-must">
                                            必填
                                        </p>
                                    <?php endif; ?>

                                    <input type="<?= $item["data"]["verify"] ?>" name="<?= $item["data"]["title"] ?>" class="form-control col-12 respond-input" placeholder="回答">
                                </div>



                            <?php elseif ($item["type"] == 'choose') : ?>

                                <div class="choose-div mb-4 questionDiv" questionType="choose">
                                    <h2 class="respond-title mb-1"><?= $item["data"]["title"] ?></h2>

                                    <?php if ($item["must"]) : ?>
                                        <p class="respond-must">
                                            必填
                                        </p>
                                    <?php endif; ?>

                                    <!--option-->
                                    </label>
                                    <div class="respond-option">
                                        <!-- 動態生成ID，for label -->
                                        <?php foreach ($item["data"]["option"] as $option) : ?>
                                            <label for="radio_<?= $radioID ?>" class="col-12 p-0">
                                                <div class="form-control form-check">
                                                    <input id="radio_<?= $radioID ?>" class="form-check-input ml-2 mt-2" type="radio" name="<?= $item["data"]["title"] ?>" value="<?= $option ?>" onclick="submitForm.radioCancel(this)">
                                                    <label class="form-check-label option-title ml-4">
                                                        <?= $option ?>
                                                    </label>
                                                    <?php $radioID++; ?>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                            <?php elseif ($item["type"] == 'check') : ?>

                                <div class="choose-div mb-4 questionDiv" questionType="check">
                                    <h2 class="respond-title mb-1"><?= $item["data"]["title"] ?></h2>

                                    <?php if ($item["must"]) : ?>
                                        <p class="respond-must">
                                            必填
                                        </p>
                                    <?php endif; ?>

                                    <!--option-->
                                    <div class="respond-option">

                                        <!-- 動態生成ID，for label -->
                                        <?php foreach ($item["data"]["option"] as $option) : ?>
                                            <label for="check_<?= $checkID ?>" class="col-12 p-0">
                                                <div class="form-control form-check">
                                                    <input id="check_<?= $checkID ?>" class="form-check-input ml-2 mt-2" name="<?= $item["data"]["title"] ?>" type="checkbox" value="<?= $option ?>">
                                                    <label class="form-check-label option-title ml-4">
                                                        <?= $option ?>
                                                    </label>
                                                    <?php $checkID++; ?>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>



                            <?php elseif ($item["type"] == 'list') : ?>

                                <div class="list-div pb-3 pt-3 questionDiv" questionType="list">
                                    <h4 class="respond-title mb-1"><?= $item["data"]["title"] ?></h4>

                                    <?php if ($item["must"]) : ?>
                                        <p class="respond-must">
                                            必填
                                        </p>
                                    <?php endif; ?>

                                    <!--option-->
                                    <select class="form-control respond-option" name="<?= $item["data"]["title"] ?>">
                                        <?php foreach ($item["data"]["option"] as $option) : ?>
                                            <option><?= $option ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <!-- 分隔線 -->
                            <hr class="col-10" style="border-top:<?= $form["style"]["data"][1] ?> 1px solid">

                        <?php endforeach; ?>

                        <!-- 處理付費部分 -->

                        <!-- 是否開啟金流 -->
                        <?php if ($form["activity"]["cashFlow"] == true) : ?>
                            <!-- 付費類型判斷 -->
                            <?php if ($form["fee"]["type"] == 'multi') : ?>
                                <div class="row">
                                    <?php foreach ($form["fee"]["data"] as $feeItem) : ?>

                                        <div class="card text-center col-4 mx-auto">
                                            <div class="card-title">
                                                <h1 class="mt-2"><?= $feeItem["title"] ?></h1>
                                            </div>

                                            <div class="card-body px-0">

                                                <?php if ($feeItem["isFree"]) : ?>

                                                    <h2 class="mb-2">免費方案</h2>
                                                    <h2 class="mb-2"><?= $feeItem["content"] ?></h2>
                                                    <div class="text-center"><button type="button" class="text-center mx-auto btn btn-bee-one col-11 col-md-6 mt-2" onclick="submitForm.recordForm(this,true)">送出</button></div>

                                                <?php else: ?>
                                                
                                                    <h2 class="mb-2">付費金額<?= $feeItem["price"] ?>元</h2>
                                                    <h2 class="mb-2"><?= $feeItem["content"] ?></h2>
                                                    <button type="button" class="btn btn-bee-one col-11 col-md-6 mt-2" <?php if (!$isPreview) : ?> data-id="<?= $feeItem["id"] ?>" <?php endif; ?> onclick="submitForm.recordForm(this)">去付款</button>

                                                <?php endif; ?>

                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                </div>
                            <?php elseif ($form["fee"]["type"] == 'free') : ?>
                                <div class="card text-center col-8 mx-auto">
                                    <div class="card-title">
                                        <h1 class="mt-2"><?= $form["fee"]["data"][0]["title"] ?></h1>
                                    </div>
                                    <div class="card-body px-0">
                                        <h2 class="mb-2">請輸入金額</h2>
                                        <div class="mb-2 col-11 col-md-6 mx-auto">
                                            <input id="feeFree" class="form-control" type="number" placeholder="請至少輸入<?= $form["fee"]["data"][0]["price"] ?>元" min="<?= $form["fee"]["data"][0]["price"] ?>" onchange='$(this).minMaxInput()'>
                                        </div>
                                        <h2 class="mb-2"><?= $form["fee"]["data"][0]["content"] ?></h2>
                                        <button type="button" class="btn btn-bee-one col-11 col-md-6 mt-2" onclick="submitForm.recordForm(this)" <?php if (!$isPreview) : ?> data-id="<?= $form["fee"]["data"][0]["id"] ?>" <?php endif; ?>>去付款</button>
                                    </div>
                                </div>

                            <?php elseif ($form["fee"]["type"] == 'fix') : ?>

                                <div class="card text-center col-8 mx-auto">
                                    <div class="card-title">
                                        <h1 class="mt-2"><?= $form["fee"]["data"][0]["title"] ?></h1>
                                    </div>
                                    <div class="card-body px-0">
                                        <h2 class="mb-2">付費金額<?= $form["fee"]["data"][0]["price"] ?>元</h2>
                                        <h2 class="mb-2"><?= $form["fee"]["data"][0]["content"] ?></h2>
                                        <button type="button" class="btn btn-bee-one col-11 col-md-6 mt-2" onclick="submitForm.recordForm(this)" <?php if (!$isPreview) : ?> data-id="<?= $form["fee"]["data"][0]["id"] ?>" <?php endif; ?>>去付款</button>
                                    </div>
                                </div>

                            <?php endif; ?>


                        <?php elseif ($form["activity"]["cashFlow"] == false) : ?>

                            <div class="text-center"><button type="button" class="text-center mx-auto btn btn-bee-one col-11 col-md-6 mt-2" onclick="submitForm.recordForm()">送出</button></div>


                        <?php endif; ?>

                    </form>
                </div>
                <div class="col-12 pb-5 mt-3" style="background:<?= $form["style"]["data"][0] ?>"></div>
            </div>
        </form>
    </div>


</body>


</html>