<?php

namespace tests\models;

use yii\db\ActiveRecord;

/**
 * Class Product
 * @package tests\models
 */
class Product extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'price' => 'Price',
        ];
    }
}
