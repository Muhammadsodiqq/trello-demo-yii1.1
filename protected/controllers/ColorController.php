<?php

class ColorController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}


	public function actionCreate()
	{
		$model = new Colors;

		if (isset($_POST['Colors'])) {
			$model->attributes = $_POST['Colors'];
			if ($model->save())
				$this->redirect(Yii::app()->request->urlReferrer);
		}
	}
}
