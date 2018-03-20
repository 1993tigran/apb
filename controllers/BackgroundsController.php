<?php

namespace app\controllers;

use app\models\BackgroundImages;
use Yii;
use app\models\Backgrounds;
use app\models\search\BackgroundsSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BackgroundsController implements the CRUD actions for Backgrounds model.
 */
class BackgroundsController extends CommonController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Backgrounds models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BackgroundsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Backgrounds model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $model = Backgrounds::getBackgroundWithImages($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Backgrounds model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Backgrounds();
        $model_background_images = new BackgroundImages();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $background_images = Yii::$app->request->post('BackgroundImages')['image'];
            foreach ($background_images as $image) {
                $fileName = md5(microtime()) . '.jpg';
                if ($this->changeImageSize($image, $fileName,self::BACKGROUND_IMAGES , $model->width, $model->height)) {
                    $model_bg_img = new BackgroundImages();
                    $model_bg_img->image = $fileName;
                    $model_bg_img->background_id = $model->id;
                    if ($model_bg_img->validate()) {
                        $model_bg_img->save();
                    }
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'model_background_images' => $model_background_images,
        ]);
    }

    /**
     * Updates an existing Backgrounds model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_background_images = new BackgroundImages();
        $background_images = BackgroundImages::getBackgroundImagesById($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $background_image = Yii::$app->request->post('BackgroundImages')['image'];

            if (!empty($background_image)) {
                foreach ($background_image as $image) {
                    $image = $this->changeImageSize($image, $model->width, $model->height);
                    $fileName = md5(microtime()) . '.jpg';
                    if ($this->base64_to_img($image, $fileName, self::BACKGROUND_IMAGES)) {
                        $model_bg_img = new BackgroundImages();
                        $model_bg_img->image = $fileName;
                        $model_bg_img->background_id = $model->id;
                        if ($model_bg_img->validate()) {
                            $model_bg_img->save();
                        }
                    }
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'model_background_images' => $model_background_images,
            'background_images' => $background_images,
        ]);
    }

    /**
     * Deletes an existing Backgrounds model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $back_id = $model->id;
        $background_images = BackgroundImages::getBackgroundImagesById($back_id);
        if ($model->delete()) {
            foreach ($background_images as $background_image) {
                unlink($_SERVER['DOCUMENT_ROOT'] . self::BACKGROUND_IMAGES . $background_image->image);
            };
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Backgrounds model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Backgrounds the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($id)
    {
        if (($model = Backgrounds::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Get Backgrounds model.
     * If it is successful, return Data.
     *  @return array|bool
     */
    public function actionGetBackgroundsAjax()
    {
        if (Yii::$app->request->isAjax) {
            $ides = Yii::$app->request->get('backgroundIdes');
            if (!empty($ides)) {
                $model = new BackgroundImages();
                $data = $model->getBacBackgroundImages($ides);
                $back_image_directory = self::BACKGROUND_IMAGES_DIRECTORY;
                foreach ($data as $key => $background) {
                    $data[$key]['image'] = $this->getImageBase64($back_image_directory . $background['image']);
                }
                if (!empty($data)) {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return $data;
                }
                return false;
            }
        }
    }

    /**
     * Get Backgrounds image model.
     * If it is successful, return Data.
     * @return bool
     */
    public function actionDeleteBackgroundImageAjax()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->get('id');
            if (!empty($id)) {
                $model = BackgroundImages::findOne($id);

                $image_name = $model->image;
                if ($model->delete()) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . self::BACKGROUND_IMAGES . $image_name);
                    return true;
                }
                return false;
            }
        }
    }




}
