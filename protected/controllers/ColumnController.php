<?php

class ColumnController extends Controller
{
	public function actionDelete($id)
	{
		$model=Columns::model()->findByPk($id)->delete();

		$this->redirect(Yii::app()->request->urlReferrer);

	}

}