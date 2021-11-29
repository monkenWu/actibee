<div id="example-basic">
    <h3>Pt.1 Cover</h3>
    <section>
        <?php echo $cover ?? "" ?>
    </section>
    <h3>Effects</h3>
    <section>
        <p>Wonderful transition effects.</p>
    </section>
    <h3>Pager</h3>
    <section>
        <p>The next and previous buttons help you to navigate through your content.</p>
    </section>
</div>


<script src="<?php echo base_url() ?>assets/js/jquery.steps-1.1.0/jquery.steps.js" type="text/javascript"></script>

<!--呼叫 jquery step-->
<script>
    $("#example-basic").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true
    });

</script>
