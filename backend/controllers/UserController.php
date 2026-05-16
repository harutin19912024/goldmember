<?php

namespace backend\controllers;

use common\components\AuthController;
use common\models\PasswordChangeForm;
use common\models\ProfileUpdateForm;
use Yii;
use common\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AuthController {

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
			$model->role = 1;
			$model->user_number = Yii::$app->request->post('User')['user_number'];
			$model->email = Yii::$app->request->post('User')['email'];
			$model->username = Yii::$app->request->post('User')['username'];
			$model->phone_number = Yii::$app->request->post('User')['phone_number'];
			$model->setPassword(Yii::$app->request->post('User')['password']);
			$model->generateAuthKey();
			if($model->save()){
				return $this->redirect('index');
			}
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }
	
	public function actionAllowToAddProduct() {
		if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('userId');
            $allow = Yii::$app->request->post('allow');
			$model = $this->findModel($id);
			$model->allow_create = $allow;
			if($model->save()) {
				echo json_encode(['success' => true]);
			} else {
				echo json_encode(['success' => false]);
			}
			exit();
		}
	}

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
			$model->user_number = Yii::$app->request->post('User')['user_number'];
			$model->username = Yii::$app->request->post('User')['username'];
			$model->phone_number = Yii::$app->request->post('User')['phone_number'];
			$model->email = Yii::$app->request->post('User')['email'];
			if(!empty(Yii::$app->request->post('User')['password'])){
				$model->setPassword(Yii::$app->request->post('User')['password']);
			} else {
				$model->setPassword($model->password);
			}
			$model->generateAuthKey();
			if($model->save()){
				return $this->redirect('index');
			}
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionChangePassword() {
        $id = Yii::$app->user->identity->id;

        $model = new PasswordChangeForm(User::findOne($id));

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'SUCCESSFULLY_PASSWORD'));
            return $this->refresh();
        } else {
            return $this->render('change_password', [
                        'user' => $model,
            ]);
        }
    }

    public function actionChangeUsername() {
        $id = intval(Yii::$app->user->identity->id);

        $user = $this->findModel($id);
        $user->scenario = User::SCENARIO_PROFILE;
        $model = new ProfileUpdateForm($user);

        /*   //ЕЛИ ЕТО AJAX
          if (\Yii::$app->request->isAjax && \Yii::$app->request->isPost) {
          if ($user->load(\Yii::$app->request->post())) {
          \Yii::$app->response->format = Response::FORMAT_JSON; //verdarcnuma json formatov brauzer@
          return ActiveForm::validate($user);
          }
          } */

        if ($model->load(Yii::$app->request->post()) && $model->update()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'SUCCESSFULLY_LOGIN'));
            return $this->refresh();
        } else {
            return $this->render('change_username', [
                        'user' => $model,
            ]);
        }
    }

}
