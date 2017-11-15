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

use draber\i18n\Common\CommonBase;

/**
 * Class GeneratorBase
 *
 * @package draber\i18n\Generator
 */
class GeneratorBase extends CommonBase
{

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $metaData = [];

    /**
     * @var array
     */
    protected $config = [];



    /**
     * CSS reader configuration
     *
     * GeneratorBase::CONFIG_CSV_SEPARATOR = ';' to set to semicolon, default is ','
     */
    const CONFIG_CSV_SEPARATOR = 'csvSeparator';

    /**
     * CSS reader configuration
     *
     * GeneratorBase::CONFIG_CSV_ENCLOSE = "'" to set to single quotes, default is '"'
     */
    const CONFIG_CSV_ENCLOSE = 'csvEncloser';


    /**
     * CSS reader configuration
     *
     * GeneratorBase::CONFIG_CSV_FIRST_LINE_KEYS = true to use the first line of a csv file
     * as array keys for all other lines
     */
    const CONFIG_CSV_FIRST_LINE_KEYS = 'csvFirstLineKeys';


    /**
     * Generator configuration
     *
     * GeneratorBase::CONFIG_GEN_KEEP_ARRAY = true to force constants of a certain JSON file
     * to be kept grouped in an array. Default is false, each value becomes a constant.
     */
    const CONFIG_GEN_KEEP_ARRAY = 'generatorKeepArray';



    /**
     * Format array for printing
     *
     * @param $array
     * @return string
     * @todo don't replace anything in the actual values!
     */
    protected static function prettyPrint(array $array)
    {
        $indent = '    '; // 4 spaces
        $string = var_export($array, 1);
        $string = preg_replace("~\s*array \(~m", " [", str_replace('),' . PHP_EOL, '],' . PHP_EOL, $string));
        $string = trim(str_replace('  ', $indent, $string));
        return trim(preg_replace("/^/m", $indent, $string));
    }

    /**
     * @param $inputPath
     * @param array $metaData, whatever you want to appear as a comment for your data, default is auto-generated from the file name
     * @param array $config, configure things the class generator should consider, CONFIG_GEN_* constants
     * @return $this
     * @throws \Exception
     */
    public function buildDataFromJson($inputPath, array $metaData = [], array $config = [])
    {
        if (!is_readable($inputPath)) {
            throw new \Exception($inputPath . ' not readable');
        }

        $this->buildMetaData($metaData);
        $this->buildConfig($config);
        $this->buildDataFromArray(json_decode(file_get_contents($inputPath), true));
        $this->fileToMetaData($inputPath);

        return $this;
    }


    /**
     * @param $array
     * @return $this
     */
    public function buildDataFromArray(array $array)
    {
        if (!empty($array['data']) && is_array($array['data'])) {
            $this->data = $array['data'];
        } else {
            $this->data = $array;
        }

        if (!empty($array['meta'])) {
            $this->buildMetaData($array['meta']);
        }

        if (!empty($array['config'])) {
            $this->buildConfig($array['config']);
        }
        return $this;
    }


    /**
     * Read data from CSV file.
     * CSV files MUST HAVE the language or country code as their first column
     *
     * @param string $inputPath
     * @param array $metaData, whatever you want to appear as a comment for your data, default is auto-generated from the file name
     * @param array $config, configure both, this read and the generator, see static::CONFIG_* constants for details
     * @return $this
     *
     * @throws \Exception
     */
    public function buildDataFromCsv($inputPath, array $metaData = [], array $config = [])
    {
        if (!is_readable($inputPath)) {
            throw new \Exception($inputPath . ' not readable');
        }

        $config = array_merge(
            [
                static::CONFIG_CSV_ENCLOSE   => '"',
                static::CONFIG_CSV_SEPARATOR => ',',
                static::CONFIG_CSV_FIRST_LINE_KEYS => false
            ],
            $config
        );

        $data = [];
        $keys = [];
        $i    = 0;

        if (($handle = fopen($inputPath, 'r')) !== false) {
            while (($rowData = fgetcsv(
                    $handle,
                    1000,
                    $config[static::CONFIG_CSV_SEPARATOR],
                    $config[static::CONFIG_CSV_ENCLOSE]
                )) !== false
            ) {
                $code = array_shift($rowData);
                if($i === 0 && $config[static::CONFIG_CSV_FIRST_LINE_KEYS]) {
                    $keys = $rowData;
                    continue;
                }
                $data[$code] = $config[static::CONFIG_CSV_FIRST_LINE_KEYS] ? array_combine($keys, $rowData) : $rowData;
                $i++;
            }
            fclose($handle);
        }

        $this->buildMetaData($metaData);
        $this->buildConfig($config);
        $this->buildDataFromArray($data);
        $this->fileToMetaData($inputPath);

        return $this;
    }


    /**
     * @param string|array $input either a path to a data file, limited to csv for now or an array with data
     * @param array $metaData , whatever you want to appear as a comment for your data, default is auto-generated from the file name
     * @param array $config , see constants for your input file format
     * @return $this
     * @throws \Exception
     */
    public function buildDataFromAnything($input, array $metaData = [], array $config = [])
    {
        $this->buildMetaData($metaData);

        if (is_array($input)) {
            $this->buildDataFromArray($input);
        }
        // delegate to appropriate method
        else {
            $inputFile   = basename($input);
            $inputFormat = strtolower(substr(strrchr($inputFile, '.'), 1));
            $readMethod  = 'buildDataFrom' . ucfirst($inputFormat);
            $this->fileToMetaData($input);

            if (!method_exists($this, $readMethod)) {
                throw new \Exception('No reading method implemented for ' . $inputFormat);
            }
            call_user_func([$this, $readMethod], $input, $config);
        }
        return $this;
    }


    /**
     * @param array $metaData
     * @return $this
     */
    public function buildMetaData(array $metaData = [])
    {
        $this->metaData = array_unique(array_merge($this->metaData, $metaData));
        return $this;
    }

    
    /**
     * @param array $config
     * @return $this
     */
    public function buildConfig(array $config = [])
    {
        $this->config = array_unique(array_merge($this->config, $config));
        return $this;
    }


    /**
     * Turn foo.ext|fooBar.ext|foo-bar.ext|foo_bar.ext into Foo, resp. Foo Bar.
     * It is important to note that this function should always be called as the last one
     *
     * @param string $file
     * @return string
     */
    protected function fileToMetaData($file)
    {
        // possible camelCase to space
        $title = strtolower(preg_replace('/(?<=\\w)([A-Z])/', ' \\1', static::bareName($file)));

        // remove unwanted characters
        $title = preg_replace('~[\W]+~', ' ', str_replace('_', ' ', $title));

        // case
        $title = ucwords($title);

        // check if something similar is not already present
        if(false === static::arrayFuzzySearch($title, $this->metaData)) {
            $this->buildMetaData([ucwords($title)]);
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'meta' => $this->metaData,
            'data' => $this->data,
            'config' => $this->config
        ];
    }
}