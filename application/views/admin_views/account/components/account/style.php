<style>
    a{
        text-decoration:none;
    }

    .profile-cover__action {
        background: var(--main-yellow);
    }

    .new-form-btn {
        color: var(--second-gray);
    }

    .new-form-btn:hover {
        color: var(--main-gray);
        transition-duration: 0.5s;
    }

    .activity-item {
        height: 15rem;
        border-radius: 20px;
    }

    .activity-item .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .activity-item .activityImg {
        display: block;
        width: 100%;
        height: 150px;
        z-index: 2;
        /*    background-position: 0px -300px;*/
        border-radius: 20px 20px 0 0
    }

    /* .activityBtn{
    position: absolute;
    bottom: 7px;
    
} */

    .activity-item .card-title {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .new-activity {
        border: double 3px var(--second-gray);
    }

    .profile-img {
        display: block;
        height: 120px;
        width: 120px;
        border-radius: 100%;
        cursor: pointer;
        background: url('<?= $userPhoto ?>') no-repeat center center/cover,
            white;
    }

    .profile-img:hover {
        box-shadow: 0 0 3pt var(--main-black);
        /* outline: 1px solid var(--main-green); */
        transition-duration: 0.3s;

    }
</style>