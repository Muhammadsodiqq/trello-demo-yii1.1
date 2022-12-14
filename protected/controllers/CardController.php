<?php

class CardController extends Controller
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


    public function actionView($id)
    {
        try {
            $this->checkAjax('Card.View');
            // $model = new Cards;
            $model = $this->loadModel($id);

            // if($model['column']['board']['user_id'] != Yii::app()->user->id){
            //     throw new Exception('access denied');
            // }

            $cardTags = CardTags::model()->with('tag')->findAll("card_id = :card_id", ["card_id" => $id]);
            $card_members = CardMembers::model()->with('user')->findAll("card_id = :card_id", ["card_id" => $id]);

            if (isset($_POST['Cards'])) {
                $model->title = @$_POST['Cards']['title'];
                $model->description = @$_POST['Cards']['description'];
                if ($model->save()) {
                    echo CJSON::encode([
                        'ok' => true,
                        "data" => $model
                    ]);
                    exit;
                }
            }

            echo CJSON::encode([
                'ok' => false,
                "model" => $this->renderPartial("_view_card", [
                    "model" => $model,
                    "tags" => $cardTags,
                    "card_members" => $card_members
                ], true, true),
            ]);
        } catch (Exception $error) {
            echo CJSON::encode([
                'ok' => 'error',
                "msg" => $error->getMessage(),
            ]);
        }
    }

    public function actionDelete($id)
    {
        $isOwn = $this->loadModel($id);
        if($isOwn['column']['board']['user_id'] != Yii::app()->user->id){
            throw new Exception('access denied');
        }

        $this->loadModel($id)->delete();

        $this->redirect(Yii::app()->request->urlReferrer);
    }

    public function actionUpdateDeadline($card_id)
    {
        try {
            $this->checkAjax('Card.UpdateDeadline');

            $model = $this->loadModel($card_id);

            if ($model['column']['board']['user_id'] != Yii::app()->user->id) {
                throw new Exception("access denied");
            }
            if (isset($_POST['Cards'])) {

                $model->attributes = $_POST['Cards'];

                if (empty($model->deadline))
                    $model->deadline = null;

                if ($model->save()) {
                    echo CJSON::encode([
                        'ok' => true,
                        "data" => $model
                    ]);
                }
            }

            echo CJSON::encode([
                'ok' => false,
                "model" => $this->renderPartial("_deadline_form", [
                    "model" => $model,
                ], true, true),
            ]);

        } catch (Exception $error) {
            echo CJSON::encode([
                'ok' => "error",
                "msg" => $error->getMessage(),
            ]);
        }
    }

    public function actionUpdateCardMember()
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $this->checkAjax();

            $model = $this->loadModel($_POST['card_id']);

            if (@$model['column']['board']['user_id'] != Yii::app()->user->id) {
                throw new Exception("access denied");
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

            $transaction->commit();

            echo CJSON::encode([
                'ok' => true,
                "data" => $this->convertModelToArray($card_members),
            ]);
        } catch (Exception $error) {
            $transaction->rollback();
            $httpVersion = Yii::app()->request->getHttpVersion();
            header("HTTP/$httpVersion 400");

            echo CJSON::encode([
                'ok' => false,
                "msg" => $error->getMessage(),
            ]);
        }
    }

    public function actionCreate($column_id)
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $this->checkAjax();
            $user_id = Yii::app()->user->id;
            $model = new Cards;
            if (isset($_POST['Cards'])) {
                $model->attributes = $_POST['Cards'];
                $model->column_id = $column_id;
                $isExists = Columns::model()->findByPk($column_id);
                $Board = Boards::model()->findByPk(@$isExists->board_id);
                $isBoardmember = BoardMembers::model()->find('board_id = :board_id AND user_id = :user_id', ["board_id" => @$Board->id, 'user_id' => $user_id]);
                if ((!@$isExists->board->user_id == $user_id) && !$isBoardmember) {
                    throw new Exception("you don't have acces for this board");
                }

                if ($model->save()) {
                    if (!Yii::app()->user->checkAccess("admin")) {
                        $card_member = new CardMembers;

                        $card_member['card_id'] = $model->id;
                        $card_member['user_id'] = $user_id;
                        if ($card_member->save()) {
                            $transaction->commit();

                            echo CJSON::encode([
                                'ok' => true,
                                "data" => $model
                            ]);
                            return;
                        }
                    }
                    $transaction->commit();

                    echo CJSON::encode([
                        'ok' => true,
                        "data" => $model
                    ]);
                    return;
                }
            }

            echo CJSON::encode([
                'ok' => false,
                "model" => $this->renderPartial("_form_card", ["model" => $model], true, true),
            ]);
        } catch (Exception $error) {
            $transaction->rollback();

            echo CJSON::encode([
                'ok' => "error",
                "msg" => $error->getMessage(),
            ]);
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
}
