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
  'forms' => 'luya\forms\Module'
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
