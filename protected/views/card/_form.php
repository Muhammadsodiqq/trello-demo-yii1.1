<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />



<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'users-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation' => false,
	)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'title'); ?>
		<?php echo $form->textField($model, 'title', array("class" => "form-control")); ?>
		<?php echo $form->error($model, 'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'description'); ?>
		<?php echo $form->textArea($model, 'description', array("class" => "form-control")); ?>
		<?php echo $form->error($model, 'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'deadline'); ?>
		<?php echo $form->dateField($model, 'deadline', array("class" => "form-control")); ?>
		<?php echo $form->error($model, 'deadline'); ?>
	</div>

	Teg qo'shish: 
	<?php foreach ($tags as $key => $tag) {
		$card_tag = CardTags::model()->find('tag_id = :tag_id AND card_id = :card_id', ['tag_id' => $tag->id, 'card_id' => $model->id]);
	?>
		<div style="background-color: <?= $tag->color->name ?>;" class="form-check form-check-inline alert">
			<input class="form-check-input" <?= $card_tag ? "checked" : "" ?> name='Cards[tag_id][]' type="checkbox" id="<?= $tag->id ?>" value="<?= $tag->id ?>">
			<label class="form-check-label" for="<?= $tag->id ?>"><?= $tag->name ?></label>
		</div>
	<?php } ?>

	
	<div>
	Foydalanuvchi qo'shish
	<select class="selectpicker" name="Cards[card_member_id][]" multiple data-live-search="true">
		<?php foreach ($board_members as $key => $board_member) {
			$card_member = CardMembers::model()->find('user_id = :user_id AND card_id = :card_id', ['user_id' => $board_member->user_id, 'card_id' => $model->id]);
			
		?>
			<option <?= $card_member ? "selected" : "" ?> value="<?= $board_member->user_id?>" > <?=$board_member['user']->username?> </option>
		<?php } ?>
	</select>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Save'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script>
	document.querySelector('input[type="submit"]').classList.add('btn')
	document.querySelector('input[type="submit"]').classList.add('btn-primary')
</script>

<script type="text/javascript">
	$('select').selectpicker();
</script>