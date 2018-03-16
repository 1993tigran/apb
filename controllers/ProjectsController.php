<?php

namespace app\controllers;

use app\models\Backgrounds;
use app\models\ProjectsBackgrounds;
use app\models\ProjectsImages;
use Yii;
use app\models\Projects;
use app\models\search\ProjectsSearch;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectsController implements the CRUD actions for Projects model.
 */
class ProjectsController extends CommonController
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$queue = 1);

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

        if ($model->load(Yii::$app->request->post())) {

            $generate = Yii::$app->request->post('generate');
            $queue = Yii::$app->request->post('queue');
            $post_projects = Yii::$app->request->post('Projects');
            $background_ides = Yii::$app->request->post('Projects')['background_ides'];

            if (!empty($post_projects['front_img']) &&
                !empty($post_projects['back_img']) &&
                !empty($post_projects['top_img']) &&
                !empty($post_projects['bottom_img']) &&
                !empty($post_projects['left_img']) &&
                !empty($post_projects['right_img']) &&
                !empty(count($background_ides))
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
                foreach ($images as $key => $image) {
                    $fileName = md5(microtime()) . '.png';
                    $fileName = $this->base64_to_img($image, $fileName, self::PROJECT_IMAGES);
                    $image_names[$key] = $fileName;
                }

                $model->front_img = $image_names['front_img'];
                $model->back_img = $image_names['back_img'];
                $model->top_img = $image_names['top_img'];
                $model->bottom_img = $image_names['bottom_img'];
                $model->left_img = $image_names['left_img'];
                $model->right_img = $image_names['right_img'];
                if(isset($generate)){
                    $model->queue = 1;
                }
                if(isset($queue)){
                    $model->queue = 0;
                }

                if ($model->save()) {
                    foreach ($background_ides as $background_id) {
                        $model_project_back = new ProjectsBackgrounds();
                        $model_project_back->project_id = $model->id;
                        $model_project_back->background_id = $background_id;
                        $model_project_back->save();
                    }
                    if(isset($generate)){
                        return $this->redirect('/project-image-generate/' . $model->id);
                    }
                    if(isset($queue)){
                        return $this->redirect('/queue-list');
                    }
                } else {
                    foreach ($image_names as $image_name) {
                        unlink($_SERVER['DOCUMENT_ROOT'] . self::PROJECT_IMAGES . $image_name);
                    }
                }
            }

        }
        return $this->render('create', [
            'model' => $model,
            'background_categories' => $background_categories,
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

        $selected_project_back = ArrayHelper::map(ProjectsBackgrounds::getProjectsBackgroundsByProjectId($model->id), 'background_id', 'background_id');

        $select_images = [
            'front_img' => $this->getImageBase64(self::PROJECT_IMAGES_DIRECTORY . $model->front_img),
            'back_img' => $this->getImageBase64(self::PROJECT_IMAGES_DIRECTORY . $model->back_img),
            'top_img' => $this->getImageBase64(self::PROJECT_IMAGES_DIRECTORY . $model->top_img),
            'bottom_img' => $this->getImageBase64(self::PROJECT_IMAGES_DIRECTORY . $model->bottom_img),
            'left_img' => $this->getImageBase64(self::PROJECT_IMAGES_DIRECTORY . $model->left_img),
            'right_img' => $this->getImageBase64(self::PROJECT_IMAGES_DIRECTORY . $model->right_img),
        ];
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            $generate = Yii::$app->request->post('generate');
            $queue = Yii::$app->request->post('queue');
            $post_projects = Yii::$app->request->post('Projects');
            $background_ides = Yii::$app->request->post('Projects')['background_ides'];
            if (!empty($post_projects['front_img']) &&
                !empty($post_projects['back_img']) &&
                !empty($post_projects['top_img']) &&
                !empty($post_projects['bottom_img']) &&
                !empty($post_projects['left_img']) &&
                !empty($post_projects['right_img']) &&
                !empty(count($background_ides))
            ) {
                $create_image_path = self::PROJECT_GENERATE_IMAGES . $model->id;
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
                foreach ($old_images as $old_image) {
                    @unlink($_SERVER['DOCUMENT_ROOT'] . self::PROJECT_IMAGES . $old_image);
                }
                foreach ($images as $key => $image) {
                    $fileName = md5(microtime()) . '.png';
                    $fileName = $this->base64_to_img($image, $fileName, self::PROJECT_IMAGES);
                    $image_names[$key] = $fileName;
                }

                $model->front_img = $image_names['front_img'];
                $model->back_img = $image_names['back_img'];
                $model->top_img = $image_names['top_img'];
                $model->bottom_img = $image_names['bottom_img'];
                $model->left_img = $image_names['left_img'];
                $model->right_img = $image_names['right_img'];
                if(isset($generate)){
                    $model->queue = 1;
                }
                if(isset($queue)){
                    $model->queue = 0;
                }
                if ($model->save()) {

                    if (ProjectsBackgrounds::deleteAll(['project_id' => $model->id])) {
                        foreach ($background_ides as $background_id) {
                            $model_project_back = new ProjectsBackgrounds();
                            $model_project_back->project_id = $model->id;
                            $model_project_back->background_id = $background_id;
                            $model_project_back->save();
                        }
                    }

                    if(isset($generate)){
                        return $this->redirect('/project-image-generate/' . $model->id);
                    }
                    if(isset($queue)){
                        return $this->redirect('/queue-list');
                    }

                }
            }

        }
        return $this->render('update', [
            'model' => $model,
            'background_categories' => $background_categories,
            'selected_project_back' => $selected_project_back,
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
            $create_image_path = self::PROJECT_GENERATE_IMAGES . $model->id;
            ProjectsImages::deleteAll(['project_id' => $model->id]);
            $this->deleteDirectory($_SERVER['DOCUMENT_ROOT'] . $create_image_path);
            return true;
        }

        $background_categories = ArrayHelper::map(Backgrounds::find()->all(), 'id', 'title');

        $selected_project_back = ArrayHelper::map(ProjectsBackgrounds::getProjectsBackgroundsByProjectId($model->id), 'background_id', 'background_id');

        $select_images = [
            'front_img' => $this->getImageBase64(self::PROJECT_IMAGES_DIRECTORY . $model->front_img),
            'back_img' => $this->getImageBase64(self::PROJECT_IMAGES_DIRECTORY . $model->back_img),
            'top_img' => $this->getImageBase64(self::PROJECT_IMAGES_DIRECTORY . $model->top_img),
            'bottom_img' => $this->getImageBase64(self::PROJECT_IMAGES_DIRECTORY . $model->bottom_img),
            'left_img' => $this->getImageBase64(self::PROJECT_IMAGES_DIRECTORY . $model->left_img),
            'right_img' => $this->getImageBase64(self::PROJECT_IMAGES_DIRECTORY . $model->right_img),
        ];

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            $generate = Yii::$app->request->post('generate');
            $queue = Yii::$app->request->post('queue');
            $post_projects = Yii::$app->request->post('Projects');
            $background_ides = Yii::$app->request->post('Projects')['background_ides'];

            if (!empty($post_projects['front_img']) &&
                !empty($post_projects['back_img']) &&
                !empty($post_projects['top_img']) &&
                !empty($post_projects['bottom_img']) &&
                !empty($post_projects['left_img']) &&
                !empty($post_projects['right_img']) &&
                !empty(count($background_ides))
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
                foreach ($old_images as $old_image) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . self::PROJECT_IMAGES . $old_image);
                }
                foreach ($images as $key => $image) {
                    $fileName = md5(microtime()) . '.png';
                    $fileName = $this->base64_to_img($image, $fileName, self::PROJECT_IMAGES);
                    $image_names[$key] = $fileName;
                }

                $model->front_img = $image_names['front_img'];
                $model->back_img = $image_names['back_img'];
                $model->top_img = $image_names['top_img'];
                $model->bottom_img = $image_names['bottom_img'];
                $model->left_img = $image_names['left_img'];
                $model->right_img = $image_names['right_img'];
                if(isset($generate)){
                    $model->queue = 1;
                }
                if(isset($queue)){
                    $model->queue = 0;
                }
                if ($model->save()) {

                    if (ProjectsBackgrounds::deleteAll(['project_id' => $model->id])) {
                        foreach ($background_ides as $background_id) {
                            $model_project_back = new ProjectsBackgrounds();
                            $model_project_back->project_id = $model->id;
                            $model_project_back->background_id = $background_id;
                            $model_project_back->save();
                        }
                    }
                    if(isset($generate)){
                        return $this->redirect('/project-image-generate/' . $model->id);
                    }
                    if(isset($queue)){
                        return $this->redirect('/queue-list');
                    }
                }
            }

        }
        return $this->render('edit', [
            'model' => $model,
            'background_categories' => $background_categories,
            'selected_project_back' => $selected_project_back,
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
                $create_image_path = self::PROJECT_GENERATE_IMAGES . $project_id;
                $this->deleteDirectory($_SERVER['DOCUMENT_ROOT'] . $create_image_path);
                foreach ($images as $image) {
                    @unlink($_SERVER['DOCUMENT_ROOT'] . self::PROJECT_IMAGES . $image);
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
        $images_sizes = $this->findBackgroundsSize($id);

        return $this->render('project-images-size-list', [
            'images_sizes' => $images_sizes,
            'project_id' => $id
        ]);
    }

    public function actionProjectImagesList()
    {
        $project_id = Yii::$app->request->get('project_id');
        $size_id = Yii::$app->request->get('size_id');
        $backgrounds_size = Backgrounds::findOne($size_id);
        $model_project_images = ProjectsImages::getProjectsImagesByIdes($project_id, $size_id);

        $countQuery = clone $model_project_images;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        $project_images = $model_project_images->offset($pages->offset)->limit($pages->limit)->all();

        if (Yii::$app->request->isAjax) {
            if (!empty(Yii::$app->request->get('id')) && !empty(Yii::$app->request->get('sizeWidth')) && !empty(Yii::$app->request->get('sizeHeight'))) {
                $id = Yii::$app->request->get('id');
                $sizeWidth = Yii::$app->request->get('sizeWidth');
                $sizeHeight = Yii::$app->request->get('sizeHeight');
                $projectId = Yii::$app->request->get('projectId');
                $model_projects_images = ProjectsImages::findOne($id);
                $image_path = self::PROJECT_GENERATE_IMAGES . $projectId . '/' . $sizeWidth . '_' . $sizeHeight . '/' . $model_projects_images->name;
                if (unlink($_SERVER['DOCUMENT_ROOT'] . $image_path)) {
                    return $model_projects_images->delete();
                }
            }
            return false;
        }
        return $this->render('project-images-list', [
            'project_images' => $project_images,
            'project_id' => $project_id,
            'backgrounds_size' => $backgrounds_size,
            'pages' => $pages
        ]);
    }

    /**
     * Lists all Projects models where queue = 0.
     * @return mixed
     */

    public function actionQueueList()
    {
        $searchModel = new ProjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$queue = 0);

        return $this->render('queue-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
    protected function findBackgroundsSize($id)
    {
        $backgrounds_size = ProjectsBackgrounds::getProjectsBackgroundSize($id);
        if ($backgrounds_size !== null) {
            return $backgrounds_size;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Project Image Generate
     * param project_id
     * @return mixed
     */
    public function actionProjectImageGenerate()
    {
        $this->layout = 'project-generate';
        $id = Yii::$app->request->get('id');
        $model_projects = Projects::findOne($id);
        if (!empty($model_projects)) {
            if (Yii::$app->request->isAjax) {
                $model_backgrounds = ProjectsBackgrounds::getProjectsWithBackgrounds($id);
                $project_max_size = ProjectsBackgrounds::getProjectsBackgroundMaxSize($id);
                $key = 0;
                foreach ($model_backgrounds as $model_background) {
                    foreach ($model_background->projectsBackgrounds as $background) {
                        $background_images[$key] = $this->getImageBase64(self::BACKGROUND_IMAGES_DIRECTORY . $background->image);
                        $key++;
                    }
                }
                $model_projects->front_img = self::PROJECT_IMAGES_DIRECTORY . $model_projects->front_img;
                $model_projects->back_img = self::PROJECT_IMAGES_DIRECTORY . $model_projects->back_img;
                $model_projects->top_img = self::PROJECT_IMAGES_DIRECTORY . $model_projects->top_img;
                $model_projects->bottom_img = self::PROJECT_IMAGES_DIRECTORY . $model_projects->bottom_img;
                $model_projects->left_img = self::PROJECT_IMAGES_DIRECTORY . $model_projects->left_img;
                $model_projects->right_img = self::PROJECT_IMAGES_DIRECTORY . $model_projects->right_img;

                $data = ['data' => $model_projects, 'backgrounds' => $background_images, 'projectMaxSize' => $project_max_size];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $data;
            }
            return $this->render('project-image-generate', [
                'id' => $id,
                'projects_Ides' => 0,
            ]);
        }
        return $this->redirect('/');
    }

    /**
     * Generate All Queue Projects Image
     * param project_id
     * @return mixed
     */
    public function actionGenerateQueueProjects()
    {

        $projects_Ides = ArrayHelper::getColumn(Projects::findAll(['queue' => 0]), 'id');;
        $this->layout = 'project-generate';

        if (!empty($projects_Ides)) {
            if (Yii::$app->request->isAjax) {

                $id = Yii::$app->request->get('id');
                $model_projects = Projects::findOne($id);

                $model_projects->queue = 1;
                $model_projects->background_ides = true;
                $model_projects->save();

                $model_backgrounds = ProjectsBackgrounds::getProjectsWithBackgrounds($id);
                $project_max_size = ProjectsBackgrounds::getProjectsBackgroundMaxSize($id);
                $key = 0;
                foreach ($model_backgrounds as $model_background) {
                    foreach ($model_background->projectsBackgrounds as $background) {
                        $background_images[$key] = $this->getImageBase64(self::BACKGROUND_IMAGES_DIRECTORY . $background->image);
                        $key++;
                    }
                }

                $model_projects->front_img = self::PROJECT_IMAGES_DIRECTORY . $model_projects->front_img;
                $model_projects->back_img = self::PROJECT_IMAGES_DIRECTORY . $model_projects->back_img;
                $model_projects->top_img = self::PROJECT_IMAGES_DIRECTORY . $model_projects->top_img;
                $model_projects->bottom_img = self::PROJECT_IMAGES_DIRECTORY . $model_projects->bottom_img;
                $model_projects->left_img = self::PROJECT_IMAGES_DIRECTORY . $model_projects->left_img;
                $model_projects->right_img = self::PROJECT_IMAGES_DIRECTORY . $model_projects->right_img;

                $data = ['data' => $model_projects, 'backgrounds' => $background_images, 'projectMaxSize' => $project_max_size];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $data;
            }
            $projects_Ides = json_encode($projects_Ides);
            return $this->render('project-image-generate', [
                'projects_Ides' => $projects_Ides,
            ]);
        }
        return $this->redirect('/queue-list');
    }


    /**
     * Ajax call Save Images
     * param imgData, projectId
     * @return boolean
     */
    public function actionSaveImagesAjax()
    {
        $data = Yii::$app->request->post('imgData');
        $project_id = Yii::$app->request->post('projectId');

        if (!empty($data) && !empty($project_id)) {
            $size = getimagesize($data);
            $projects_image_width = $size[0];
            $projects_image_height = $size[1];

            $backgrounds = ProjectsBackgrounds::getBackgroundsWithImages($project_id);

            foreach ($backgrounds as $background) {

                $background_width = $background->background->width;
                $background_height = $background->background->height;

                $save_path = self::PROJECT_GENERATE_IMAGES . $project_id . '/' . $background_width . '_' . $background_height;
                if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $save_path)) {
                    mkdir($_SERVER['DOCUMENT_ROOT'] . $save_path, 0777, true);
                }
                $save_path = $save_path . '/';


                $background_images = $background->projectsBackgrounds;

                foreach ($background_images as $background_image) {
                    $file_name = md5(microtime()) . '.jpg';
                    $fileName = $project_id . '_' . $background_width . '_' . $background_height . '_' . $file_name;
                    $background_image_path = self::BACKGROUND_IMAGES_DIRECTORY . $background_image->image;

                    $project_image_path = $data;

//                   if($projects_image_width < $background_width && $projects_image_height < $background_height){
//
//                   }elseif ($projects_image_width > $background_width && $projects_image_height > $background_height){
//
//                   }elseif ($projects_image_width < $background_width && $projects_image_height > $background_height){
//
//                   }elseif ($projects_image_width > $background_width && $projects_image_height < $background_height){
//
//                   }elseif ($projects_image_width == $background_width && $projects_image_height == $background_height){
//
//                   }

                    if ($this->compareSave($fileName, $save_path, $background_image_path, $project_image_path)) {
                        $model_project_image = new ProjectsImages();
                        $model_project_image->images_size_id = $background->background->id;
                        $model_project_image->project_id = $project_id;
                        $model_project_image->name = $fileName;
                        $model_project_image->save();
                    };
                }
            }
            return true;
        }
    }

//    public function actionGetdata()
//    {
//        $background_categories = ArrayHelper::map(Backgrounds::find()->all(), 'id', 'title');
//        return $background_categories;
//    }


    /**
     * Create and download folder
     * param $project_id
     * return redirect
     */
    public function actionZipping($id)
    {
        if (!empty($id)) {

            // Get real path for our folder
            $rootPath = $_SERVER['DOCUMENT_ROOT'] . self::PROJECT_GENERATE_IMAGES.$id;


            $archive_file_name = $_SERVER['DOCUMENT_ROOT'] . '/web/zip/images.zip';
            // Initialize archive object
            $zip = new \ZipArchive();
            $zip->open($archive_file_name, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            // Create recursive directory iterator
            /** @var SplFileInfo[] $files */
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($rootPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                // Skip directories (they would be added automatically)
                if (!$file->isDir()) {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);

                    // Add current file to archive
                    $zip->addFile($filePath, $relativePath);
                }
            }
            // Zip archive will be created only after closing object
            $zip->close();

            $zip_file_name = 'images.zip';

            header("Content-type: application/zip");
            header("Content-Disposition: attachment; filename=$zip_file_name");
            header("Content-length: " . filesize($archive_file_name));
            header("Pragma: no-cache");
            header("Expires: 0");
            readfile("$archive_file_name");

            return $this->goBack();
        }
    }


    public function actionTest()
    {

    }
}
