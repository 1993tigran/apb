<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;


class CommonController extends Controller
{
    const PROJECT_GENERATE_IMAGES = '/web/uploads/images/';
    const PROJECT_IMAGES = '/web/uploads/projects_image/';
    const BACKGROUND_IMAGES_DIRECTORY = '/uploads/background-image/';
    const PROJECT_IMAGES_DIRECTORY = '/uploads/projects_image/';
    const BACKGROUND_IMAGES = '/web/uploads/background-image/';


    /**
     * Resizes the image with new width height.
     * @param $image
     * @param $fileName
     * @param $save_path
     * @param $newwidth
     * @param $newheight
     * @return bool
     */
    protected function changeImageSize($image, $fileName, $save_path, $newwidth, $newheight)
    {
        // Content type
        header('Content-Type: image/jpg');

        // Get new sizes
        list($width, $height) = getimagesize($image);

        // Load
        $thumb = imagecreatetruecolor($newwidth, $newheight);
//        imagealphablending($thumb, false);
//        imagesavealpha($thumb, true);
        $source = imagecreatefrompng($image);

        // Resize
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        // Output
        // Print image
        $path = $_SERVER['DOCUMENT_ROOT'] . $save_path . $fileName;

        imagejpeg($thumb, $path);

        return imagedestroy($thumb);

    }

    /**
     * Base64 code To Image file.
     * @param $image
     * @param $fileName
     * @param $save_path
     * @return mixed
     */
    protected function base64_to_img($image, $fileName, $save_path)
    {

        $type = pathinfo($fileName, PATHINFO_EXTENSION);

        $path = $_SERVER['DOCUMENT_ROOT'] . $save_path . $fileName;
        if ($type == 'png') {
            $resource = imagecreatefrompng($image);
            imagepng($resource, $path, 9);
            imagedestroy($resource);
        } else {
            $resource = imagecreatefromjpeg($image);
            imagejpeg($resource, $path, 100);
            imagedestroy($resource);
        }


        return $fileName;
    }

    /**
     * Image file To Base64 code.
     * @param $image
     * @return string
     */
    protected function getImageBase64($image)
    {
        $path = $image;
        $type = pathinfo($path, PATHINFO_EXTENSION);

        $data = @file_get_contents(Yii::$app->urlManager->hostInfo . $path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

    /**
     * Delete the Directory
     * @param $dir
     * @return bool
     */
    protected function deleteDirectory($dir)
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


    /**
     * Compare 2 images, join and save
     * @param $fileName
     * @param $save_path
     * @param $background_image_path
     * @param $project_image_path
     * @return bool
     */
    protected function compareSave($fileName, $save_path, $background_image_path, $project_image_path)
    {

        $jpeg = @imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'] . '/web' . $background_image_path);
        $png = @imagecreatefrompng($project_image_path);

        if (!$jpeg || !$png) {
            return false;
        }
        list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/web' . $background_image_path);
        // Get dimensions of source image.
        list($origWidth, $origHeight) = getimagesize($project_image_path);

        $maxWidth = $width;
        $maxHeight = $height;

        // Calculate ratio of desired maximum sizes and original sizes.
        $widthRatio = $origHeight * $maxWidth / $maxHeight;
        $heightRatio = $origWidth * $maxHeight / $maxWidth;

        $out = imagecreatetruecolor($maxWidth, $maxHeight);
        imagecopyresampled($out, $jpeg, 0, 0, 0, 0, $width, $height, $width, $height);
        if ($widthRatio > $origWidth) {
            //cut point by height
            $h_point = (($origHeight - $heightRatio) / 2);
            //copy image
            imagecopyresampled($out, $png, 0, 0, 0, $h_point, $width, $height, $origWidth, $heightRatio);
        } else {
            //cut point by width
            $w_point = (($origWidth - $widthRatio) / 2);
            imagecopyresampled($out, $png, 0, 0, $w_point, 0, $width, $height, $widthRatio, $origHeight);
        }

        $path = $_SERVER['DOCUMENT_ROOT'] . $save_path . $fileName;
         imagejpeg($out, $path, 100);
        return imagedestroy($out);

    }
}