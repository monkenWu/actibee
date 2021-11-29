<style>
    /* activityStyle */
    ul li {
        list-style: none;
    }

    ol li {
        list-style-type: decimal;
    }

    .option-item,
    .questionHandler {
        cursor: move;
    }

   

    /*選項外框 */
    .option-item,
    .feeItem {
        border: rgba(0, 0, 0, .125);
        border-radius: 4px;
        background-color: rgba(0, 0, 0, .125);
    }

    /*input*/
    .option_item {
        border-right: 0px;
        /* border-bottom-right-radius:0px;
        border-top-right-radius:0px; */
    }

    /* 選項X */
    .btn-outline-primary {
        /* border-bottom-left-radius:0px;
        border-top-left-radius:0px; */
        background-color: white;

    }

    /* .option-item:hover {
        border-color: var(--third-yellow);
        transition-duration: 0.5s;
    } */


    p,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: Microsoft JhengHei;
    }



    /* 去除滾動條 */
    /*增加jquery-step content 高度*/
    /* .content {
        height: 50rem;
    } */

    .feeCheck[type="checkbox"]:checked {
        background: var(--main-purple);
    }


    /* Radiotyle */
    input[type="radio"] {
        height: 20px;
        width: 20px;
    }

    input[type=radio]:checked::after {
        top: 1px;
        left: 1px;
        border: #fff solid 3px;
        height: 16px;
        width: 16px;
        transition-duration: 0.2s;

    }
</style>