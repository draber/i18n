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

namespace draber\i18n\Common;

use Doctrine\Common\Inflector\Inflector;

/**
 * Factory for the country/language specific classes
 *
 * @author Dieter Raber
 */
class Factory
{

    /**
     * Get an instance of a country specific class.
     * Accepts XX, xx-XX and xx_XX formatted iso codes.
     *
     * @param string $isoCode
     * @return object
     * @throws \Exception
     */
    public static function getCountry($isoCode)
    {
        $className = static::getClassName(substr($isoCode, 0, 2), 'countries');
        if (class_exists($className)) {
            return new $className();
        }
        throw new \Exception('Invalid class name ' . $className);
    }

    /**
     * Get an instance of a language specific class.
     * Accepts xx, xx-XX and xx_XX formatted iso codes.
     *
     * @param string $isoCode
     * @return object
     * @throws \Exception
     */
    public static function getLanguage($isoCode)
    {
        $longClassName = static::getClassName($isoCode, 'languages');
        if (class_exists($longClassName)) {
            return new $longClassName();
        }
        // this might be the same as $longClassName!
        $shortClassName = static::getClassName(substr($isoCode, 0, 2), 'languages');
        if (class_exists($shortClassName)) {
            return new $shortClassName();
        }
        throw new \Exception('Invalid class name ' . $longClassName);
    }


    /**
     * Build a namespace class name.
     * Accepts xx, xx-XX and xx_XX formatted iso codes.
     *
     * @param $isoCode
     * @param $type
     * @return string
     */
    protected static function getClassName($isoCode, $type)
    {
        // XX
        if ($type === 'countries') {
            $isoCode = strtoupper(substr($isoCode, -2));
        }
        // xx-XX or xx_XX
        else if (strlen($isoCode) === 5) {
            $isoCode = strtolower(substr($isoCode, 0, 2)) . '_' . strtoupper(substr($isoCode, -2));
        }
        // xx
        else {
            $isoCode = strtolower(substr($isoCode, 0, 2));
        }
        $ns = dirname(__NAMESPACE__) . '\\' . ucfirst(Inflector::singularize($type));
        return $ns . '\\' . ucfirst($type) . '\\' . $isoCode;
    }
}
