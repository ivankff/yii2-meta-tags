<?php

namespace ivankff\metaTags;

use Yii;
use yii\base\Widget;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\widgets\ActiveForm;

/**
 */
class MetaTags extends Widget
{

    /** @var ActiveRecord */
    public $model;
    /** @var ActiveForm */
    public $form;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        static::registerTranslations();

        if (! $this->_getBehavior())
            throw new Exception(static::t('messages', 'widget_behavior_exception {behaviorName}', ['behaviorName' => MetaTagsComponent::$behaviorName]), 500);
    }

    /**
     * {@inheritDoc}
     */
    public function run()
    {
        return $this->render('MetaTags', [
            'model' => $this->_getBehavior()->getMetaTagModel(),
            'form' => $this->form,
        ]);
    }

    /**
     * @return MetaTagBehavior|null
     */
    private function _getBehavior()
    {
        foreach ($this->model->getBehaviors() as $b)
            if ($b instanceof MetaTagBehavior)
                return $b;

        return null;
    }

    /**
     */
    public static function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['metaTags/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'sys',
            'basePath' => '@vendor/ivankff/yii2-meta-tags/messages',
            'fileMap' => [
                'metaTags/messages' => 'messages.php',
            ],
        ];
    }

    /**
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        static::registerTranslations();
        return Yii::t('metaTags/' . $category, $message, $params, $language);
    }

}
