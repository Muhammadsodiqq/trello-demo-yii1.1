<?php

class UserController extends Controller
{
	private $_identity;
	/**
	 * @property RAuthorizer
	 */
	private $_authorizer;

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
		$transaction = Yii::app()->db->beginTransaction();

		$model = new Users('create');

		if (isset($_POST['Users'])) {

			$model->attributes = $_POST['Users'];

			if ($model->save()) {
				$assignment = new Authassignment;

				$assignment->itemname = 'user';
				$assignment->userid = $model->id;

				if($assignment->save()){
					$model["password"] = $_POST['Users']['password'];
					$this->_identity = new UserIdentity($model->username, $model->password);
	
					if ($this->_identity->authenticate()) {
						Yii::app()->user->login($this->_identity);
						if (@$_COOKIE["tokenLink"]) {
							$transaction->commit();

							$this->redirect('/inviteLink/invite/token/' . $_COOKIE["tokenLink"]);
						}
						$transaction->commit();

						$this->redirect("/");
					}
				}
			}
		}
		
		$transaction->rollback();

		$this->render('signup', array('model' => $model));
	}


	public function actionLogin()
	{

		$model = new Users;

		if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if (isset($_POST['Users'])) {
			$model->attributes = $_POST['Users'];
			if ($model->validate() && $model->login()) {

				if (@$_COOKIE["tokenLink"]) {

					$this->redirect('/inviteLink/invite/token/' . $_COOKIE["tokenLink"]);
				}
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		$this->render('login', array('model' => $model));
	}


	public function actionLogout()
	{
		Yii::app()->user->logout();
		unset(Yii::app()->request->cookies['tokenLink']);
		$this->redirect(Yii::app()->homeUrl);
	}
}
