<?php
/**
 * MIT License
 *
 * Copyright (c) 2017 Dieter Raber
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/autoload.php';

use draber\i18n\Generator\JsonGenerator;
use draber\i18n\Generator\ClassGenerator;
use draber\i18n\Generator\CsvGenerator;

$jsonGen  = new JsonGenerator();
$csvGen   = new CsvGenerator();
$classGen = new ClassGenerator();

// Examples with CSV import
// $jsonGen->buildDataFromAnything('../../resources/data/numerals.csv')->view();
//$jsonGen->buildDataFromCsv(
//   '../../resources/data/number-formats.csv',
//   [
//       'Number Formats',
//   ],
//   [
//       JsonGenerator::CONFIG_CSV_FIRST_LINE_KEYS => true
//   ]
//)->view()->save('./data/countries/number-formats.json');

// Examples with JSON import
// $jsonGen->buildDataFromAnything('./data/languages/numerals.json', [JsonGenerator::CONFIG_GEN_KEEP_ARRAY => true])->view();
// $jsonGen->buildDataFromJson('./data/languages/numerals.json', ['Numerals from 0-20', date('Y:m:d H:i:s')], [date('Y:m:d H:i:s')])->view();

// Save as JSON (optionally with view)
//$jsonGen->buildDataFromCsv(
//    '../../resources/data/numerals.csv',
//    [
//        'Numerals from 0-20',
//        date('Y:m:d H:i:s')
//    ],
//    [
//        JsonGenerator::CONFIG_GEN_KEEP_ARRAY => true
//    ]
//)->view()->save('./data/languages/numerals.json');

// $jsonGen->buildDataFromJson('../../resources/data/language.json', [date('Y:m:d H:i:s')])->save('./data/languages/language.json');

// $jsonGen->buildDataFromJson('../../resources/data/country.json', [date('Y:m:d H:i:s')])->save('./data/countries/country.json');
// $jsonGen->buildDataFromJson('../../resources/data/currency.json', [date('Y:m:d H:i:s')])->save('./data/countries/currency.json');


// re-convert JSON to CSV
// $csvGen->fromGeneratedJson('./data/countries/currencies.json')->save('../../resources/data/currencies.csv');
// $csvGen->fromGeneratedJson('./data/countries/countries.json')->save('../../resources/data/countries.csv');
// $csvGen->fromGeneratedJson('./data/languages/languages.json')->save('../../resources/data/languages.csv');

// Generate classes
// $classGen->languages();
$classGen->countries();