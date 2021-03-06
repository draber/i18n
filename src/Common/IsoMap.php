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

use draber\i18n\Common\CommonBase;

/**
 * Mapping of iso/languages names to ISO 3166-1/639-1 alpha 2 codes
 *
 * @author Dieter Raber
 */
class IsoMap extends CommonBase
{

    const ISO_LIST = [];
    
    /**
     * Get the iso name base on the code
     *
     * @param $code
     *
     * @return string|false
     */
    public static function getName($code)
    {
        $code = strtolower($code);
        return !empty(static::ISO_LIST[$code]) ? static::ISO_LIST[$code] : false;
    }


    /**
     * Get the iso code base on the name
     *
     * @param $name
     *
     * @return string|false
     */
    public static function getCode($name) {
        return static::arrayFuzzySearch($name, static::ISO_LIST);
    }
}
