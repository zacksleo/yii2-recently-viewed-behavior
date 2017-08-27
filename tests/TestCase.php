<?php

namespace tests;

use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * This is the base class for all yii framework unit tests.
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();

        $this->setupTestDbData();

        $this->createRuntimeFolder();
    }

    protected function tearDown()
    {
        $this->destroyApplication();
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     *
     * @param array $config The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\yii\web\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => $this->getVendorPath(),
            'controllerNamespace' => 'tests\data\controllers',
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'mysql:host=localhost;dbname=test',
                    'username' => 'root',
                ],
                'request' => [
                    'hostInfo' => 'http://domain.com',
                    'scriptUrl' => 'index.php',
                ],
                'session' => [
                    'class' => 'yii\web\DbSession',
                    'cookieParams' => [
                        'domain' => '.domain.com',
                        'httpOnly' => false,
                    ],
                ],
            ],
        ], $config));
    }

    /**
     * @return string vendor path
     */
    protected function getVendorPath()
    {
        return dirname(__DIR__) . '/vendor';
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
    }

    /**
     * Setup tables for test ActiveRecord
     */
    protected function setupTestDbData()
    {
        $db = Yii::$app->getDb();

        // Structure :
        try {
            $db->createCommand()->createTable('item', [
                'id' => 'pk',
                'item_name' => 'string(125) not null',
                'subtitle' => 'string(125) not null',
                'categories' => 'string not null',
                'market_price' => 'decimal(10,2) not null default 0',
                'price' => 'integer not null default 0',
                'description' => 'text not null',
                'logo_image' => 'string not null',
                'status' => 'boolean not null default 1',
                'created_at' => 'integer not null',
                'updated_at' => 'integer not null',
            ])->execute();
            $db->createCommand()->createTable('session', [
                'id' => 'pk',
                'expire' => 'integer',
                'data' => 'binary',
            ])->execute();
            // Data :
            $db->createCommand()->batchInsert('item', [
                'id',
                'item_name',
                'subtitle',
                'categories',
                'description',
                'logo_image',
                'created_at',
                'updated_at'
            ], [
                [
                    'id' => 1,
                    'item_name' => 'goods-1',
                    'subtitle' => 'goods',
                    'categories' => '1,2,3',
                    'description' => 'goods',
                    'logo_image' => 'logo.png',
                    'created_at' => time(),
                    'updated_at' => time(),
                ],
                [
                    'id' => 2,
                    'item_name' => 'goods-2',
                    'subtitle' => 'goods',
                    'categories' => '1,2,3',
                    'description' => 'goods',
                    'logo_image' => 'logo.png',
                    'created_at' => time(),
                    'updated_at' => time(),
                ],
                [
                    'id' => 3,
                    'item_name' => 'goods-3',
                    'subtitle' => 'goods',
                    'categories' => '1,2,3',
                    'description' => 'goods',
                    'logo_image' => 'logo.png',
                    'created_at' => time(),
                    'updated_at' => time(),
                ],
            ])->execute();
        } catch (Exception $e) {

        }
    }

    /**
     * Create runtime folder
     */
    protected function createRuntimeFolder()
    {
        FileHelper::createDirectory(dirname(__DIR__) . '/tests/runtime');
    }
}
