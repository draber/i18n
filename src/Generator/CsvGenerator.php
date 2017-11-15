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

namespace draber\i18n\Generator;

use draber\i18n\Generator\GeneratorBase;


/**
 * Class CsvGenerator
 *
 * @package draber\i18n\Generator
 */
class CsvGenerator extends GeneratorBase
{

    private $csv;

    /**
     * Save converted data
     *
     * @param $path
     * @return $this
     */
    public function save($path) {
        file_put_contents($path, $this->csv);
        return $this;
    }


    /**
     * View converted data
     *
     * @return $this
     */
    public function view(){
        print $this->csv;
        return $this;
    }


    /**
     * Convert generated JSON back to CSV
     *
     * @param $inputPath
     * @return $this
     * @throws \Exception
     */
    public function fromGeneratedJson($inputPath) {
        if (!is_readable($inputPath)) {
            throw new \Exception($inputPath . ' not readable');
        }
        $this->buildDataFromArray(json_decode(file_get_contents($inputPath), true));
        $firstRow = array_keys(current($this->data));
        array_unshift($firstRow, '');
        $this->csv = $this->toCsvLine($firstRow);
        foreach($this->data as $code => $entries){
            array_unshift($entries, $code);
            $this->csv .= $this->toCsvLine($entries);
        }
        return $this;
    }


    /**
     * @param array $array
     * @return string
     */
    protected function toCsvLine(array $array) {
        $array = array_map([$this, 'quote'], $array);
        return implode(',', $array) . "\n";
    }
}
