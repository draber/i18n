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


/**
 * Class CommonBase
 * Methods shared by Language and Country classes
 *
 * @package draber\i18n\Common
 */
class CommonBase
{

    /**
     * Extends array search with a bit of fuzzyness
     *
     * @param $value
     * @param $array
     * @return bool|false|int|string
     */
    protected static function arrayFuzzySearch($value, $array) {

        $value  = trim($value);
        $result = array_search(trim($value), $array);
        if(false !== $result) {
            return $result;
        }
        $search = preg_grep('/' . preg_quote($value, '/') . '/i', $array);
        if(count($search)) {
            $value  = array_shift($search);
            $result = array_search(trim($value), $array);
            if(false !== $result) {
                return $result;
            }
        }
        return false;
    }


    /**
     * Get foo from /path/to/foo.bar
     *
     * @param $filePath
     * @return bool|string
     */
    protected function bareName($filePath){
        $file = basename($filePath);
        return substr($file, 0, strrpos($file, '.'));
    }


    /**
     * Quote a string with double quotes
     *
     * @param string $string
     * @param string $char
     * @return string
     */
    protected function quote($string, $char = '"') {
        return $char . str_replace($char, '\\' . $char, $string) . $char;
    }
}