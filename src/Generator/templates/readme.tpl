# i18n
A set of classes to help with internationalization.
All methods return null if no data are available.

## Language specific methods
```php
use draber\i18n\Common\Factory;

$language = Factory::getLanguage('de');

{LANGUAGE_CLASS_CODE}
```

## Country specific methods
```php
use draber\i18n\Common\Factory;

$country = Factory::getCountry('de');

{COUNTRY_CLASS_CODE}
```
