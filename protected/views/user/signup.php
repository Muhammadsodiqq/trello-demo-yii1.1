<?php
/* @var $this RegisterController */

$this->breadcrumbs=array(
	'Register',
);
/* @var $this UserController */
/* @var $model User */




?>

<h1>Register</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>