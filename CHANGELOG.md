# CHANGELOG

All notable changes to this project will be documented in this file. This project adheres to [Semantic Versioning](http://semver.org/).
In order to read more about upgrading and BC breaks have a look at the [UPGRADE Document](UPGRADE.md).

## 1.8.1 (31. October 2023)

+ [#32](https://github.com/luyadev/luya-module-forms/pull/32) Added bahasa indonesia language

## 1.8.0 (9. August 2023)

+ Apply the `nl2br()` function on the intro and outro text within the default email compose function. This ensures that all newlines are transformed into `<br />` tags, unless you choose to override the default behavior by implementing your own custom logic using the `'emailMessage' => function(SubmissionEmail $email, Forms $forms) {}` function.

## 1.7.4 (9. August 2023)

+ Add empty attribute name check to SelectBlock.

## 1.7.3 (9. August 2023)

+ Enhance the handling of empty attribute name values to ensure that the input is hidden gracefully instead of triggering an error when no attribute name is provided. Additionally, empty arrays for select and checkbox inputs no longer result in exceptions. Instead, an empty array is now the default, ensuring that nothing is displayed and the process proceeds smoothly.

## 1.7.2 (5. April 2023)

+ [#31](https://github.com/luyadev/luya-module-forms/pull/31) Added PT Translations
+ Added tests for PHP 8.2
+ Fixed issue where value was not an array.

## 1.7.1 (5. October 2022)

+ [#30](https://github.com/luyadev/luya-module-forms/pull/30) Added option to delete forms including all values and submissions.

## 1.7.0 (1. September 2022)

+ [#29](https://github.com/luyadev/luya-module-forms/pull/29) Added option to retrieve certain value in submission email object.

## 1.6.0 (24. August 2022)

+ [#28](https://github.com/luyadev/luya-module-forms/pull/28) New `afterSave` event on `Forms` component.

## 1.5.1 (2. June 2022)

+ Fixed issue with latest Yii Framework Dynamic Model
+ Added Tests for PHP 8.1

## 1.5.0 (10. August 2021)

+ [#24](https://github.com/luyadev/luya-module-forms/issues/24) If no recipients are provided, no emails will be triggered. This allows you to build forms with just saving the data into the database.
+ [#23](https://github.com/luyadev/luya-module-forms/issues/23) Added option to define hidden input fields with a preset value.

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
