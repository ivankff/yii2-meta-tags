<?php

namespace ivankff\metaTags;

use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\ModelEvent;
use yii\db\ActiveRecord;

use ivankff\metaTags\models\MetaTag;
use yii\db\AfterSaveEvent;
use yii\web\Request;

/**
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'MetaTag' => [
 *             'class' => 'ivankff\metaTags\MetaTagBehavior',
 *             'primaryKey' => function($owner, $behavior) {
 *                  return md5(implode($owner->primaryKey, '_'));
 *              },
 *             'modelName' => get_class($this),
 *         ],
 *     ];
 * }
 * ```
 *
 * @property ActiveRecord $owner
 */
class MetaTagBehavior extends Behavior
{

    /**
     * @var callable|null an anonymous function returning the value. The anonymous function signature should be:
     * `function($owner, $behavior)`
     * it is useful when primary key is array
     * if `null` then `$this->owner->primaryKey` will be used
     */
    public $primaryKey;
    /**
     * @var string|null model name to save in `model` attribute
     * `(new \ReflectionClass($this->owner))->getShortName()` is used by default
     */
    public $modelName;

    /** @var MetaTag */
    private $_model;
    /** @var bool */
    private $_needToSave = false;

    /**
     * {@inheritDoc}
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * @param ModelEvent $event
     */
    public function beforeSave($event)
    {
        $request = Yii::$app->get('request');

        if ($request instanceof Request) {
            $model = $this->getMetaTagModel();

            if ($model->load($request->post()))
                $this->_needToSave = true;
        }
    }

    /**
     * @param AfterSaveEvent $event
     */
    public function afterSave($event)
    {
        if ($this->_needToSave) {
            $model = $this->getMetaTagModel();
            $model->model_id = $this->_getModelId();
            $model->model = $this->_getModelName();

            if (! $model->isNewRecord && $model->isEmpty()) {
                $model->delete();
            } elseif (! $model->isEmpty()) {
                $model->save();
            }

            $this->_needToSave = false;
        }
    }

    /**
     * @param Event $event
     */
    public function afterDelete($event)
    {
        MetaTag::deleteAll([
            'model_id' => $this->_getModelId(),
            'model'  => $this->_getModelName(),
        ]);
    }

    /**
     * @return MetaTag|null
     */
    public function getMetaTagModel()
    {
        if (null === $this->_model) {
            $modelId = $this->_getModelId();
            $modelName = $this->_getModelName();

            $this->_model = MetaTag::findOne([
                'model_id' => $modelId,
                'model'  => $modelName,
            ]);

            if (! $this->_model) {
                $this->_model = new MetaTag();
                $this->_model->model_id = $this->_getModelId();
                $this->_model->model = $modelName;
            }
        }

        return $this->_model;
    }

    /**
     * @return string
     */
    public function getMetaTagTitle()
    {
        $model = $this->getMetaTagModel();
        return $model ? $model->title : '';
    }

    /**
     * @return string
     */
    public function getMetaTagDescription()
    {
        $model = $this->getMetaTagModel();
        return $model ? $model->description : '';
    }

    /**
     * @return string
     */
    public function getMetaTagKeywords()
    {
        $model = $this->getMetaTagModel();
        return $model ? $model->keywords : '';
    }

    /**
     * @return mixed
     */
    private function _getModelId()
    {
        if (is_callable($this->primaryKey))
            return call_user_func($this->primaryKey, $this->owner, $this);

        return $this->owner->primaryKey;
    }

    /**
     * @return string
     */
    private function _getModelName()
    {
        return $this->modelName ?: (new \ReflectionClass($this->owner))->getShortName();
    }

}
