# i18n
A set of classes to help with internationalization.
All methods return null if no data are available.

## Language specific methods
```php
use draber\i18n\Common\Factory;

$language = Factory::getLanguage('de');

$language->getLanguageCode();       // returns string 'de'
$language->getLanguageName();       // returns string 'German'
$language->getLanguageNativeName(); // returns string 'Deutsch'
$language->getNumerals();           // returns array [
                                    //             0 => 'null',
                                    //             1 => 'eins',
                                    //             2 => 'zwei',
                                    //             3 => 'drei',
                                    //             4 => 'vier',
                                    //             5 => 'fünf',
                                    //             6 => 'sechs',
                                    //             7 => 'sieben',
                                    //             8 => 'acht',
                                    //             9 => 'neun',
                                    //             10 => 'zehn',
                                    //             11 => 'elf',
                                    //             12 => 'zwölf',
                                    //             13 => 'dreizehn',
                                    //             14 => 'vierzehn',
                                    //             15 => 'fünfzehn',
                                    //             16 => 'sechzehn',
                                    //             17 => 'siebzehn',
                                    //             18 => 'achtzehn',
                                    //             19 => 'neunzehn',
                                    //             20 => 'zwanzig',
                                    //     ]
```

## Country specific methods
```php
use draber\i18n\Common\Factory;

$country = Factory::getCountry('de');

$country->getCountryName();                        // returns string 'Germany'
$country->getCountryCode();                        // returns string 'DE'
$country->getCurrencyCode();                       // returns string 'EUR'
$country->getCurrencyName();                       // returns string 'Euro'
$country->getCurrencySymbol();                     // returns string '€'
$country->getNumberFormatDecimal();                // returns string ','
$country->getNumberFormatGroup();                  // returns string '.'
$country->getNumberFormatList();                   // returns string ';'
$country->getNumberFormatPercentSign();            // returns string '%'
$country->getNumberFormatPlusSign();               // returns string '+'
$country->getNumberFormatMinusSign();              // returns string '-'
$country->getNumberFormatExponential();            // returns string 'E'
$country->getNumberFormatSuperscriptingExponent(); // returns string '·'
$country->getNumberFormatPerMille();               // returns string '‰'
$country->getNumberFormatInfinity();               // returns string '∞'
$country->getNumberFormatNan();                    // returns string 'NaN'
$country->getNumberFormatTimeSeparator();          // returns string ':'
```
