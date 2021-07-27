# CHANGELOG

All notable changes to this project will be documented in this file. This project adheres to [Semantic Versioning](http://semver.org/).
In order to read more about upgrading and BC breaks have a look at the [UPGRADE Document](UPGRADE.md).

## 1.4.4 (27. July 2021)

+ Enable compatibility for CMS and Admin 4.0

## 1.4.3 (4. March 2021)

+ [#22](https://github.com/luyadev/luya-module-forms/pull/22) Make labels in email submission summary bold for better readability. 

## 1.4.2 (4. March 2021)

+ [#21](https://github.com/luyadev/luya-module-forms/pull/21) Fixed a bug where the export where wrong sorted if a new field has been added in between existing columns.
+ [#20](https://github.com/luyadev/luya-module-forms/pull/20) Fixed a bug where multiple validations throws an error with storage file validators.

## 1.4.1 (15. January 2021)

+ Fixed bug with radio list separator.
 
## 1.4.0 (12. January 2021)

+ [#19](https://github.com/luyadev/luya-module-forms/pull/19) Added new option to define the `separator` value when using radio buttons as select.

## 1.3.1 (7. January 2021)

+ [#18](https://github.com/luyadev/luya-module-forms/pull/18) Added a Polyfill in order to ensure the Datepicker works on IE and Safari.

## 1.3.0 (7. January 2021)

+ [#16](https://github.com/luyadev/luya-module-forms/issues/16) Fix formatting of values in email.
+ [#14](https://github.com/luyadev/luya-module-forms/issues/14) Fixed a bug where model validation does not work as expected when review step is diabled.

## 1.2.0 (30. December 2020)

+ [#13](https://github.com/luyadev/luya-module-forms/pull/13) Add new helper methods `getValueByAttribute()`, `getBodyHtml()`, `getBodyText()`, `getSummaryText()`.

## 1.1.0 (30. December 2020)

+ [#9](https://github.com/luyadev/luya-module-forms/pull/9/) Fixed bug when review of the form is not required. Removed slugify of attributes because of proposed slugify value is not allowed in attribute (`-`). Changed some labels. Esnure the export uses attribute instead of the label, as the label is not unique. Enabled deleting of submissions.

## 1.0.0 (25. November 2020)

+ First stable release of LUYA Forms Builder Module
