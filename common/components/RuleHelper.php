<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 14.11.2016
 * Time: 10:47
 */

namespace common\components;


class RuleHelper
{
    private static $file = "routes.json";
    private static $path;
    private static $content;
    private static $pattern;
    private static $defaults;

    /**
     * @return string
     */
    public static function getFile()
    {
        return self::$file;
    }

    /**
     * @param string $file
     */
    public static function setFile($file)
    {
        self::$file = $file;
    }

    /**
     * @return mixed
     */
    public static function getPath()
    {
        return self::$path;
    }

    /**
     * @param mixed $path
     */
    public static function setPath($path)
    {
        self::$path = $path;
    }

    /**
     * @return mixed
     */
    public static function getContent()
    {
        return self::$content;
    }

    /**
     * @param mixed $content
     */
    public static function setContent($content)
    {
        self::$content = $content;
    }

    public static function generatePattern($route_name, $id)
    {

        if (strpos($route_name, '/')) {
            $patternArray = explode('/', $route_name);
            $rout_name = $patternArray[0];
            unset($patternArray[0]);
            $tags = "/";
            foreach ($patternArray as $key => $value) {
                $tags = $tags . "<tag" . $key . ":" . $value . ">/";
            }

            self::$pattern = "" . $rout_name . "" . rtrim($tags, "/");
        } else {
            self::$pattern = $route_name;
        }
        self::$defaults = ['id' => $id];
        return true;
    }

    /**
     * @param $rout_name
     * @param $url
     * @param $id
     */
    public static function makeRule($route_name, $url, $id)
    {
        self::generatePattern($route_name, $id);
        self::$content = [
            $id => [
                'pattern' => self::$pattern,
                'route' => $url,
                'defaults' => self::$defaults
            ]
        ];
        self::saveToFie();
    }

    /**
     * @param $rout_name
     * @param $url
     * @param $id
     * @param $oldRoute_name
     * @param null $old_url
     * @param null $old_id
     * @return bool
     */
    public static function updateRule($rout_name, $url, $id, $oldRoute_name,  $old_id = null)
    {

        $idToBeUpdated = (!$old_id) ? $id : $old_id;
        if (isset($oldRoute_name) && $oldRoute_name != "" && $oldRoute_name) {
            self::deleteRule($idToBeUpdated);
        }
        self::generatePattern($rout_name, $id);
        self::$content = [
            $id => [
                'pattern' => self::$pattern,
                'route' => $url,
                'defaults' => self::$defaults
            ]
        ];
        self::saveToFie();
        return true;
    }

    public static function deleteRule($id)
    {
        $filePath = self::$path . '/' . self::$file;
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);

            $array = json_decode($content, true);
            unset($array[$id]);
            $content1 = json_encode($array);
            file_put_contents($filePath, $content1);
            return true;
        }
        return false;
    }

    private static function saveToFie()
    {
        $filePath = self::$path . '/' . self::$file;
        if (!file_exists($filePath)) {
            $content = self::$content;
            $fp = fopen($filePath, "w+");
            fwrite($fp, json_encode($content));
            fclose($fp);
            return true;
        } else {
            $content = file_get_contents($filePath);
            $array = json_decode($content, true);
            $id = array_keys(self::$content)[0];
            $array[$id]= self::$content[$id];
            $content1 = json_encode($array);
            file_put_contents($filePath, $content1);
            return true;
        }

    }


}