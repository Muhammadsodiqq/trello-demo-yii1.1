<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'board-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        // 'enableAjaxValidation' => true,
        'enableClientValidation' => true,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 255,"class" => "form-control")); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton('Save',['id' => "modal_saver"]); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script>

	document.querySelector('#modal_saver').classList.add('btn')
	document.querySelector('#modal_saver').classList.add('btn-primary')
</script>
