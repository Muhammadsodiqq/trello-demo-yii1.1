<?php
/* @var $this RegisterController */

$this->breadcrumbs=array(
	'Login',
);
/* @var $this UserController */
/* @var $model User */




?>

<h1>Login</h1>

<?php $this->renderPartial('_form-login', array('model'=>$model)); ?>