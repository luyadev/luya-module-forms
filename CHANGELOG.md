# CHANGELOG

All notable changes to this project will be documented in this file. This project adheres to [Semantic Versioning](http://semver.org/).
In order to read more about upgrading and BC breaks have a look at the [UPGRADE Document](UPGRADE.md).

## 1.2.1 ()

+ [#14](https://github.com/luyadev/luya-module-forms/issues/14) Fixed a bug where model validation does not work as expected when review step is diabled.

## 1.2.0 (30. December 2020)

+ [#13](https://github.com/luyadev/luya-module-forms/pull/13) Add new helper methods `getValueByAttribute()`, `getBodyHtml()`, `getBodyText()`, `getSummaryText()`.

## 1.1.0 (30. December 2020)

+ [#9](https://github.com/luyadev/luya-module-forms/pull/9/) Fixed bug when review of the form is not required. Removed slugify of attributes because of proposed slugify value is not allowed in attribute (`-`). Changed some labels. Esnure the export uses attribute instead of the label, as the label is not unique. Enabled deleting of submissions.

## 1.0.0 (25. November 2020)

+ First stable release of LUYA Forms Builder Module
