<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-TW" xml:lang="zh-TW">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <title>actibeeMail</title>
    <style class="float-center" type="text/css">
        /*beeStyle*/


        /* 按鈕 */
        .btn-bee-one {
            background-color: #FFE67c !important;
            color: white !important;
            border-radius: 20px !important;
        }

        .btn-bee-one:hover {
            background-color: #FFCC66 !important;

            transition-duration: 0.5s !important;
        }

        .btn-bee-two {
            background-color: #ffffcc !important;
            color: black !important;
            border-radius: 20px !important;
            box-shadow: #b5b5b5 2px 2px 3px !important;
        }

        .btn-bee-two:hover {
            background-color: #FFCC66 !important;
            color: white !important;
        }

        .btn-bee-three {
            background-color: #E5E5E5 !important;
            border-radius: 20px !important;
        }

        .btn-bee-three:hover {
            background-color: #F2F8E9 !important;
        }

        /*-----------------------------------------------------------*/

        body,
        html,
        .body {
            background: #f3f3f3 !important;
        }

        .header {
            background: #f3f3f3 !important;
        }

        a {
            border: none !important;
            text-decoration: none;
        }

        td {
            border: none !important;
            background: none !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        a {
            font-family: Microsoft JhengHei !important;
        }

        * {
            font-family: Microsoft JhengHei !important;
        }

        .mailWrap {
            padding:5px 0 15px 0;
/*            height: 500px;*/
            width: 500px;
            border: 1px solid #FFCC66;
            border-top: 50px  solid #FFCC66;
            border-bottom: 50px  solid #295F2D;;
            border-radius: 20px;
        }

        .coverImg {
            position: relative;
            width: 350px;
            left: 50px;
        }

        .mailBtn {
            padding:10px 30px 10px 30px;
            text-align: center;
            margin: 0px auto;
        }
    </style>
</head>

<body>
    <div class="mailWrap">
        <h1 style="text-align: center;margin-bottom: 10px;color:black">Actibee</h1>
        <h1 style="text-align: center;font-size: 50px;margin-bottom: 10px;margin-top: 10px;color:black">活動蜂</h1>
        <img class="coverImg" src="https://i.imgur.com/K6sWb83.png" style="padding-left:50px">
        <h2 style="text-align: center;padding:0 30px 0 30px;word-break: break-all;font-size: 30px;color:black"><?=$title?></h2>
        <p style="text-align: center;padding:0 30px 0 30px;word-break: break-all;font-size: 20px;color:black"><?=$content?></p>
        <p style="text-align: center;margin-top: 40px;"><a style="font-size:25px" href="<?=$href?>" class="btn-bee-one mailBtn"><?=$buttonName?></a></p>
        
    </div>
</body></html>