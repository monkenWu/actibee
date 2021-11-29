/*
預計功能:
1.時間要讓截止日晚於報名開始日

*
/




//var name,condition,location,time,intro,fee,contact
//var info = {};
//
//document.querySelector("#making_btn").addEventListener("click", function () {
//    console.log("get info");
//    info.name = document.querySelector("#name").value;
//    info.condition = document.querySelector("#condition").value;
//    info.location = document.querySelector("#location").value;
//    info.time = document.querySelector("#time").value;
//    info.intro = document.querySelector("#intro").value;
//    info.fee = document.querySelector("#fee").value;
//    info.contact = document.querySelector("#contact").value;
//
//    console.log(info);
//
//    localStorage.setItem("user_info", JSON.stringify(info));
//    console.log(localStorage.getItem("user_info"));
//
//    alert("製作完成");
//});

//function add_option(){
//    alert("add option");
//}


/*******************
 *******新增題目*******
 ********************/

var question_div = `
<li class="list-group-item">
<div class="form-group question_content" question_num="">
<div class="container">
<div class="row">
<input type="text" class="form-control col-8 question_tittle" placeholder="問題說明">
<select class="form-control col-3 question_type" >
<option>簡答</option>
<option>段落</option>
<option selected>單選</option>
<option>多選</option><option>下拉式選單</option>
</select>
<button type="button" class="btn btn-secondary col-1 del_question">X</button>
<div class="form-check"><input class="form-check-input must" type="checkbox" value="" id="defaultCheck1">
<label class="form-check-label" for="defaultCheck1">必填 </label>
</div>
</div>
</div>`;

var ans_div = `
<select class="form-control col-3 input_type"> 
<option selected>文字 </option> <option> 時間 </option>
<option> email </option> <option>連結</option>
</select>
</div>
</div>
</li> `;

var choose_div = `
<div class="question_div">
<ul class="list-group option_list">
<li class="list-group-item">
<div class="container">
<div class="row">
<input type="text" class="form-control col-10 option_item"  placeholder="選項">
<button type="button" class="btn btn-outline-primary col-2 del_option">X</button>
</div>
</div>
</li>
</ul>
<button type="button" class="btn btn-secondary new_option" id="new_option">新增選項</button></div></div></li>`;


$("#new_question").on("click", function () {
    var new_question_div = $(question_div);
    $(new_question_div).children().append(choose_div);
    $("#form_ul").append(new_question_div);
    $(".option_list").sortable();
    //set_change_type();
});



/******************
 ****切換題目類型*****
 *******************/

function set_change_type() {
    $(document).on("change", ".question_type", function () {
        //        console.log("type changed");
        var type_val = $(this).val();
        //    console.log($(this).parent().parent().next());
        //        console.log($(this).parent().parent().next());
        if (type_val == "簡答" || type_val == "段落") {
            //        console.log("是簡答");

            $(this).parent().parent().next().replaceWith(ans_div);

        }
        if (type_val == "單選" || type_val == "多選" || type_val == "下拉式選單") {
            $(this).parent().parent().next().replaceWith(choose_div);
            $(".option_list").sortable(); //切回來要重宣告
        }
    });
}




//定問題編號，先移除**************
var question_num = 1;
var option_num = 1;
//*****************************




/****************************
 *******刪除選擇類型的選項*******
 *****************************/

function del_option() {
    $(document).on("click", ".del_option", function () {
        //    console.log(this.parent());
        $(this).parent().parent().parent().remove();
    });
}



/****************
 *******刪除題目****
 ****************/
$(document).on("click", ".del_question", function () {
    //    console.log(this.parent());
    $(this).parent().parent().parent().parent().remove();
});




/*******************
 **********新增選項*******
 **************************/

var new_option_el = `
<li class="list-group-item">
<div class="container">
<div class="row">
<input type="text" class="form-control col-10 option_item"  placeholder="選項">
<button type="button" class="btn btn-outline-primary col-2 del_option">X</button>
</div>
</div>
</li>`;


$(document).on("click", ".new_option", function () {
    //    alert("add option");
    option_num++;
    //console.log(option_num);
    //    new_option_el.attr("placeholder","選項"+option_num);
    $(this).prev().append(new_option_el);
    var add_new_option = $(this).prev().children().last().children().children().children();
    //    console.log(add_new_option);
    add_new_option.attr("placeholder", "選項" /* + option_num*/ );
    del_option();

});





/********************
 *********收費類型切換******
 *********************/

var fee_ans_div = {
    free: `
<div id="fee_ans_div"><div class="container"><div class="row "><input type="text" class="form-control col-4 fee_title" placeholder="收費標題"><input type="text" class="form-control col-4 fee_price" placeholder="最低輸入金額"><input type="text" class="form-control col-4 fee_content" placeholder="收費說明"></div></div></div>`,

    fix: `
<div id="fee_ans_div"><div class="container"><div class="row "><input type="text" class="form-control col-4 fee_title" placeholder="收費標題"><input type="text" class="form-control col-4 fee_price" placeholder="收費"><input type="text" class="form-control col-4 fee_content" placeholder="收費說明"></div></div></div>`,

    multi: `
<div id="fee_ans_div"><ul class="list-group" id="multi_ul"><li class="list-group-item fee_multi_div"><div class="container"><div class="row "><input type="text" class="form-control col-3 fee_title" placeholder="收費標題"><input type="text" class="form-control col-3 fee_price" placeholder="收費金額"><input type="text" class="form-control col-3 fee_content" placeholder="收費說明"><button type="button" class="btn btn-outline-primary col-2 del_multi_option">X</button></div></div></li></ul><button type="button" class="btn btn-primary" id="new_multi_option">新增方案</button></div>`,
};


