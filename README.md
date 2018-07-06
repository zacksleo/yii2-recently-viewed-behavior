# yii2-recently-viewed-behavior

[![StyleCI](https://styleci.io/repos/101527643/shield?branch=master)](https://styleci.io/repos/101527643)
[![Build Status](https://travis-ci.org/zacksleo/yii2-recently-viewed-behavior.svg?branch=master)](https://travis-ci.org/zacksleo/yii2-recently-viewed-behavior)
[![Code Climate](https://img.shields.io/codeclimate/github/zacksleo/yii2-recently-viewed-behavior.svg)]()
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/zacksleo/yii2-recently-viewed-behavior/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/zacksleo/yii2-recently-viewed-behavior/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/zacksleo/yii2-recently-viewed-behavior/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/zacksleo/yii2-recently-viewed-behavior/?branch=master)


## Quick Start

### Install

```bash
  composer install zacksleo/yii2-recently-viewed-behavior
```

### Usage

```php

use yii\web\Controller;
use zacksleo\yii2\behaviors\RecentlyViewedBehavior;

class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'recentlyViewed' => [
                'class' => RecentlyViewedBehavior::className(),
                'limit' => 5, // Limit the number of recently viewed items stored. 0 = no limit.
            ],
        ];
    }
    
    
    public function actionView($id)
    {
        // set recently models
        $model = $this->findModel($id);
        $this->setRecentlyViewed(get_class($model), $id);
        // get recently models
        $this->getRecentlyViewed(get_class($model));
    }    

```


