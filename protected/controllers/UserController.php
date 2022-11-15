<?php

class UserController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	// /**
	//  * @return array action filters
	//  */
	// public function filters()
	// {
	// 	return array(
	// 		'rights', // perform access control for CRUD operations
	// 	);
	// }


	private $_identity;
	/**
	 * @property RAuthorizer
	 */
	private $_authorizer;

	public function actionIndex()
	{
		$this->render('index');
	}


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

				if ($assignment->save()) {
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
