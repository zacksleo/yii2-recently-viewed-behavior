<?php

namespace tests;

use tests\data\models\Item;
use zacksleo\yii2\behaviors\RecentlyViewedBehavior;

class RecentlyViewBehaviorTest extends TestCase
{
    public function testBehavior()
    {
        \Yii::$app->getSession()->removeAll();
        $_SESSION = [];
        $model = Item::findOne(1);
        $behavior = new RecentlyViewedBehavior();
        $behavior->limit = 5;
        $behavior->setRecentlyViewed(get_class($model), $model->id);
        $models = $behavior->getRecentlyViewed(get_class($model));
        $this->assertInstanceOf('tests\data\models\Item', $models[0]);
        var_dump($models);
        $this->assertEquals(1, count($models));
    }
}
