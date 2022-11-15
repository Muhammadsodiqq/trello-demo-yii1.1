<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'sub-board-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        // 'enableAjaxValidation' => true,
        'enableClientValidation' => true,
    ));

     echo $form->errorSummary($model); 
    $date = (new DateTime($model->deadline))->format('d.m.Y');
    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name' => 'Cards[deadline]',
        'model' => $model,
        'value' => $date,
        // jQuery datepicker plugin options
        'options' => array(
            'showAnim' => 'fold',
            'showOn' => 'button',
            'showButtonPanel' => true,
            'dateFormat' => 'd.m.yy',
        ),
        'htmlOptions' => array(),
    )); ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Save', ['id' => "sub_modal_saver"]); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
