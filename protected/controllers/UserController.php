<?php

class UserController extends Controller
{
	private $_identity;

	public function actionIndex()
	{
		$this->render('index');
	}



	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/

	public function actionSignup()
	{
		$model = new Users;

		if (isset($_POST['Users'])) {

			$model->attributes = $_POST['Users'];

			if ($model->save()) {
				$model["password"]= $_POST['Users']['password'];
				$this->_identity = new UserIdentity($model->username,$model->password);

				if($this->_identity->authenticate()){
					Yii::app()->user->login($this->_identity);
				 	$this->redirect("/");
				}
			}
		}

		$this->render('signup', array('model' => $model));
	}


	public function actionLogin()
	{
		$model=new Users;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['Users']))
		{
			$model->attributes = $_POST['Users'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
