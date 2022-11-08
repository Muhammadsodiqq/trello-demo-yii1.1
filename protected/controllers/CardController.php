<?php

class CardController extends Controller
{

    public function actionView($id)
    {
        try {
            $this->checkAjax('Card.View');
            $card = $this->loadModel($id);
            $cardTags = CardTags::model()->with('tag')->findAll("card_id = :card_id", ["card_id" => $id]);
            $card_members = CardMembers::model()->with('user')->findAll("card_id = :card_id", ["card_id" => $id]);
            echo CJSON::encode([
                "card" => $card,
                "tags" => $this->convertModelToArray($cardTags),
                "card_members" => $this->convertModelToArray($card_members)
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

    public function actionDelete($id)
    {
        $this->checkPermission('Card.Delete', Yii::app()->user->id);

        $this->loadModel($id)->delete();

        $this->redirect(Yii::app()->request->urlReferrer);
    }


    public function actionUpdate()
    {

        try {
            $this->checkAjax('Card.Update');

            $model = $this->loadModel($_POST['card_id']);

            if (!$model) {
                throw new Exception("invalid card id");
            }

            $model->title = @$_POST['title'];
            $model->description = @$_POST['description'];

            if (!$model->save()) {
                $this->getError($model);
            }

            echo CJSON::encode([
                'ok' => true,
                "data" => $model,
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

    public function actionUpdateDeadline()
    {
        try {
            $this->checkAjax('Card.UpdateDeadline');

            $model = $this->loadModel($_POST['card_id']);

            if (!$model) {
                throw new Exception("invalid card id");
            }

            $model->deadline = $_POST['deadline'] ?? $model->deadline;

            if (!$model->save()) {
                $this->getError($model);
            }

            echo CJSON::encode([
                'ok' => true,
                "data" => $model,
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

    public function actionUpdateCardMember()
    {
        try {
            $this->checkAjax('Card.UpdateCardMember');

            $model = $this->loadModel($_POST['card_id']);

            if (!$model) {
                throw new Exception("invalid card id");
            }

            CardMembers::model()->deleteAll('card_id = :card_id', ['card_id' => $_POST['card_id']]);
            if (isset($_POST['card_member_id'])) {
                foreach ($_POST['card_member_id'] as $card_memberid) {
                    $user = Users::model()->findByPk($card_memberid);
                    if (!$user) {
                        throw new Exception("invalid member");
                    }
                    $card_member = new CardMembers;

                    $card_member['card_id'] = $_POST['card_id'];
                    $card_member['user_id'] = $card_memberid;
                    if (!$card_member->save()) {
                        $this->getError($card_member);
                    }
                }
            }
            $card_members = CardMembers::model()->with('user')->findAll("card_id = :card_id", ["card_id" => $_POST['card_id']]);

            echo CJSON::encode([
                'ok' => true,
                "data" => $this->convertModelToArray($card_members),
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

    public function actionUpdateCardTag()
    {
        try {
            $this->checkAjax('Card.UpdateCardTag');

            $model = $this->loadModel($_POST['card_id']);

            if (!$model) {
                throw new Exception("invalid card id");
            }

            if (isset($_POST['tag_id'])) {
                CardTags::model()->deleteAll('card_id = :card_id AND tag_id = :tag_id', ['card_id' => $_POST['card_id'], "tag_id" => $_POST['tag_id']]);

                $tag = Tags::model()->findByPk($_POST['tag_id']);
                if (!$tag) {
                    throw new Exception("invalid tag");
                }
                $card_tags = new CardTags;

                $card_tags['card_id'] = $_POST['card_id'];
                $card_tags['tag_id'] = $_POST['tag_id'];
                if (!$card_tags->save()) {
                    $this->getError($card_tags);
                }
            }

            $cardTags = CardTags::model()->with('tag')->findAll("card_id = :card_id", ["card_id" => $id]);

            echo CJSON::encode([
                'ok' => true,
                "data" => $this->convertModelToArray($cardTags),
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

    public function actionCreate()
    {
        if (isset($_POST['Card'])) {
            $model = new Cards;
            $model->title = $_POST['Card']['title'];
            $model->description = $_POST['Card']['description'];
            $model->column_id = $_POST['Card']['column_id'];

            
            if ($model->save()) {
                if (!Yii::app()->user->checkAccess("admin")) {
                    $card_member = new CardMembers;
    
                    $card_member['card_id'] = $model->id;
                    $card_member['user_id'] = Yii::app()->user->id;
                    if ($card_member->save()) {
                        $this->redirect(Yii::app()->request->urlReferrer);
                    }
                }
            }
        }
    }

    public function loadModel($id)
    {
        $model = Cards::model()->with('cardMembers', 'cardTags')->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
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
}
