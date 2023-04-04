<form id="formSent" method="post">
    <input name="emails_sending" value="1">
    <input name="idr" value="0">
    <button class="btn btn-secondary">sent</button>
    <?php $sent = new ControllerAction(); $sent -> emails_sending();?>
</form>


<style>
    table{
        /*border: solid 1px;*/
    }
</style>


