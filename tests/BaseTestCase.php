<?php
namespace luya\forms\tests;

use luya\testsuite\cases\WebApplicationTestCase;
use luya\testsuite\components\DummySession;
use yii\web\AssetManager;

class BaseTestCase extends WebApplicationTestCase
{
    public function getConfigArray()
    {
        return [
            'id' => 'forms',
            'basePath' => __DIR__,
            'language' => 'en',
            'modules' => [
                'forms' => [
                    'class' => 'luya\forms\Module',
                ]
                ],
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'sqlite::memory:',
                ],
                'session' => [
                    'class' => DummySession::class,
                ],
                'assetManager' => [
                    'class' => AssetManager::class,
                    'basePath' => dirname(__FILE__ ) . '/assets',
                    'bundles' => [
                        'yii\web\JqueryAsset' => false,
                        'luya\bootstrap4\Bootstrap4Asset' => false,
                    ],
                ]
            ]
        ];
    }
}
