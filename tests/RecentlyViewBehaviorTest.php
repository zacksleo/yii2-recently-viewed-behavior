<?php

namespace tests;

use yii;
use tests\data\models\Item;
use zacksleo\yii2\behaviors\RecentlyViewedBehavior;

class RecentlyViewBehaviorTest extends TestCase
{
    public function testBehavior()
    {
        $model = Item::findOne(1);
        $behavior = new RecentlyViewedBehavior();
        $behavior->limit = 5;
        $behavior->setRecentlyViewed(get_class($model), $model->id);
        $behavior->setRecentlyViewed(get_class($model), $model->id);
        $models = $behavior->getRecentlyViewed(get_class($model));
        $this->assertInstanceOf('tests\data\models\Item', $models[0]);
        $this->assertEquals(1, count($models));
        $behavior->clearRecentlyViewed(get_class($model));
        $empty = $behavior->getRecentlyViewed(get_class($model));
        $this->assertEmpty($empty);
    }

    public function testLimit()
    {
        Yii::$app->session->removeAll();
        $behavior = new RecentlyViewedBehavior();
        $behavior->limit = 2;
        $model = new Item();
        $behavior->setRecentlyViewed(get_class($model), 1);
        $behavior->setRecentlyViewed(get_class($model), 2);
        $behavior->setRecentlyViewed(get_class($model), 3);
        $models = $behavior->getRecentlyViewed(get_class($model));
        $this->assertEquals($behavior->limit, count($models));
    }
}
