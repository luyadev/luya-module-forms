<?php

namespace luya\forms\tests;

use luya\forms\Model;
use yii\base\DynamicModel;

class ModelTest extends BaseTestCase
{
    public function testFormatAttributeValue()
    {
        $model = new Model([
            'foo' => 'bar',
            'baz' => 'test@luya.io'
        ]);
        $model->formatters['baz'] = 'email';

        $this->assertSame('<a href="mailto:test@luya.io">test@luya.io</a>', $model->formatAttributeValue('baz', 'test@luya.io'));
        $this->assertSame('bar', $model->formatAttributeValue('bar', 'bar'));
    }

    public function testInvisibleSetterGetter()
    {
        $model = new Model([
            'foo' => '', 'baz' => '', 'foobar' => '',
        ]);
        $model->invisibleAttribute('foobar');

        $this->assertTrue($model->isAttributeInvisible('foobar'));

        $this->assertSame(['foo', 'baz'], $model->getAttributesWithoutInvisible());
    }
}
