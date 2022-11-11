<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="d-flex">
    <div class="form">

        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'sub-board-form',
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
            <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255, "class" => "form-control")); ?>
            <?php echo $form->error($model, 'title'); ?>
        </div>


        <div class="row">
            <?php echo $form->labelEx($model, 'color_id'); ?>
            <?php echo $form->dropDownList(
                $model,
                'color_id',
                CHtml::listData($colors, 'id', 'name'),
                array('empty' => Yii::t('app', 'Please select an color.'),)
            ); ?>
            <?php echo $form->error($model, 'color_id'); ?>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Save', ['id' => "sub_modal_saver"]); ?>
        </div>



    </div><!-- form -->
    <hr>
    <div class="m-2 tags_check" style="min-width: 30%;">
        <h4>Teglar</h4>
        <?php foreach ($tags as $tag) { 
                $cardTeg = CardTags::model()->find("card_id = :card_id AND tag_id = :tag_id",['card_id' => $card_id, 'tag_id' => $tag['id']]);
            ?>
            <div class="form-check">
                <input class="tag_checkbox form-check-input" type="checkbox" value="<?= $tag['id'] ?>" id="<?= $tag['id'] ?>" <?= $cardTeg ? 'checked' : ''?>>
                <label class="form-check-label" for="<?= $tag['id'] ?>">
                    <?= $tag->name ?>
                </label>
            </div>
        <?php } ?>
    </div>

</div>
<?php $this->endWidget(); ?>
<script>
    document.querySelector('#modal_saver').classList.add('btn')
    document.querySelector('#modal_saver').classList.add('btn-primary')


</script>