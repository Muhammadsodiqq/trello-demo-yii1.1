<?php

class TagController extends Controller
{
    public function actionCreate()
    {
        try {
            $this->checkAjax();
            $model = new Tags;
            $model->name = @$_POST['name'];
            $model->color_id = @$_POST['color_id'];
            $model->board_id = @$_POST['board_id'];

            if (!$model->save()) {
                $this->getError($model);	
            }

            $card_tags = new CardTags;

            $card_tags['card_id'] = $_POST['card_id'];
            $card_tags['tag_id'] = $model->id;

            if(!$card_tags->save()){
                $this->getError($card_tags);
			}
			$data = Tags::model()->with('color')->findByPk($model->id);
			echo CJSON::encode([
                'ok' => true,
                "data" => $this->convertModelToArray($data),
            ]);

        } catch (Exception $error) {
			$httpVersion = Yii::app()->request->getHttpVersion();
            header("HTTP/$httpVersion 400");

            echo CJSON::encode([
                'ok' => false,
                "msg" => $error->getMessage(),
            ]);
        }
    }

	public function actionGetTags()
	{
		try {
			$this->checkAjax();

			$model = Tags::model()->findAll('board_id = :board_id',["board_id" => $_POST["board_id"]]);

			$arr = [];
			foreach($model as $tag){
				$card_member = CardTags::model()->find('tag_id = :tag_id AND card_id = :card_id', ['tag_id' => $tag['id'], 'card_id' => $_POST['card_id']]);
				$sub_arr = [];
				if($card_member){
					$sub_arr = [...$tag];
					$sub_arr['is_card_tag'] = true;
				}else{
					$sub_arr = [...$tag];
					$sub_arr['is_card_tag'] = false;
				}

				array_push($arr, $sub_arr);
			}

			echo CJSON::encode([
                'ok' => true,
                "data" => $arr,
            ]);
		} catch (Exception $error) {
			$httpVersion = Yii::app()->request->getHttpVersion();
            header("HTTP/$httpVersion 400");

            echo CJSON::encode([
                'ok' => false,
                "msg" => $error->getMessage(),
            ]);
		}
	}

	public function actionTegControl()
	{
		$this->checkAjax();
		if(!@$_POST['is_delete']){
			throw new Exception("invalid request");
		}
		$card = Cards::model()->find('card_id = :card_id ', ['card_id' => $_POST['card_id'], 'tag_id' => $_POST['tag_id']]);
		$tag = Tags::model()->find('tag_id = :tag_id', ['card_id' => $_POST['card_id'], 'tag_id' => $_POST['tag_id']]);

		if(!$model){
			throw new Exception("invalid request");
		}
		
		if(@$_POST['is_delete'] == true){
			$model->delete();
		}else{

		}
	}
}
