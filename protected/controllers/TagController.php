<?php

class TagController extends Controller
{
	public function actionCreate($board_id, $card_id)
	{
		$transaction = Yii::app()->db->beginTransaction();

		try {
			$board = Boards::model()->findByPk($board_id);
			
			if (@$board->user_id !== Yii::app()->user->id) {
				throw new Exception('access denied board');
			}
			$this->checkAjax('Tag.Create');
			$model = new Tags;

			$tags = Tags::model()->findAll('board_id = :board_id', ['board_id' => $board_id]);
			$colors = Colors::model()->findAll();
			if (isset($_POST['Tags'])) {

				$model->attributes = @$_POST['Tags'];
				$model->board_id = $board_id;

				if ($model->save()) {
					$card_tags = new CardTags;
					// if($model['column']['board'])

					$card = Cards::model()->findByPk($card_id);
					// var_dump($card) ;die;

					if (@$card['column']['board']->user_id !== Yii::app()->user->id) {
						throw new Exception('access denied');
					}
					$card_tags['card_id'] = $card_id;
					$card_tags['tag_id'] = $model->id;

					if ($card_tags->save()) {
						$transaction->commit();

						echo CJSON::encode([
							'ok' => true,
							"data" => $model,
							"model" => $this->renderPartial("tag_form", [
								"model" => $model,
								'tags' => $tags,
								'colors' => $colors,
								'card_id' => $card_id
							], true, true)
						]);
						exit;
					}
				}
			}

			$transaction->rollback();

			echo CJSON::encode([
				'ok' => false,
				"model" => $this->renderPartial("tag_form", [
					"model" => $model,
					'tags' => $tags,
					'colors' => $colors,
					'card_id' => $card_id
				], true, true),
			]);
		} catch (Exception $error) {
			$transaction->rollback();

			echo CJSON::encode([
				'ok' => 'error',
				"msg" => $error->getMessage(),
			]);
		}
	}

	public function actionTegControl()
	{
		$transaction = Yii::app()->db->beginTransaction();

		try {
			$this->checkAjax('Tag.TegControl');
			// var_dump($_POST['CardTags']);
			if (isset($_POST['CardTags'])) {
				if (@$_POST['CardTags']['is_delete'] == true) {
					CardTags::model()->find('card_id = :card_id AND tag_id = :tag_id', ['card_id' => $_POST['CardTags']['card_id'], 'tag_id' => $_POST['CardTags']['tag_id']])->delete();
				} else {
					$isExists = CardTags::model()->find('card_id = :card_id AND tag_id = :tag_id', ['card_id' => $_POST['CardTags']['card_id'], 'tag_id' => $_POST['CardTags']['tag_id']]);

					if ($isExists) {
						throw new Exception("this tag is already exists for this board");
					}
					$model = new CardTags;

					$model->card_id = $_POST['CardTags']['card_id'];
					$model->tag_id = $_POST['CardTags']['tag_id'];

					if (!$model->save()) {
						$this->getError($model);
					}
					$data = Tags::model()->with('color')->findByPk($_POST['CardTags']['tag_id']);
				}
			}

			$transaction->commit();

			echo CJSON::encode([
				'ok' => true,
				"data" => @$data ? $this->convertModelToArray($data) : null,
			]);
		} catch (Exception $error) {
			$transaction->rollback();

			echo CJSON::encode([
				'ok' => false,
				"msg" => $error->getMessage(),
			]);
		}
	}
}
