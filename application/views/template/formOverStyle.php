<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <?php $this->load->view("basic/headLoad") ?>
    <?php echo $headComponents ?? "" ?>
    <style>
        /* 定義按鈕顏色 */
        .btn {
            background-color: <?= $form["style"]["data"][1] ?>;
            color:white;
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

        .previewIcon{ 
            z-index: 3;
            position: fixed;
            right: 20px;
            top:20px;
        }
    </style>
</head>

<body>

    <div class="container bg-light col-12 col-md-6 mt-2 px-0">

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
                <div class="card-title text-center">
                    <h1 class="mt-3">表單已結束
                    </h1>
                </div>
                <div class="card-body text-center mt-3">
                    非常抱歉，這份表單已經關閉，謝謝您的來訪。
                </div>
                <div class="col-12 pb-5 mt-3" style="background:<?= $form["style"]["data"][0] ?>"></div>
            </div>
        <form>

    </div>

</body>


</html>