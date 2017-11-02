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
namespace draber\i18n\Country;

use draber\i18n\Country\CountryInterface;
use draber\i18n\Country\Iso3166Map;

/**
 * Country specific code snippets
 *
 * @author Dieter Raber
 */
abstract class CountryAbstract implements CountryInterface
{

    protected $countryName;

    public function __construct()
    {
        $this->countryName = Iso3166Map::getCode(static::countryCode);
    }

    /**
     * Retrieve country code
     *
     * @return string
     */
    public function getCountryCode() {
        return static::COUNTRY_CODE;
    }

    /**
     * Retrieve country name
     *
     * @return string
     */
    public function getCountryName() {
        return $this->countryName;
    }

    /**
     * Retrieve currency name
     *
     * @return string
     */
    public function getCurrencyName() {
        return static::CURRENCY_NAME;
    }

    /**
     * Retrieve currency code
     *
     * @return string
     */
    public function getCurrencyCode() {
        return static::CURRENCY_CODE;
    }

    /**
     * Retrieve currency symbol
     *
     * @return string
     */
    public function getCurrencySymbol() {
        return static::CURRENCY_SYMBOL;
    }
}