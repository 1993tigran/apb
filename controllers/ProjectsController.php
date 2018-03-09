<?php

namespace app\controllers;

use app\models\Backgrounds;
use app\models\ImagesSize;
use app\models\ProjectsBackgrounds;
use app\models\ProjectsImages;
use app\models\ProjectsImagesSize;
use Yii;
use app\models\Projects;
use app\models\search\ProjectsSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectsController implements the CRUD actions for Projects model.
 */
class ProjectsController extends Controller
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
     * Lists all Projects models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Projects model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Projects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Projects();

        $background_categories = ArrayHelper::map(Backgrounds::find()->all(), 'id', 'title');
        $model_images_size = ImagesSize::find()->all();
        $images_size = [];
        foreach ($model_images_size as $key => $val) {
            $images_size[$val->id] = $val->width . ' × ' . $val->height;
        }
        if ($model->load(Yii::$app->request->post())) {
            $post_projects = Yii::$app->request->post('Projects');
            $background_ides = Yii::$app->request->post('Projects')['background_ides'];
            $images_size_ides = Yii::$app->request->post('Projects')['images_size'];

            if (!empty($post_projects['front_img']) &&
                !empty($post_projects['back_img']) &&
                !empty($post_projects['top_img']) &&
                !empty($post_projects['bottom_img']) &&
                !empty($post_projects['left_img']) &&
                !empty($post_projects['right_img']) &&
                !empty(count($background_ides)) &&
                !empty(count($images_size_ides))
            ) {

                $images = [
                    'front_img' => $post_projects['front_img'],
                    'back_img' => $post_projects['back_img'],
                    'top_img' => $post_projects['top_img'],
                    'bottom_img' => $post_projects['bottom_img'],
                    'left_img' => $post_projects['left_img'],
                    'right_img' => $post_projects['right_img'],
                ];

                $image_names = [];
                $save_path = '/web/uploads/projects_image/';
                foreach ($images as $key => $image) {
                    $fileName = md5(microtime()) . '.jpg';
                    $fileName = $this->base64_to_img($image, $fileName, $save_path);
                    $image_names[$key] = $fileName;
                }

                $model->front_img = $image_names['front_img'];
                $model->back_img = $image_names['back_img'];
                $model->top_img = $image_names['top_img'];
                $model->bottom_img = $image_names['bottom_img'];
                $model->left_img = $image_names['left_img'];
                $model->right_img = $image_names['right_img'];

                if ($model->save()) {
                    foreach ($background_ides as $background_id) {
                        $model_project_back = new ProjectsBackgrounds();
                        $model_project_back->project_id = $model->id;
                        $model_project_back->background_id = $background_id;
                        $model_project_back->save();
                    }
                    foreach ($images_size_ides as $size_id) {
                        $model_project_images_size = new ProjectsImagesSize();
                        $model_project_images_size->project_id = $model->id;
                        $model_project_images_size->images_size = $size_id;
                        $model_project_images_size->save();
                    }
                    return $this->redirect('/project-image-generate/' . $model->id);
                }
            }

        }

        return $this->render('create', [
            'model' => $model,
            'background_categories' => $background_categories,
            'images_size' => $images_size,
        ]);
    }

    /**
     * Updates an existing Projects model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $background_categories = ArrayHelper::map(Backgrounds::find()->all(), 'id', 'title');
        $model_images_size = ImagesSize::find()->all();

        $selected_project_back = ArrayHelper::map(ProjectsBackgrounds::getProjectsBackgroundsByProjectId($model->id), 'background_id', 'background_id');
        $selected_images_size = ArrayHelper::map(ProjectsImagesSize::getProjectsImagesSizeByProjectId($model->id), 'images_size', 'images_size');

        $path_select_img = '/uploads/projects_image/';
        $select_images = [
            'front_img' => $this->getImageBase64($path_select_img . $model->front_img),
            'back_img' => $this->getImageBase64($path_select_img . $model->back_img),
            'top_img' => $this->getImageBase64($path_select_img . $model->top_img),
            'bottom_img' => $this->getImageBase64($path_select_img . $model->bottom_img),
            'left_img' => $this->getImageBase64($path_select_img . $model->left_img),
            'right_img' => $this->getImageBase64($path_select_img . $model->right_img),
        ];
        $images_size = [];
        foreach ($model_images_size as $key => $val) {
            $images_size[$val->id] = $val->width . ' × ' . $val->height;
        }
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            $post_projects = Yii::$app->request->post('Projects');
            $background_ides = Yii::$app->request->post('Projects')['background_ides'];
            $images_size_ides = Yii::$app->request->post('Projects')['images_size'];
            if (!empty($post_projects['front_img']) &&
                !empty($post_projects['back_img']) &&
                !empty($post_projects['top_img']) &&
                !empty($post_projects['bottom_img']) &&
                !empty($post_projects['left_img']) &&
                !empty($post_projects['right_img']) &&
                !empty(count($background_ides)) &&
                !empty(count($images_size_ides))
            ) {
                $create_image_path = '/web/uploads/images/' . $model->id;
                ProjectsImages::deleteAll(['project_id' => $model->id]);
                $this->deleteDirectory($_SERVER['DOCUMENT_ROOT'] . $create_image_path);
                $images = [
                    'front_img' => $post_projects['front_img'],
                    'back_img' => $post_projects['back_img'],
                    'top_img' => $post_projects['top_img'],
                    'bottom_img' => $post_projects['bottom_img'],
                    'left_img' => $post_projects['left_img'],
                    'right_img' => $post_projects['right_img'],
                ];
                $project_model = $this->findModel($id);
                $old_images = [
                    'front_img' => $project_model->front_img,
                    'back_img' => $project_model->back_img,
                    'top_img' => $project_model->top_img,
                    'bottom_img' => $project_model->bottom_img,
                    'left_img' => $project_model->left_img,
                    'right_img' => $project_model->right_img,
                ];

                $image_names = [];
                $save_path = '/web/uploads/projects_image/';
                foreach ($old_images as $old_image) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . $save_path . $old_image);
                }
                foreach ($images as $key => $image) {
                    $fileName = md5(microtime()) . '.jpg';
                    $fileName = $this->base64_to_img($image, $fileName, $save_path);
                    $image_names[$key] = $fileName;
                }

                $model->front_img = $image_names['front_img'];
                $model->back_img = $image_names['back_img'];
                $model->top_img = $image_names['top_img'];
                $model->bottom_img = $image_names['bottom_img'];
                $model->left_img = $image_names['left_img'];
                $model->right_img = $image_names['right_img'];

                if ($model->save()) {

                    if (ProjectsBackgrounds::deleteAll(['project_id' => $model->id])) {
                        foreach ($background_ides as $background_id) {
                            $model_project_back = new ProjectsBackgrounds();
                            $model_project_back->project_id = $model->id;
                            $model_project_back->background_id = $background_id;
                            $model_project_back->save();
                        }
                    }

                    if (ProjectsImagesSize::deleteAll(['project_id' => $model->id])) {
                        foreach ($images_size_ides as $size_id) {
                            $model_project_images_size = new ProjectsImagesSize();
                            $model_project_images_size->project_id = $model->id;
                            $model_project_images_size->images_size = $size_id;
                            $model_project_images_size->save();
                        }
                    }

                    return $this->redirect('/project-image-generate/' . $model->id);
                }
            }

        }
        return $this->render('update', [
            'model' => $model,
            'background_categories' => $background_categories,
            'images_size' => $images_size,
            'selected_project_back' => $selected_project_back,
            'selected_images_size' => $selected_images_size,
            'select_images' => $select_images,
        ]);
    }

    /**
     * Edit an existing Projects model.
     * If Edit is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax) {
            $create_image_path = '/web/uploads/images/' . $model->id;
            ProjectsImages::deleteAll(['project_id' => $model->id]);
            $this->deleteDirectory($_SERVER['DOCUMENT_ROOT'] . $create_image_path);
            return true;
        }

        $background_categories = ArrayHelper::map(Backgrounds::find()->all(), 'id', 'title');
        $model_images_size = ImagesSize::find()->all();

        $selected_project_back = ArrayHelper::map(ProjectsBackgrounds::getProjectsBackgroundsByProjectId($model->id), 'background_id', 'background_id');
        $selected_images_size = ArrayHelper::map(ProjectsImagesSize::getProjectsImagesSizeByProjectId($model->id), 'images_size', 'images_size');

        $path_select_img = '/uploads/projects_image/';
        $select_images = [
            'front_img' => $this->getImageBase64($path_select_img . $model->front_img),
            'back_img' => $this->getImageBase64($path_select_img . $model->back_img),
            'top_img' => $this->getImageBase64($path_select_img . $model->top_img),
            'bottom_img' => $this->getImageBase64($path_select_img . $model->bottom_img),
            'left_img' => $this->getImageBase64($path_select_img . $model->left_img),
            'right_img' => $this->getImageBase64($path_select_img . $model->right_img),
        ];
        $images_size = [];
        foreach ($model_images_size as $key => $val) {
            $images_size[$val->id] = $val->width . ' × ' . $val->height;
        }
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            $post_projects = Yii::$app->request->post('Projects');
            $background_ides = Yii::$app->request->post('Projects')['background_ides'];
            $images_size_ides = Yii::$app->request->post('Projects')['images_size'];
            if (!empty($post_projects['front_img']) &&
                !empty($post_projects['back_img']) &&
                !empty($post_projects['top_img']) &&
                !empty($post_projects['bottom_img']) &&
                !empty($post_projects['left_img']) &&
                !empty($post_projects['right_img']) &&
                !empty(count($background_ides)) &&
                !empty(count($images_size_ides))
            ) {

                $images = [
                    'front_img' => $post_projects['front_img'],
                    'back_img' => $post_projects['back_img'],
                    'top_img' => $post_projects['top_img'],
                    'bottom_img' => $post_projects['bottom_img'],
                    'left_img' => $post_projects['left_img'],
                    'right_img' => $post_projects['right_img'],
                ];
                $project_model = $this->findModel($id);
                $old_images = [
                    'front_img' => $project_model->front_img,
                    'back_img' => $project_model->back_img,
                    'top_img' => $project_model->top_img,
                    'bottom_img' => $project_model->bottom_img,
                    'left_img' => $project_model->left_img,
                    'right_img' => $project_model->right_img,
                ];

                $image_names = [];
                $save_path = '/web/uploads/projects_image/';
                foreach ($old_images as $old_image) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . $save_path . $old_image);
                }
                foreach ($images as $key => $image) {
                    $fileName = md5(microtime()) . '.jpg';
                    $fileName = $this->base64_to_img($image, $fileName, $save_path);
                    $image_names[$key] = $fileName;
                }

                $model->front_img = $image_names['front_img'];
                $model->back_img = $image_names['back_img'];
                $model->top_img = $image_names['top_img'];
                $model->bottom_img = $image_names['bottom_img'];
                $model->left_img = $image_names['left_img'];
                $model->right_img = $image_names['right_img'];

                if ($model->save()) {

                    if (ProjectsBackgrounds::deleteAll(['project_id' => $model->id])) {
                        foreach ($background_ides as $background_id) {
                            $model_project_back = new ProjectsBackgrounds();
                            $model_project_back->project_id = $model->id;
                            $model_project_back->background_id = $background_id;
                            $model_project_back->save();
                        }
                    }

                    if (ProjectsImagesSize::deleteAll(['project_id' => $model->id])) {
                        foreach ($images_size_ides as $size_id) {
                            $model_project_images_size = new ProjectsImagesSize();
                            $model_project_images_size->project_id = $model->id;
                            $model_project_images_size->images_size = $size_id;
                            $model_project_images_size->save();
                        }
                    }

                    return $this->redirect('/project-image-generate/' . $model->id);
                }
            }

        }
        return $this->render('edit', [
            'model' => $model,
            'background_categories' => $background_categories,
            'images_size' => $images_size,
            'selected_project_back' => $selected_project_back,
            'selected_images_size' => $selected_images_size,
            'select_images' => $select_images,
        ]);
    }

    /**
     * Deletes an existing Projects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $project_id = $model->id;
        if (!empty($model)) {
            $images = [
                'front_img' => $model->front_img,
                'back_img' => $model->back_img,
                'top_img' => $model->top_img,
                'bottom_img' => $model->bottom_img,
                'left_img' => $model->left_img,
                'right_img' => $model->right_img,
            ];
            if ($model->delete()) {
                $create_image_path = '/web/uploads/images/' . $project_id;
                $this->deleteDirectory($_SERVER['DOCUMENT_ROOT'] . $create_image_path);
                foreach ($images as $image) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/web/uploads/projects_image/' . $image);
                }
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * ProjectImageslist an existing ProjectsImages model.
     * If ProjectImageslist is successful, the browser will be redirected to the 'ProjectImageslist view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProjectImagesSizeList($id)
    {
        $model_images_size = $this->findImagesSizeWithProjectsImages($id);
        return $this->render('project-images-size-list', [
            'model_images_size' => $model_images_size,
            'project_id' => $id
        ]);
    }

    public function actionProjectImagesList()
    {
        $project_id = Yii::$app->request->get('project_id');
        $size_id = Yii::$app->request->get('size_id');
        $images_size = ImagesSize::findOne($size_id);
        $project_images = ProjectsImages::getProjectsImagesByIdes($project_id,$size_id);
        return $this->render('project-images-list', [
            'project_images' => $project_images,
            'project_id' => $project_id,
            'images_size' => $images_size
        ]);
    }

    /**
     * Finds the Projects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Projects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Projects::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the ProjectsImages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProjectsImages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findImagesSizeWithProjectsImages($id)
    {
        $projects_images_sizes = ProjectsImagesSize::gatProjectImagesWithSize($id);

        if ($projects_images_sizes !== null) {
            return $projects_images_sizes;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionGenerateImageSizeAjax()
    {
        $project_id = Yii::$app->request->get('id');
        $projectsImagesSize = ProjectsImagesSize::getProjectsImagesSize($project_id);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $projectsImagesSize;
    }

    public function actionProjectImageGenerate()
    {
        $this->layout = 'project-generate';
        $id = Yii::$app->request->get('id');
        $model_projects = Projects::findOne($id);
        $projectsImagesSize = ProjectsImagesSize::getProjectsImagesSize($id);
        if (!empty($model_projects)) {
            if (Yii::$app->request->isAjax) {

                $model_backgrounds = ProjectsBackgrounds::getProjectsWithBackgrounds($id);

                $back_image_directory = '/uploads/background-image/';
                $key = 0;
                foreach ($model_backgrounds as $model_background) {
                    foreach ($model_background->projectsBackgrounds as $background) {
                        $background_images[$key] = $this->getImageBase64($back_image_directory . $background->image);
                        $key++;
                    }
                }
                $image_directory = '/uploads/projects_image/';
                $model_projects->front_img = $image_directory . $model_projects->front_img;
                $model_projects->back_img = $image_directory . $model_projects->back_img;
                $model_projects->top_img = $image_directory . $model_projects->top_img;
                $model_projects->bottom_img = $image_directory . $model_projects->bottom_img;
                $model_projects->left_img = $image_directory . $model_projects->left_img;
                $model_projects->right_img = $image_directory . $model_projects->right_img;

                $data = ['data' => $model_projects, 'backgrounds' => $background_images];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $data;
            }
            return $this->render('project-image-generate', [
                'id' => $id,
                'projects_images_size' => $projectsImagesSize
            ]);
        }
        return $this->redirect('/');
    }

    public function actionTest()
    {
        $project_image_path = '/images/test/Small-mario.png';
        $project_image = $this->getImageBase64($project_image_path);
        $project_image_size = getimagesize($project_image);
        $project_image_width = $project_image_size[0];
        $project_image_height = $project_image_size[1];
        debug($project_image_width);
        debug($project_image_height);

        $background_image_path = '/uploads/background-image/6585461afc0ff84646aebcb0d2158859.jpg';
        $background_image =$this->getImageBase64($background_image_path);
        $background_image_size = getimagesize($background_image);
        $background_image_width = $background_image_size[0];
        $background_image_height = $background_image_size[1];

        if($project_image_width < $background_image_width )
        {
            $type = pathinfo($_SERVER['DOCUMENT_ROOT'].'/web'.$background_image_path, PATHINFO_EXTENSION);

            $jpeg = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'].'/web'.$background_image_path);
            $png = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'].'/web'.$project_image_path);


            list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'].'/web'.$background_image_path);
            list($newwidth, $newheight) = getimagesize($_SERVER['DOCUMENT_ROOT'].'/web'.$project_image_path);
            $out = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($out, $jpeg, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            imagecopyresampled($out, $png, 0, 0, 0, 0, $newwidth, $newheight, $newwidth, $newheight);
//            imagejpeg($out, '/images/test/out.jpg', 100);
            ob_start();
            imagejpeg($out);
            $data = ob_get_contents();
            ob_end_clean();
            $image = 'data:image/jpeg;base64,' . base64_encode($data);
            $fileName = 'test.jpg';
            $save_path = '/web/images/test/';
            $image_name = $this->base64_to_img($image, $fileName, $save_path);
            dd($image_name);
        }

        debug($background_image_width);
        debug($background_image_height);die;



        dd($project_image);
    }

    public function actionSaveImagesAjax()
    {
        $data = Yii::$app->request->post('imgData');
        $project_id = Yii::$app->request->post('projectId');
        $images_size_id = Yii::$app->request->post('imageSizeId');

        $image_name = $this->base64_to_img($data, 'test.png', '/web/test/');
//        list($type, $data) = explode(';', $data);
//        list(, $data) = explode(',', $data);
//        $data = base64_decode($data);
//        $file = md5(microtime());
////fopen($file);
//        file_put_contents('test/' . $file . '.png', $data);
////        die;
        if (!empty($data) && !empty($project_id)) {
            $model_projects_images = ProjectsImagesSize::gatProjectImagesWithSize($project_id);
            $file_name = md5(microtime()) . '.png';
            foreach ($model_projects_images as $projects_image) {
                $images_size = $projects_image->imagesSize;
                $width = $images_size->width;
                $height = $images_size->height;
                $image = $this->changeImageSize($data, $width, $height);
                $save_path = '/web/uploads/images/' . $project_id . '/' . $width . '_' . $height;
                if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $save_path)) {
                    mkdir($_SERVER['DOCUMENT_ROOT'] . $save_path, 0777, true);
                }
                $save_path = $save_path . '/';
                $fileName = $project_id . '_' . $width . '_' . $height . '_' . $file_name;
                $image_name = $this->base64_to_img($image, $fileName, $save_path);
                if (!empty($image_name)) {
                    $model_project_image = new ProjectsImages();
                    $model_project_image->images_size_id = $images_size->id;
                    $model_project_image->project_id = $project_id;
                    $model_project_image->name = $image_name;
                    $model_project_image->save();
                }
            }
        }
    }

    public function actionGetdata()
    {
        $background_categories = ArrayHelper::map(Backgrounds::find()->all(), 'id', 'title');
        return $background_categories;
    }


    private function base64_to_img($image, $fileName, $save_path)
    {
        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $image);

        $extExplode = explode('/', $data[0]);
        $extExplode1 = explode(';', end($extExplode));

        $path = $_SERVER['DOCUMENT_ROOT'] . $save_path . $fileName;

        $ifp = fopen($path, 'wb');
        // open the output file for writing

        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($data[1]));
        // clean up the file resource
        fclose($ifp);
        return $fileName;
    }

    private function getImageBase64($image)
    {
        $path = $image;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents(Yii::$app->urlManager->hostInfo . $path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

    public function changeImageSize($filename, $newwidth, $newheight)
    {

        // Content type
        header('Content-Type: image/png');

        // Get new sizes
        list($width, $height) = getimagesize($filename);

        // Load
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        $source = imagecreatefrompng($filename);

        // Resize
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        // Output
        // Print image
        ob_start();
        imagepng($thumb);
        $data = ob_get_contents();
        ob_end_clean();
        return 'data:image/png;base64,' . base64_encode($data);
    }

    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir) || is_link($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!$this->deleteDirectory($dir . "/" . $item, false)) {
                chmod($dir . "/" . $item, 0777);
                if (!$this->deleteDirectory($dir . "/" . $item, false)) return false;
            };
        }
        return rmdir($dir);
    }


}
