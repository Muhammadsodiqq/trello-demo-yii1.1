<?php

class ColumnController extends Controller
{
	public function actionDelete($id)
	{
		$model=Columns::model()->findByPk($id)->delete();

		$this->redirect(Yii::app()->request->urlReferrer);
	}

	public function actionCreate()
	{
		try {
			$this->checkAjax();

			$model = new Columns;
			$model->title = @$_POST['title'];
			$model->board_id = @$_POST['board_id'];
			if (!$model->save()) {
				$this->getError($model);
			}
			echo CJSON::encode([
				'ok' => true,
				"data" => $model
			]);
		} catch (Exception $error) {
			$httpVersion = Yii::app()->request->getHttpVersion();
			header("HTTP/$httpVersion 400");

			echo CJSON::encode([
				'ok' => false,
				"msg" => $error->getMessage()
			]);
		}
	}

}