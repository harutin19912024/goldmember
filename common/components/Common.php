<?php

/**
 * Created by PhpStorm.
 * User: SAMVEL
 * Date: 06.06.2016
 * Time: 14:48
 */

namespace common\components;

use Yii;
use backend\models\Contact;
use yii\base\Component;
use yii\helpers\BaseFileHelper;
use yii\web\NotFoundHttpException;

class Common extends Component {

    /**
     * return image path
     * @param int $id
     * @param boolean $general <p>is iamge general</p>
     * @param boolean $original <p>is image small</p>
     * @return  array image paths
     * */
    public static function getImageProduct($data, $general = true, $original = false) {


        $image = [];
        $base = '/';

        if ($general) {

            $image[] = $base . 'uploads/products/' . $data->id . '/general/small_' . $data->general_image;
        } else {
            $path = \Yii::getAlias("@backend/web/uploads/products/" . $data->id);
            $files = BaseFileHelper::findFiles($path);

            foreach ($files as $file) {
                if (!strstr($file, "general") && !strstr($file, "gallery") && !strstr($file, "special")) {
                    $image[] = $base . 'uploads/products/' . $data->id . '/' . basename($file);
                }
            }
        }

        return $image;
    }

    public static function getImageCategory($data) {

        $path = \Yii::getAlias("@backend/web/uploads/categories/" . $data->id . '/general');

        $image = [];

        $files = BaseFileHelper::findFiles($path);

        if (!empty($files)) {
            $img = explode('web', $files[0]);

            $image[] = str_replace('\\', '/', $img[1]);
        }

        return $image[0];
    }

    public static function getSpecialImage($data) {
        $image = [];
        $base = '/';
        $image[] = $base . 'uploads/products/' . $data->id . '/special/special_' . $data->general_image;

        return $image;
    }

    public static function getPartnersImage($data) {
        $path = \Yii::getAlias("@backend/web/uploads/partners/" . $data->id . '/general');

        $image = [];

        $files = BaseFileHelper::findFiles($path);

        if (!empty($files)) {
            $img = explode('web', $files[0]);
            $image[] = $img[1];
        }
        return $image;
    }

    public static function getCategoriesImage($data) {

        $path = \Yii::getAlias("@backend/web/uploads/categories/" . $data->id . '/general');

        $image = [];

        $files = BaseFileHelper::findFiles($path);

        if (!empty($files)) {
            $img = end((explode('web', $files[0])));
            $image[] = $img;
        }

        return $image;
    }

    public static function getImageGalerry($data, $modified = true, $original = false) {


        $image = [];
        $bigImage = [];
        $base = '/';

        if ($modified) {
            $path = \Yii::getAlias("@backend/web/uploads/products/" . $data->id . '/gallery');
            $files = BaseFileHelper::findFiles($path);

            foreach ($files as $file) {

                if (!strstr($file, "modified")) {
                    $big = explode('web', $file);
                    $bigImage[] = $big[1];
                } else {
                    $img = explode('web', $file);
                    $image[] = $img[1];
                }
            }
        } else {
            $path = \Yii::getAlias("@common/web/uploads/products/" . $data->id . '/gallery');
            $files = BaseFileHelper::findFiles($path);

            foreach ($files as $file) {
                if (!strstr($file, "modified")) {
                    $img = explode('web', $file);
                    $image[] = $img[1];
                }
            }
        }


        return $image;
    }

    public static function getImageSlider($modified = true, $small = false) {
        $image = [];
        $base = '/';

        if ($modified) {
            $path = \Yii::getAlias("@backend/web/uploads/slider/modified");
            $files = BaseFileHelper::findFiles($path);
            foreach ($files as $file) {
                $img = explode('web', $file);
                $repImg = str_replace("\\", "/", $img[1]);
                $image[] = $repImg;
            }
        } else {
            $path = \Yii::getAlias("@backend/web/uploads/slider/small");
            $files = BaseFileHelper::findFiles($path);

            foreach ($files as $file) {
                if (!strstr($file, "modified")) {
                    $img = explode('web', $file);
                    $image[] = $img[1];
                }
            }
        }

        return $image;
    }

    public static function getContactItems($position = 'header', $data) {
        if ($position === 'header') {
            return self::getContact($position, $data);
        } elseif ($position === 'footer') {

            return self::getContact($position, $data);
        } else {
            return false;
        }
    }

    public static function getAboutShortContent() {
        $short = (new \yii\db\Query())
                        ->from("{{%about}}")
                        ->select("{{%about}}.short_content")->one();
        if ($short === null) {
            throw new NotFoundHttpException;
        }

        return $short;
    }

    private static function getContact($position, $data) {
        $contact = (new \yii\db\Query())
                        ->from("{{%contact}}")
                        ->select("{{%contact}}.*")->where([
                    'position' => $position,
                    'name' => $data,
                ])->all();
        if ($contact === null) {
            throw new NotFoundHttpException;
        }
        return $contact;
    }

    public static function clearP($data) {
        echo '<pre>';
        var_dump(strip_tags($data));
        echo '</pre>';
        die;
    }

}
