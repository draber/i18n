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

/**
 * WARNING: This is generated code - DO NOT EDIT!
 * Create a new data file in Generator/data/countries instead and run the
 * the code generator.
 */

namespace draber\i18n\Country\Countries;

use draber\i18n\Country\CountryBase;

/**
 * Country specific methods
 *
 * @author Dieter Raber
 */
class CountryMethods extends CountryBase
{
    
    /** 
     * Retrieve the value of static::COUNTRY_NAME
     * 
     * @return string|null
     */
    public function getCountryName() {
        return defined('static::COUNTRY_NAME') ? static::COUNTRY_NAME : null;
    }
    

    /** 
     * Retrieve the value of static::COUNTRY_CODE
     * 
     * @return string|null
     */
    public function getCountryCode() {
        return defined('static::COUNTRY_CODE') ? static::COUNTRY_CODE : null;
    }
    

    /** 
     * Retrieve the value of static::CURRENCY_CODE
     * 
     * @return string|null
     */
    public function getCurrencyCode() {
        return defined('static::CURRENCY_CODE') ? static::CURRENCY_CODE : null;
    }
    

    /** 
     * Retrieve the value of static::CURRENCY_NAME
     * 
     * @return string|null
     */
    public function getCurrencyName() {
        return defined('static::CURRENCY_NAME') ? static::CURRENCY_NAME : null;
    }
    

    /** 
     * Retrieve the value of static::CURRENCY_SYMBOL
     * 
     * @return string|null
     */
    public function getCurrencySymbol() {
        return defined('static::CURRENCY_SYMBOL') ? static::CURRENCY_SYMBOL : null;
    }
    

}
