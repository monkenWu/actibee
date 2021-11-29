<script type="text/babel">

    /**************
    表單內容處理
    ****************/

    var feeTypeText = {
        "free":"付費者自行輸入",
        "multi":"一般定價收費",
        "fix":"單一費用",
    }

    var activityForm = {

    reEditForm : false,//初始為非修改活動狀態>新建活動

    formUl : "#form_ul",
    form: "#edit_form",
    
    allInput:"",


    /***********
    客製化sortable
    ************/
    editorSortable:function(dom){
        $(dom).sortable({
            containment: "#formWrap",//限制拖動位置在div中

            scroll: true,
            scrollSensitivity: 50,
            scrollSpeed: 1,

            /***********
            處理題目sortable的頁面滾動
            **********/
            //sort觸發時開始function
            sort: function(event, ui) {
                var currentScrollTop = $(window).scrollTop(),
                    topHelper = ui.position.top,
                    delta = topHelper - currentScrollTop;
                   
                setTimeout(function() {
                    // console.log(delta);
                    if(delta <110 && delta >0 )delta = 0;//往下多遠滾動
                    if(delta >-110 && delta <0 )delta = 0;//往上多遠滾動
                    if(delta >7 ) delta = 7;//往下滾動速度
                    if(delta <-7 ) delta = -7;//往上滾動速度
                    // console.log(delta);
                    $(window).scrollTop(currentScrollTop + delta);
                }, 2);//2毫秒後觸發
            }
        });
    },


    /*******************
    新增題目+題目拖曳滾動
    ********************/
  
    questionDiv:`
    <li class="list-group-item pt-0 mb-4 px-0" data-componentname = "questionDiv">
        <div class="col-12 text-center pb-3 mb-3 questionHandler" style="background:rgba(0,0,0,.125)"></div>
        <div class="form-group question_content col-11 mb-1 mx-auto" question_num="">
            <div class="container">
                <div class="row">
                    <input type="text" class="form-control col-8 question_tittle" placeholder="標題">
                    <select class="form-control col-2 question_type" onchange="activityForm.changeType(this)">
                        <option>${questionTypeText.ans}</option>
                        <option>${questionTypeText.ltext}</option>
                        <option selected>${questionTypeText.choose}</option>
                        <option>${questionTypeText.check}</option>
                        <option>${questionTypeText.list}</option>
                    </select>
                    <div class="col-1"><!--空格用--></div>
                    <button type="button" class="btn btn-bee-one col-1" onclick="activityForm.delQuestion(this)">X</button>
                    <div class="row col-12">
                        <input class="form-check-input must ml-5 mt-1" type="checkbox" value="">
                        <p>必填</p>
                    </div>
                </div>
            </div>
            <div class="question_div container-fluid">
            <!--不同類型內容-->
            </div>
        </div>
    </li> 
    `,
    ansDiv:`
    <div class="row">
        <h3 class="mr-1 mt-2">輸入類型</h3>
        <select class="form-control col-2 input_type">
            <option selected>文字</option>
            <option>時間</option>
            <option>email</option>
            <option>連結</option>
        </select>
        <input class="col-3 form-control" type="text" disabled="disabled" placeholder="回答">
    </div>
    `,
    //chooseDiv有選項子部分，故需要預處理
    chooseDiv:function(){
        var basicDiv =  `
            <ul class="list-group option_list">
            </ul>
            <button type="button" class="btn btn-bee-two new_option mt-3" onclick="activityForm.newOption(this)">新增選項</button>
        `;
        $(basicDiv).find(".option_list").append(this.optionEl);
        return basicDiv},
    optionEl:`
    <li class="list-group-item option-item mb-1" data-componentname = "optionDiv">
        <div class="container">
            <div class="row pl-4">
                <input type="text" class="form-control col-11 option_item"  placeholder="選項">
                <button type="button" class="btn btn-outline-primary col-1" onclick="activityForm.delOption(this)">X</button>
            </div>
        </div>
    </li>`,
    /**************************
    取的指定物件的上層component
    ***************************/
    //使用需指定component的最外層節點一個data-componentname
    //dom為尋找component的進入點，一直往上找到符合dataName的dom，其為component的起始節點
    //目前以最多找20個為主，之後考慮改成一路找到document層級
    getComponent: function(dom,dataName){
         function _getComponentDom(dom,dataName){
            var _findParentCount = 0;
            //start get Component dom
            var dom = dom;
            while($(dom).data().componentname!=dataName && _findParentCount<20){
                dom = $(dom).parent();

                //考慮一路找到document
                // if(dom == $(document)){
                //     throw("Until document,we can not find component !");
                //     break;
                // }
                _findParentCount++;
            }
            if(_findParentCount>=20){
                //console.log("Didnt find component , return inputed dom");
                return dom;
            }else{
                //console.log("find component!");
                var ComponentDom = dom;
                return ComponentDom;
            }
        }
        
        function main(dom){
            if(typeof dom == "undefined"){
                //console.log("the dom used to search component is undefined!");
                return dom
            }else{
                return _getComponentDom(dom,dataName);
            }  
        }
        
        return main(dom);
    },

    
    /***********
    統一插入動畫效果
    ********* */
    addWithAnimatiom :function(dom,addToDom){
        //console.log("addWithAnimatiom"+dom+"to"+addToDom);
        return $(dom).hide().appendTo(addToDom).show("normal");
    },

    /***********
    統一刪除動畫效果
    ********* */
    delWithAnimatiom :function(dom){
        return dom.hide('normal', function(){ dom.remove(); });
    },

    newQuestion: function() {
        //隱藏"按下方新增問題開始製作你的表單吧!"只有一開始有用
        $("#questionBackTip").hide();
        $("#edit_form").show();
        var new_question_div = $(this.questionDiv);
        //組合Question div
        $(new_question_div).find(".question_div").append(this.chooseDiv);
        //把select狀態綁到data中
        var newQuestionType = $(new_question_div).find(".question_type");
        $(newQuestionType).data("questionTypeData",$(newQuestionType).find(".question_type").val())
        this.addWithAnimatiom(new_question_div,this.formUl);
        $(".option_list").sortable();
        this.editorSortable($(this.formUl));
        return new_question_div;
    },

    /*******************
     **********新增選項*******
     **************************/
    newOption:function(btnDom){
        //var newOptionUl = $(dom).prev().append(this.optionEl);
        var _componentDom = this.getComponent(btnDom,"questionDiv");
        if(typeof _componentDom == "undefined"){
            //alert(btnDom + "is undefined");
            return
        }else{
            var _optionUlDom = $(_componentDom).find(".option_list");
            var newOptionUl = this.addWithAnimatiom(this.optionEl,_optionUlDom);
            return newOptionUl;
        }
       
    },

    /******************
     切換題目類型
     *******************/
     //改變內容editMain也需要改變
    changeType : function(dom){
        if(typeof dom == "undefined"){
            //alert(dom + "is undefined");
            return
        }else{
            var type_val = $(dom).val();
            var questionLi = this.getComponent(dom,"questionDiv");
            if(typeof questionLi == "undefined"){
                //console.log("questionLi is undefined")
                return
            }
            //var questionLi = $(dom).parent().parent().parent().parent();
            var questionTypeData = $(dom).data("questionTypeData");
            var optionQuestion = [questionTypeText.choose,questionTypeText.check,questionTypeText.list];

            //確認原本種類狀態，有需要才變，定義在newQuestion
            if(optionQuestion.indexOf(questionTypeData) != -1){
                if(optionQuestion.indexOf(type_val)!= -1){
                    $(dom).data("questionTypeData",type_val);
                    return;
                }
            }

            if (type_val == questionTypeText.ans || 
            type_val == questionTypeText.ltext) {
                $(questionLi).find(".question_div").html(this.ansDiv);
            }
            if (type_val == questionTypeText.choose || 
            type_val == questionTypeText.check || 
            type_val == questionTypeText.list) {
                $(questionLi).find(".question_div").html(this.chooseDiv);
                $(".option_list").sortable(); //切回來要重宣告
            }

            $(dom).data("questionTypeData",type_val);
            //綁定data
        }
     
    },


    /****************************
     刪除選擇類型的選項
     *****************************/
    delOption : function(dom){
        var _componentDom = this.getComponent(dom,"optionDiv");
        this.delWithAnimatiom(_componentDom);
    },

    /****************
     刪除題目
     ****************/
    delQuestion:function(dom){
        //避免誤刪
        swal({
            icon:"warning",
            title:"確定要刪除題目嗎?",
            buttons: ["先不用","確定"]
        }).then((result)=>{
            if(result){
                //questionLi
                var _componentDom = this.getComponent(dom,"questionDiv");
                this.delWithAnimatiom(_componentDom);
            }
        });        
    },

    

    /*************
     * 收費處理
     * **************/
    

    /***********************************************************
    submit form content
    ****************************************************************/
    record_form:function(){
         /*記錄使用者資料*/
        //準備送出物件
        var submit_content = {};

        //判斷是否為活動修改
        


        /****************
        寫入圖片
        **************/
        submit_content.cover=[];
        var coverImg = "";
        
        

        if(activityForm.reEditForm){
            coverImg = edit.activityInfo.cover[0]
            submit_content.cover.push(coverImg)
        }

        //判定是否有執行上傳圖片
        if(JSON.stringify(coverPage.returnData)!="{}"){
            coverImg = coverPage.returnData["data"]["link"]
            //把前面http那些去掉只剩下XXX.jpg 避免後端消毒
            coverImg = coverImg.slice(20);
            console.log(coverImg);
            //把原本的清掉
            submit_content.cover.splice(0,1);//第[0]開始刪1個
            submit_content.cover.push(coverImg);
        }



        /*************************
         *********輸入活動資訊*******
         *****************************/

        submit_content.activity = {};
        submit_content.activity["title"] = document.getElementById("activity_title").value;
        //textarea內容需要字符轉換content.replace(/\n/g,"<br/>"); 如果寫"\n"只會換一開始
        submit_content.activity["content"] = document.getElementById("activity_content").value;
        submit_content.activity["start_date"] = document.getElementById("start_date").value;
        submit_content.activity["deadline"] = document.getElementById("deadline").value;

        //是否開啟金流
        submit_content.activity["cashFlow"] = $("#cashFlow").prop("checked");

        //限制人數
        if(($("#submitMaxCheck").prop("checked"))){
            submit_content.activity["submitMax"] = $("#submitMaxInput").val();
        }else{
            submit_content.activity["submitMax"] = 0;
        }



        /***************************
         *******輸入題目與選項*******
         ****************************/
        submit_content.form = [];

        for (var question of $(".question_content")) {
            //直接$(elem)可將其轉為jquery物件，再用find()處理
            var new_submit_content = {};


            //找這個題目的type
            var new_question_type = $(question).find(".question_type option:selected").text();
            switch (new_question_type) {
                case questionTypeText.ans:
                    new_submit_content.type = "ans";
                    break;
                case questionTypeText.ltext:
                    new_submit_content.type = "ltext";
                    break;
                case questionTypeText.choose:
                    new_submit_content.type = "choose";
                    break;
                case questionTypeText.check:
                    new_submit_content.type = "check";
                    break;
                case questionTypeText.list:
                    new_submit_content.type = "list";
                    break;
            }

            //加入標題
            var new_question_tittle = $(question).find(".question_tittle").val();
            new_submit_content.data = {};
            new_submit_content.data["title"] = new_question_tittle;

            //是否必填
            var new_question_must = $(question).find(".must").prop("checked");
            new_submit_content.must = new_question_must;

            //依驗證類型加入限制
            if (new_question_type == questionTypeText.ans || new_question_type == questionTypeText.ltext) {
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
            if (new_question_type == questionTypeText.choose || new_question_type == questionTypeText.check || new_question_type == questionTypeText.list) {
                var new_question_option = [];
                //加入題目
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
            case feeTypeText.free:
                var fee_data = {};
                submit_content.fee["type"] = "free";
                fee_data.title = $(".fee_title").val();
                fee_data.price = $(".fee_price").val();
                if($(".fee_price").val()==0){
                    fee_data.isFree = true;
                }else{
                        fee_data.isFree = false;
                }
                fee_data.content = $(".fee_content").val();

                submit_content.fee["data"].push(fee_data);
                break;
            case feeTypeText.fix:
                var fee_data = {};
                submit_content.fee["type"] = "fix";
                fee_data.title = $(".fee_title").val();
                fee_data.price = $(".fee_price").val();
                if($(".fee_price").val()==0){
                    fee_data.isFree = true;
                }else{
                    fee_data.isFree = false;
                }
                fee_data.content = $(".fee_content").val();

                submit_content.fee["data"].push(fee_data);
                break;
            case feeTypeText.multi:
                submit_content.fee["type"] = "multi";

                for (var fee_multi_div of $(".fee_multi_div")) {
                    var fee_data = {};
                    fee_data.title = $(fee_multi_div).find(".fee_title").val();
                    fee_data.price = $(fee_multi_div).find(".fee_price").val();
                    if($(fee_multi_div).find(".fee_price").val()==0){
                        fee_data.isFree = true;
                    }else{
                        fee_data.isFree = false;
                    }
                    fee_data.content = $(fee_multi_div).find(".fee_content").val();

                    submit_content.fee["data"].push(fee_data);
                }

                break;

        }



        /***************
        寫入風格
        ***************/
        submit_content.style = {};
        submit_content.style["data"]=[];

        //從被選擇style input的父元素去尋找顏色的div
        var formStyleInput = $("#formStyleDiv").find("input:checked");
        var formStyleSelect = $(formStyleInput).parent();
        
        var mainColor = $(formStyleSelect).find("div[name='mainColor']").css("background-color");
        var secondColor = $(formStyleSelect).find("div[name='secondColor']").css("background-color");
        submit_content.style.title=$(formStyleInput).attr('id');
        submit_content.style["data"].push(mainColor);
        submit_content.style["data"].push(secondColor);



        //確認送出內容
        console.log(submit_content);
        
        return submit_content;
        
    
    }
};



  
</script>