$(document).on("change", "#fee_type", function () {
    var fee_type_val = $(this).val();
    switch (fee_type_val) {
        case "付費者自訂":
            $("#fee_ans_div").replaceWith(fee_ans_div.free);
            break;
        case "單一費用":
            $("#fee_ans_div").replaceWith(fee_ans_div.fix);
            break;
        case "多種費用":
            $("#fee_ans_div").replaceWith(fee_ans_div.multi);
            $("#multi_ul").sortable();
            break;
    }
});


//新增多重方案
$(document).on("click", "#new_multi_option", function () {
    //    console.log("new multi");
    $("#multi_ul").append(`<li class="list-group-item fee_multi_div"><div class="container"><div class="row"><input type="text" class="form-control col-3 fee_title" placeholder="收費標題"><input type="text" class="form-control col-3 fee_price" placeholder="收費金額"><input type="text" class="form-control col-3 fee_content" placeholder="收費說明"><button type="button" class="btn btn-outline-primary col-2 del_multi_option">X</button></div></div></li>`);


});

//刪除新增方案
$(document).on("click", ".del_multi_option", function () {
    //    console.log(this.parent());
    $(this).parent().parent().parent().remove();
});




/***************************************
 ********************stat main**************
 ************************************************/

del_option();
$("#form_ul").sortable();
$(".option_list").sortable();
set_change_type();





/**************
submit form content
****************/


$("#submit_form").on("click", function () {


    //準備送出物件
    var submit_content = [];



    /*************************
     *********輸入活動資訊*******
     *****************************/

    submit_content.activity = {};
    submit_content.activity["title"] = document.getElementById("activity_title").value;
    submit_content.activity["content"] = document.getElementById("activity_content").value;
    submit_content.activity["start_date"] = document.getElementById("start_date").value;
    submit_content.activity["deadline"] = document.getElementById("deadline").value;




    /************************
     *******處理題目與選項*******
     ****************************/
    submit_content.form = [];

    //    console.log($(".question_content"));
    for (var question of $(".question_content")) {
        //直接$(elem)可將其轉為jquery物件，再用find()處理
        var new_submit_content = {};


        //找這個題目的type
        var new_question_type = $(question).find(".question_type option:selected").text();
        switch (new_question_type) {
            case "簡答":
                new_submit_content.type = "ans";
                break;
            case "段落":
                new_submit_content.type = "ltext";
                break;
            case "單選":
                new_submit_content.type = "choose";
                break;
            case "多選":
                new_submit_content.type = "check";
                break;
            case "下拉式選單":
                new_submit_content.type = "list";
                break;
        }

        //加入標題
        var new_question_tittle = $(question).find(".question_tittle").val();
        //        console.log($(question).find(".question_tittle").val());
        new_submit_content.data = {};
        new_submit_content.data["title"] = new_question_tittle;

        //是否必填
        var new_question_must = $(question).find(".must").prop("checked");
        new_submit_content.must = new_question_must;

        //依驗證類型加入限制
        if (new_question_type == "簡答" || new_question_type == "段落") {
            var new_question_input_type = $(question).find(".input_type").val();
            switch (new_question_input_type) {
                case "文字":
                    new_submit_content.data["verify"] = "text";
                    break;
                case "時間":
                    new_submit_content.data["verify"] = "date";
                    break;
                case "email":
                    new_submit_content.data["verify"] = "email";
                    break;
                case "連結":
                    new_submit_content.data["verify"] = "url";
                    break;
            }

        }
        if (new_question_type == "單選" || new_question_type == "多選" || new_question_type == "下拉式選單") {
            var new_question_option = [];
            //        console.log($(question).find(".option_item"));
            for (var option_content of $(question).find(".option_item")) {
                new_question_option.push($(option_content).val());
            }
            new_submit_content.data["option"] = new_question_option;
        }


        submit_content.form.push(new_submit_content);

    }



    /****************
     ***輸入收費方案***
     ****************/

    submit_content.fee = {};
    submit_content.fee["data"] = [];


    var fee_type_val = $("#fee_type").val();
    switch (fee_type_val) {
        case "付費者自訂":
            var fee_data = {};
            submit_content.fee["type"] = "free";
            fee_data.title = $(".fee_title").val();
            fee_data.price = $(".fee_price").val();
            fee_data.content = $(".fee_content").val();

            submit_content.fee["data"].push(fee_data);
            break;
        case "單一費用":
            var fee_data = {};
            submit_content.fee["type"] = "fix";
            fee_data.title = $(".fee_title").val();
            fee_data.price = $(".fee_price").val();
            fee_data.content = $(".fee_content").val();

            submit_content.fee["data"].push(fee_data);
            break;
        case "多種費用":
            submit_content.fee["type"] = "multi";
            console.log($(".fee_multi_div"));

            for (var fee_multi_div of $(".fee_multi_div")) {
                var fee_data = {};
                fee_data.title = $(fee_multi_div).find(".fee_title").val();
                fee_data.price = $(fee_multi_div).find(".fee_price").val();
                fee_data.content = $(fee_multi_div).find(".fee_content").val();

                submit_content.fee["data"].push(fee_data);
            }

            break;

    }



    //確認送出內容
    console.log(submit_content);
});
