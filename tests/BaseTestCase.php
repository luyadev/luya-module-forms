<?php
namespace luya\forms\tests;

use luya\forms\Forms;
use luya\helpers\FileHelper;
use luya\testsuite\cases\WebApplicationTestCase;
use luya\testsuite\components\DummySession;
use luya\testsuite\traits\CmsDatabaseTableTrait;
use yii\web\AssetManager;

abstract class BaseTestCase extends WebApplicationTestCase
{
    use CmsDatabaseTableTrait;
    
    public function getConfigArray()
    {
        return [
            'id' => 'forms',
            'basePath' => __DIR__,
            'language' => 'en',
            'modules' => [
                'forms' => [
                    'class' => 'luya\forms\Module',
                ],
                'admin' => [
                    'class' => 'luya\admin\Module',
                ]
            ],
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'sqlite::memory:',
                ],
                'forms' => [
                    'class' => Forms::class,
                    'activeFormClassOptions' => [
                        'enableClientScript' => false,
                    ]
                ],
                'session' => [
                    'class' => DummySession::class,
                ],
                'assetManager' => [
                    'class' => AssetManager::class,
                    'basePath' => dirname(__FILE__) . '/assets',
                    'bundles' => [
                        'yii\web\JqueryAsset' => false,
                        'luya\bootstrap4\Bootstrap4Asset' => false,
                        'yii\widgets\ActiveFormAsset' => false,
                        'yii\web\YiiAsset' => false,
                    ],
                ]
            ]
        ];
    }

    public function beforeSetup()
    {
        parent::beforeSetup();
        FileHelper::createDirectory(dirname(__FILE__) . '/assets', 0777);
    }
}
