<?php

class ColorController extends Controller
{
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
