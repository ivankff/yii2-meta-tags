<?php

namespace ivankff\metaTags\models;

use Yii;
use ivankff\metaTags\MetaTags;

/**
 * @property int $model_id
 * @property string $model
 * @property string $title
 * @property string $description
 * @property string $keywords
 */
class MetaTag extends \yii\db\ActiveRecord
{

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return !$this->title && !$this->description && !$this->keywords;
    }

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%meta_tags}}';
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['model', 'model_id'], 'required'],
            [['description', 'title', 'keywords'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => MetaTags::t('messages', 'model_title'),
            'keywords' => MetaTags::t('messages', 'model_keywords'),
            'description' => MetaTags::t('messages', 'model_description'),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior',
                'createdAtAttribute' => false,
                'updatedAtAttribute' => 'time_update',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function formName()
    {
        $suffix = preg_replace('/[^a-zA-Z0-9_]+/u', '', $this->model . $this->model_id);
        return parent::formName() . $suffix;
    }

}
