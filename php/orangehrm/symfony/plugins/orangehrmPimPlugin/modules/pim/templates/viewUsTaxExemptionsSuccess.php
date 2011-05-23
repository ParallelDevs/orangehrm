<div id="leftMenu">
    <?php include_partial('leftmenu', array('empNumber' => $empNumber, 'form' => $form));?>
</div>
<div id="messagebar" class="<?php echo isset($messageType) ? "messageBalloon_{$messageType}" : ''; ?>" style="margin-left: 16px;width: 550px;">
    <span style="font-weight: bold;"><?php echo isset($message) ? $message : ''; ?></span>
</div>
<div class="outerbox" id="outerbox">
    <div class="mainHeading"><h2><?php echo __('Tax Exemptions'); ?></h2></div>
    <div>
        <form id="frmEmpTaxExemptions" method="post" action="<?php echo url_for('pim/viewUsTaxExemptions'); ?>">
            <?php echo $form['_csrf_token']; ?>
            <?php echo $form['empNumber']->render(); ?>
            <br />
            <span class="label"><?php echo __("Federal Income Tax") ?></span>
            <div>
                <?php echo $form['federalStatus']->renderLabel(__("Marital Status")); ?>
                <?php echo $form['federalStatus']->render(array("class" => "drpDown")); ?>
                <br class="clear" />
            </div>
            <div>
                <?php echo $form['federalExemptions']->renderLabel(__("Exemptions")); ?>
                <?php echo $form['federalExemptions']->render(array("class" => "txtBox", "maxlength" => 70, 'size' => 10)); ?>
                <br class="clear" />
                <br class="clear" />
                <br class="clear" />
            </div>
            <span class="label"><?php echo __("State Income Tax") ?></span>
            <div>
                <?php echo $form['state']->renderLabel(__("State")); ?>
                <?php echo $form['state']->render(array("class" => "drpDown")); ?>
                <br class="clear" />
            </div>
            <div>
                <?php echo $form['stateStatus']->renderLabel(__("Marital Status")); ?>
                <?php echo $form['stateStatus']->render(array("class" => "drpDown")); ?>
                <br class="clear" />
            </div>
            <div>
                <?php echo $form['stateExemptions']->renderLabel(__("Exemptions")); ?>
                <?php echo $form['stateExemptions']->render(array("class" => "txtBox", "maxlength" => 70, 'size' => 10)); ?>
                <br class="clear" />
                <br class="clear" />
                <br class="clear" />
            </div>
            <div>
                <?php echo $form['unempState']->renderLabel(__("Unemployment State")); ?>
                <?php echo $form['unempState']->render(array("class" => "drpDown")); ?>
                <br class="clear" />
            </div>
            <div>
                <?php echo $form['workState']->renderLabel(__("Work State")); ?>
                <?php echo $form['workState']->render(array("class" => "drpDown")); ?>
                <br class="clear" />
            </div>
            <div class="formbuttons">
                <input type="button" class="savebutton" id="btnSave" value="<?php echo __("Edit"); ?>" tabindex="2" />
            </div>
        </form>
    </div>
</div>
<div id="bottom">
    <?php echo include_component('pim', 'customFields', array('empNumber'=>$empNumber, 'screen' => 'tax'));?>
    <?php echo include_component('pim', 'attachments', array('empNumber'=>$empNumber, 'screen' => 'tax'));?>
</div>

<link href="<?php echo public_path('../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css')?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.core.js')?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.datepicker.js')?>"></script>
<?php echo stylesheet_tag('orangehrm.datepicker.css') ?>
<?php echo javascript_include_tag('orangehrm.datepicker.js')?>

<?php echo stylesheet_tag('../orangehrmPimPlugin/css/viewUsTaxExemptionsSuccess'); ?>
<?php echo javascript_include_tag('../orangehrmPimPlugin/js/viewUsTaxExemptionsSuccess'); ?>

<script type="text/javascript">
    //<![CDATA[
    //we write javascript related stuff here, but if the logic gets lengthy should use a seperate js file
    var lang_edit = "<?php echo __("Edit"); ?>";
    var lang_save = "<?php echo __("Save"); ?>";
    var fileModified = 0;
    //]]>
</script>