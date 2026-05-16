<?php
/**
 * Created by PhpStorm.
 * User: SAMVEL
 * Date: 8/16/2016
 * Time: 20:33
 */

namespace app\components;
use yii\web\Request;

class LanguageHttpRequest extends Request
{
    private $_requestUri;

    public function resolveRequestUri()
    {


        if ($this->_requestUri === null)
            $this->_requestUri = MultiLangHelper::processLangInUrl(parent::resolveRequestUri());

        return $this->_requestUri;
    }

    public function getOriginalUrl()
    {
        return $this->getOriginalRequestUri();
    }

    public function getOriginalRequestUri()
    {
		
        return MultiLangHelper::addLangToUrl($this->getUrl());
    }
}