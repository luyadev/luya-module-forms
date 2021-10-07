<p align="center">
  <img src="https://raw.githubusercontent.com/luyadev/luya/master/docs/logo/luya-logo-0.2x.png" alt="LUYA Logo"/>
</p>

# LUYA CMS Forms Builder

[![LUYA](https://img.shields.io/badge/Powered%20by-LUYA-brightgreen.svg)](https://luya.io)
![Tests](https://github.com/luyadev/luya-module-forms/workflows/Tests/badge.svg)
[![Maintainability](https://api.codeclimate.com/v1/badges/41f50ebcd7330406bcc4/maintainability)](https://codeclimate.com/github/luyadev/luya-module-forms/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/41f50ebcd7330406bcc4/test_coverage)](https://codeclimate.com/github/luyadev/luya-module-forms/test_coverage)

A Drag & Drop Forms Builder based on LUYA CMS Blocks.

Available forms module block extensions:

+ [ReCaptcha 3 Forms Block](https://github.com/luyadev/luya-forms-captcha)

## Installation

Install the extension through composer:

```sh
composer require luyadev/luya-module-forms
```

Add the module to the config

```php
'modules' => [
    //...
    'forms' => [
        'class' => 'luya\forms\Module',
    ]
]
```

Run the migrate command which does the database table setup:

```sh
./luya migrate
```

Run the import command in order to setup all the need permissions:

```sh
./luya import
```

## Adjust Mailer Component

In order to customize the mailer component which should be taken for sending the mails, define the Forms component with the given callback.

```php
'components' => [
    //...
    'forms' => [
        'class' => 'luya\forms\Forms',
        'emailMessage' => function (SubmissionEmail $email, Forms $form) {
        
            // your custom mailer integration is here, ensure to return a boolean
            // value whether sending was successfull or not!    
            return \Yii::$app->mailer->compose()
                ->setFrom(...)
                ->setTo($email->getRecipients())
                ->setSubject($email->getSubject())
                ->setTextBody($email->getBodyText())
                ->setHtmlBody($email->getBodyHtml())
                ->send();
        }
    ]
]
```

Maybe the client would like to recieve a custom email, therefore you can extract the attribute value with `$email->submission->getValueByAttribute('email_attribute_in_form')`.

```php
'emailMessage' => function (SubmissionEmail $email, Forms $form) {
    return Yii::$app->mailer->compose()
        ->setTo($email->submission->getValueByAttribute('email')) // receives the value from the user entered data.        
        ....
}
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

## Development

Refresh message files:

```php
./vendor/bin/luya message msgconfig.php 
```
