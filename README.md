<p align="center">
  <img src="https://raw.githubusercontent.com/luyadev/luya/master/docs/logo/luya-logo-0.2x.png" alt="LUYA Logo"/>
</p>

# LUYA CMS Form Builder

[![LUYA](https://img.shields.io/badge/Powered%20by-LUYA-brightgreen.svg)](https://luya.io)
![Tests](https://github.com/luyadev/luya-module-forms/workflows/Tests/badge.svg)

Generate forms with LUYA CMS Blocks

## Installation

Install the extension through composer:

```sh
composer require luyadev/luya-module-forms
```

Add the module to the config

```
'modules' => [
  'forms' => [
    'class' => 'luya\forms\Module',
  ]
]
```

Run the migrate command

```sh
./luya migrate
```

Run the import command afterwards:

```sh
./luya import
```

## Create Custom Form Field Blocks

The default blocks may not suit your needs, therefore its possible to create your own from input block:

```php
class MyDropDownBlock extends PhpBlock
{
    use FieldBlockTrait;
    
    public function name()
    {
        return 'Dropdown';
    }

    public function admin()
    {
        return '<p>My Dropdown {{vars.label}}</p>';
    }

    public function frontend()
    {
        Yii::$app->forms->autoConfigureAttribute(
            $this->getVarValue($this->varAttribute),
            $this->getVarValue($this->varRule, $this->defaultRule), 
            $this->getVarValue($this->varIsRequired),
            $this->getVarValue($this->varLabel),
            $this->getVarValue($this->varHint)
        );

        // Use all possible options with ActiveField or use the HtmlHelper
        return Yii::$app->forms->form->field(Yii::$app->forms->model, $this->getVarValue($this->varAttribute))
            ->dropDownList([1 => 'Foo', 2 => 'Bar']);
    }
}
```

## Captcha Block

An example of a Captcha code block utilizing the [himiklab recaptcha library](https://github.com/himiklab/yii2-recaptcha-widget)

```php
<?php

use Yii;
use himiklab\yii2\recaptcha\ReCaptchaValidator3;
use luya\cms\base\PhpBlock;
use luya\forms\FieldBlockTrait;

class RecaptchaBlock extends PhpBlock
{
    use FieldBlockTrait;
    
    public function name()
    {
        return 'Form Captcha';
    }

    public function admin()
    {
        return '<p>Captcha</p>';
    }

    public function icon()
    {
        return 'android';
    }

    public function frontend()
    {
        Yii::$app->forms->autoConfigureAttribute(
            $this->getVarValue($this->varAttribute),
            ReCaptchaValidator3::class, 
            true,
            false
        );

        // this ensures the value is hidden in confirm and save actions, and also ensure the attribute
        // value does not need to be validate again after the preview.
        Yii::$app->forms->model->invisibleAttribute($this->getVarValue($this->varAttribute));

        return Yii::$app->forms->form->field(Yii::$app->forms->model, $this->getVarValue($this->varAttribute))
            ->widget('himiklab\yii2\recaptcha\ReCaptcha3')
            ->label(false);
    }
}
```

## Development

Refresh message files:

```php
./vendor/bin/luya message msgconfig.php 
```
