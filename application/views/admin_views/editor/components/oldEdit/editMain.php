<script type="text/babel">
    //全域都可以使用basic.pushReady()
    //它可以傳入在頁面加載完成時要執行的function
    basic.pushReady(function(){
        edit.initSteps();
        //提示離開不會儲存資訊
        $(window).bind('beforeunload',function(){
            return "離開不會儲存資訊喔!";
        });
    })
    var edit={
        activityToken : "<?= $activityToken ?>",
        activityInfo:{
            "cover":["Hho5alT.jpg"],
            "activity": {
                "title": "大設盃",
                "content": "大設盃簡介.............",
                "start_date": "2019-10-24",
                "deadline": "2019-10-25",
                "cashFlow":true
            },
            "form": [
                {
                    "type": "ans",
                    "data": {
                        "title": "你的名字是?",
                        "verify": "text"
                    },
                    "must": true
                },
                {
                    "type": "ans",
                    "data": {
                        "title": "email",
                        "verify": "email"
                    },
                    "must": true
                },
                {
                    "type": "ltext",
                    "data": {
                        "title": "可以自我介紹一下嗎?",
                        "verify": "text"
                    },
                    "must": false
                },
                {
                    "type": "choose",
                    "data": {
                        "title": "吃葷吃素?",
                        "option": ["葷", "素", "不訂便當"]
                    },
                    "must": true
                },
                {
                    "type": "check",
                    "data": {
                        "title": "有什麼需要我們替你準備的嗎?",
                        "option": ["水", "藥品", "雨衣"]
                    },
                    "must": false
                },
                {
                    "type": "list",
                    "data": {
                        "title": "你是大幾?",
                        "option": ["大一", "大二", "大三"]
                    },
                    "must": true
                }
            ],
            "fee": {
                "data": [{
                    "title": "收費1",
                    "price": "100",
                    "content": "都100"
                },
                {
                    "title": "收費2",
                    "price": "100",
                    "content": "都100"
                }],
                "type": "fix"
            },
            "style":{
                "title":"formStyleThree",
                "data":["rgb(46, 50, 60)","rgb(91, 125, 135)"]
            },
        },
        initSteps:function(){
            //將狀態設為修改活動，非新建
            activityForm.reEditForm = true;
            //用token跟後端ajax資料
            //把資料渲染到頁面上，先用假資料渲染
            document.getElementById("activity_title").value = this.activityInfo.activity["title"];
            document.getElementById("activity_content").value = this.activityInfo.activity["content"];
            document.getElementById("start_date").value = this.activityInfo.activity["start_date"];
            document.getElementById("deadline").value =  this.activityInfo.activity["deadline"];
            //是否選擇金流
            $("#cashFlow").prop("checked",this.activityInfo.activity["cashFlow"]);
            activityForm.cashFlowToggle($("#cashFlow"));
            
            //恢復封面圖片
            var imgBackground = "url(https://i.imgur.com/"+this.activityInfo.cover[0]+") no-repeat center center/cover";
            $("#previewImg").css("background",imgBackground);

            //恢復問卷內容
            this.restoreForm();

            //恢復風格選擇
            var formStyle = this.activityInfo["style"];
            $(`#${formStyle.title}`).prop("checked",true);

        },
        /***********
        恢復問卷內容
        **********/
        restoreForm:function(){
            $("#form_ul").html("");
            var formContent = this.activityInfo.form;
  
            /************
            處理題目
            ***********/

            formContent.forEach(function(formInfo){

                //formInfo = edit.activityInfo.form[i]

                var questionDom = activityForm.newQuestion();
                //轉換中英
                var type  = "單選";
                switch (formInfo.type) {
                    case "ans":
                        type = questionTypeText.ans;
                        break;
                    case "ltext":
                        type = questionTypeText.ltext;
                        break;
                    case "choose":
                        type = questionTypeText.choose;
                        break;
                    case "check":
                        type = questionTypeText.check;
                        break;
                    case "list":
                        type = questionTypeText.list;
                        break;
                }
                $(questionDom).find("select").val(type);
                //更改type
                activityForm.changeType($(questionDom).find("select"));
                //更改title
                $(questionDom).find(".question_tittle").val(formInfo.data["title"]);
                

                /***************
                處理內容或選項
                ****************/
                //是否必填
                $(questionDom).find(".must").attr("checked",formInfo.must);

                //文字類型/選項內容
                if(formInfo.type=="ans"||formInfo.type=="ltext"){
                    var verify = "";
                    switch (formInfo.data["verify"]) {
                        case "text":
                            verify = "文字";
                            break;
                        case "date":
                            verify = "時間";
                            break;
                        case "email":
                            verify = "email";
                            break;
                        case "url":
                            verify = "連結";
                            break;
                    }
                    $(questionDom).find(".input_type").val(verify);


                }else if($.inArray(formInfo.type,["choose","check","list"]) != -1){
                    //處理選項，先清空
                    $(questionDom).find("Ul").html("");
                    formInfo.data["option"].forEach(function(optionTitle){
                        var newOptionBtn = $(questionDom).find(".new_option");
                        var newOptionUl = activityForm.newOption(newOptionBtn);
                        //指定回傳Ul中的li option中的最後一個
                        var newOptionDom = $(newOptionUl).children().last().find(".option_item");
                        $(newOptionDom).val(optionTitle);

                    }); 
                }
                

                /****************
                處理收費類型
                ****************/
                var feeType = "單一費用";
                switch (edit.activityInfo.fee["type"]) {
                    case "free":
                        feeType = "付費者自訂";
                        break;
                    case "fix":
                        feeType = "單一費用";
                        break;
                    case "multi":
                        feeType = "多種費用";
                        break;
                }
                $("#fee_type").val(feeType);
                activityForm.feeTypeChange($("#fee_type"));
                //處理多重收費
                if (edit.activityInfo.fee["type"]=="multi"){
                    //清空重做
                    $("#multi_ul").html("");
                    edit.activityInfo.fee["data"].forEach(function(multiInfo){
                        var newMultiOption = $(activityForm.fee_ans_div["multiOption"]);
                        $(newMultiOption).find(".fee_title").val(multiInfo.title);
                        $(newMultiOption).find(".fee_price").val(multiInfo.price);
                        $(newMultiOption).find(".fee_content").val(multiInfo.content);
                        $("#multi_ul").append(newMultiOption);
                    });
                    
                }else if(edit.activityInfo.fee["type"]=="fix"||edit.activityInfo.fee["type"]=="free"){
                    $(".fee_title").val(edit.activityInfo.fee["data"][0].title);
                    $(".fee_price").val(edit.activityInfo.fee["data"][0].price);
                    $(".fee_content").val(edit.activityInfo.fee["data"][0].content);
                }           
            });
        }


    }
</script>