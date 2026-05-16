<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "material".
 *
 * @property int $id
 * @property string $name
 * @property string|null $short_description
 * @property string|null $description
 * @property string|null $path
 */
class Material extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'material';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['name', 'short_description', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'short_description' => Yii::t('app', 'Short Description'),
            'description' => Yii::t('app', 'Description'),
            'path' => Yii::t('app', 'Path'),
        ];
    }
    
    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['material_id' => 'id']);
    }

    /**
     * Gets query for [[TrMaterials]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrMaterials()
    {
        return $this->hasMany(TrMaterial::class, ['material_id' => 'id']);
    }
    
    
    public function updateTranslation()
    {
        $tr = TrMaterial::findOne(['language_id' => 2, 'material_id' => $this->id]);

        if (!$tr) {
            $tr = new TrMaterial();
            $tr->setAttribute('language_id', 2);
            $tr->setAttribute('material_id', $this->id);
        }
        $tr->setAttribute('name', $this->name);
        $tr->setAttribute('short_description', $this->short_description);
        $tr->setAttribute('description', $this->description);
        $tr->save();

        return true;
    }
}
