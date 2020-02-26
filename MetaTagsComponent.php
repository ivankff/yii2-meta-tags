<?php

namespace ivankff\metaTags;

use Yii;
use yii\db\ActiveRecord;

/**
 */
class MetaTagsComponent extends \yii\base\Component
{

    public static $behaviorName = 'MetaTag';

    public $generateCsrf = true;
    public $generateOg = true;

    /**
     * @param ActiveRecord $model
     */
    public function register($model = null)
    {
        if ($this->generateCsrf && Yii::$app->request->enableCsrfValidation) {
            Yii::$app->view->registerMetaTag(['name' => 'csrf-param', 'content' => Yii::$app->request->csrfParam], 'csrf-param');
            Yii::$app->view->registerMetaTag(['name' => 'csrf-token', 'content' => Yii::$app->request->csrfToken], 'csrf-token');
        }

        if ($model instanceof ActiveRecord) {
            $behavior = null;

            foreach ($model->behaviors() as $b)
                if ($b instanceof MetaTagBehavior)
                    $behavior = $b;

            if ($behavior) {
                Yii::$app->view->registerMetaTag(['name' => 'title', 'content' => $behavior->getMetaTagTitle()], 'title');
                Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => $behavior->getMetaTagKeywords()], 'keywords');
                Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $behavior->getMetaTagDescription()], 'description');

                if ($this->generateOg) {
                    Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => $behavior->getMetaTagTitle()], 'og:title');
                    Yii::$app->view->registerMetaTag(['property' => 'og:description', 'content' => $behavior->getMetaTagDescription()], 'og:description');
                    Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => \yii\helpers\Url::to('', true)], 'og:url');
                }
            }
        }
    }

}
