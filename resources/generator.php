<?php
/**
 * Created by PhpStorm.
 * User: Dieter
 * Date: 02/11/2017
 * Time: 09:26
 */

$currencies = json_decode(file_get_contents('./currencies.json'), 1);

$template = file_get_contents('./CountryTemplate.tpl');

$mapping = [
    'country'      => '{COUNTRY_NAME}',
    'countryCode'  => '{COUNTRY_CODE}',
    'currencyCode' => '{CURRENCY_CODE}',
    'currency'     => '{CURRENCY_NAME}',
    'symbol'       => '{CURRENCY_SYMBOL}'
];

foreach($currencies as $code => $currencyData){
    $class = $template;
    foreach($currencyData as $key => $entry){
        if($key === 'countryCode'){
            $entry = strtoupper($entry);
        }
        $class = str_replace($mapping[$key], $entry, $class);
    }
    $file = sprintf('../src/Country/Countries/%s.php', strtoupper($code));
    file_put_contents($file, $class);
}
