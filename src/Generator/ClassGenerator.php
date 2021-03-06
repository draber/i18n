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
use draber\i18n\Language\LanguageBase;
use draber\i18n\Country\CountryBase;
use Doctrine\Common\Inflector\Inflector;
use draber\i18n\Common\Factory;

use draber\i18n\Language\Languages\LanguageMethods;
use draber\i18n\Country\Countries\CountryMethods;

/**
 * Generate classes that contain country or language specific current
 *
 * @author Dieter Raber
 */
class ClassGenerator extends GeneratorBase
{

    const METHOD_TPL = "
    /** 
     * Retrieve the value of static::{CONSTANT}
     * 
     * @return {TYPE}|null
     */
    public function {METHOD}() {
        return defined('static::{CONSTANT}') ? static::{CONSTANT} : null;
    }
    ";

    const README_TPL = "
    /** 
     * Retrieve the value of static::{CONSTANT}
     */
    {CLASS)->{METHOD}();
    ";

    private $lookupClass;

    private $methods = [];

    private $constants = [];

    /**
     * @var  string 'Country'|'Language'used in templates
     */
    private $singular;
    private $lowerSingular;

    /**
     * @var  string 'Countries'|'Languages'used in templates
     */
    private $plural;
    private $lowerPlural;

    private $isoList = [];

    private $classDir;


    /**
     * Build country files
     *
     * @return ClassGenerator
     */
    public function countries()
    {
        $this->singular    = 'Country';
        $this->lookupClass = CountryBase::class;

        return $this->generate();
    }

    /**
     * Build language files
     *
     * @return ClassGenerator
     */
    public function languages()
    {
        $this->singular    = 'Language';
        $this->lookupClass = LanguageBase::class;

        return $this->generate();
    }


    protected function generate()
    {
        // Initialize variables
        $this->init();

        // read files and separate into content, meta and config
        $this->loadData(__DIR__ . '/data/' . $this->lowerPlural);

        // topic is the bare name of a JSON file, e.g. 'numeral'
        foreach ($this->data as $topic => $data) {
            $config = $this->config[$topic];
            // make sure methods and constants get valid and reasonable names
            $uTopic = str_replace('-', '_', $topic);
            $sTopic = Inflector::singularize($uTopic);

            // code is an ISO code
            foreach ($data as $code => $entries) {
                if ($topic === $this->lowerPlural) {
                    $this->isoList[$code] = $entries['name'];
                }
                // all values in an array
                if (!empty($config[static::CONFIG_GEN_KEEP_ARRAY])) {
                    $this->addConstant($code, $uTopic, $entries);
                    $this->addMethod($uTopic, $entries);
                } // one value = one constant
                else {
                    foreach ($entries as $key => $entry) {
                        $this->addConstant($code, $sTopic, $entries, $key);
                        $this->addMethod($sTopic, $entries, $key);
                    }
                }
            }
        }

        $this->buildMethodClass();
        $this->buildIsoCodeClass();
        $this->buildConstantClasses();

        return $this;
    }

    /**
     * Load data for a given target
     *
     * @param $dataDir
     *
     * @return bool
     * @throws \Exception
     */
    protected function loadData($dataDir)
    {
        if (!is_dir($dataDir)) {
            throw new \Exception('Unknown directory ' . $dataDir);
        }

        foreach (glob($dataDir . '/*.json') as $i => $current) {

            $currentKey = static::bareName($current);
            $data       = json_decode(file_get_contents($current), 1);

            // for convenience of access make sure all keys always exist
            $this->data[$currentKey]     = !empty($data['data']) ? $data['data'] : [];
            $this->metaData[$currentKey] = !empty($data['meta']) ? $data['meta'] : [];
            $this->config[$currentKey]   = !empty($data['config']) ? $data['config'] : [];
        }

        return true;
    }


    /**
     * Initialize variables
     */
    protected function init()
    {
        $this->data          = [];
        $this->metaData      = [];
        $this->config        = [];
        $this->isoList       = [];
        $this->methods       = [];
        $this->constants     = [];
        $this->plural        = Inflector::pluralize($this->singular);
        $this->classDir      = dirname(__DIR__) . '/' . $this->singular . '/' . $this->plural;
        $this->lowerSingular = strtolower($this->singular);
        $this->lowerPlural   = strtolower($this->plural);
    }


    /**
     * Build a PHP comment from meta data
     *
     * @param string $currentKey
     *
     * @return bool|string
     */
    protected function metaToComment($currentKey)
    {
        if (empty($this->metaData[$currentKey])) {
            return false;
        }
        $comment = "    /**\n";
        foreach ($this->metaData[$currentKey] as $entry) {
            $comment .= '     *' . $entry . "\n";
        }
        $comment = "     */\n";

        return $comment;
    }


    /**
     * Build a constant to be used in the template
     *
     * @param string $code
     * @param string $topic
     * @param string $key
     * @param $value
     *
     * @return bool
     */
    protected function addConstant($code, $topic, $value, $key = '')
    {
        $name     = $key ? strtoupper($topic . '_' . $key) : strtoupper($topic);
        $value    = $key ? $value[$key] : $value;
        $constant = '    const ' . $name . ' = ';
        $constant .= gettype($value) === 'array'
            ? static::prettyPrint($value)
            : $this->quote($value);

        $this->constants[$code][$name] = $constant . ';';

        return true;
    }


    /**
     * Build a getter for each constant, unless there is a custom one
     *
     * @param $topic
     * @param $value
     * @param $key
     *
     * @return bool
     */
    protected function addMethod($topic, $value, $key = '')
    {
        $value    = $key ? $value[$key] : $value;
        $type     = gettype($value);
        $constant = $key ? strtoupper($topic . '_' . $key) : strtoupper($topic);
        $method   = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($constant))));
        if (method_exists($this->lookupClass, $method)) {
            $this->methods[$this->lookupClass . '::' . $method] = true;

            return false;
        }
        if (!empty($this->methods[$this->lookupClass . '::' . $method])) {
            return false;
        }
        $this->methods[$this->lookupClass . '::' . $method] = str_replace(
            ['{CONSTANT}', '{TYPE}', '{METHOD}'],
            [$constant, $type, $method],
            static::METHOD_TPL
        );

        return true;
    }


    /**
     * Build class with getters
     *
     * @return bool|int
     */
    protected function buildMethodClass()
    {
        $classCode = '';
        foreach ($this->methods as $method) {
            if (!is_string($method)) {
                continue;
            }
            $classCode .= $method . "\n";
        }
        $content = $this->populateTemplate(
            file_get_contents(__DIR__ . '/templates/methods.tpl'),
            $classCode
        );

        return file_put_contents($this->classDir . '/' . $this->singular . 'Methods.php', $content);
    }


    /**
     * Build class with getters
     *
     * @return bool|int
     */
    public function buildDocs()
    {
        $classes = [
            'Language' => [
                'class' => LanguageMethods::class,
            ],
            'Country'  => [
                'class' => CountryMethods::class,
            ]
        ];

        $docs = [];
        $longest = 0;
        foreach ($classes as $target => $classData) {
            $classes[$target]['reflection'] = new \ReflectionClass($classData['class']);
            $classes[$target]['methods']    = $classes[$target]['reflection']->getMethods(\ReflectionMethod::IS_PUBLIC);
            $classes[$target]['instance']   = call_user_func(
                [Factory::class, 'get' . $target],
                'de'
            );
            foreach($classes[$target]['methods'] as $method) {
                $data = $classes[$target]['instance']->{$method->name}();
                $type = gettype($data);
                $call = '$' . strtolower($target) . '->' . $method->name . '();';
                $longest = max([$longest, strlen($call)]);
                $docs[$target][] = [
                    'method' => $call,
                    'type' => $type,
                    'data' => $data
                ];
            }

            foreach($docs[$target] as $key => $valueArr) {
                $valueArr['method'] = str_pad($valueArr['method'], $longest);
                if($valueArr['type'] === 'string') {
                    $valueArr['data'] = static::quote($valueArr['data'], "'");
                }
                else {
                    $valueArr['data'] = static::prettyPrint($valueArr['data']);
                    $valueArr['data'] = trim(preg_replace("/^/m", str_repeat(' ', $longest) . ' // ', $valueArr['data']), ' /');
                }
                $docs[$target][$key] = str_pad($valueArr['method'], $longest)
                    . ' // returns ' . $valueArr['type'] . ' ' . $valueArr['data'];
            }
            $longest = 0;
        }

        return file_put_contents(dirname(dirname(__DIR__)) . '/README.md', str_replace(
            ['{LANGUAGE_CLASS_CODE}', '{COUNTRY_CLASS_CODE}'],
            [implode("\n", $docs['Language']), implode("\n", $docs['Country'])],
            file_get_contents(__DIR__ . '/templates/readme.tpl')
        ));
    }


    /**
     * Build class with Iso Codes
     *
     * @return bool|int
     */
    protected function buildIsoCodeClass()
    {
        $content = $this->populateTemplate(
            file_get_contents(__DIR__ . '/templates/isoList.tpl'),
            static::prettyPrint($this->isoList)
        );
        return file_put_contents($this->classDir . '/' . $this->singular . 'IsoMap.php', $content);
    }


    /**
     * Build class with constants, the language/country specific data classes
     *
     * @return bool
     */
    protected function buildConstantClasses()
    {
        $template = file_get_contents(__DIR__ . '/templates/constants.tpl');

        foreach ($this->constants as $code => $constants) {
            $classCode = '';
            foreach ($constants as $constant) {
                $classCode .= $constant . "\n";
            }
            $content = $this->populateTemplate(
                $template,
                ltrim($classCode),
                $code
            );
            if (false !== $content) {
                file_put_contents($this->classDir . '/' . $code . '.php', $content);
            }
        }
        return true;
    }


    /**
     * @param $template
     * @param $classCode
     * @param $isoCode
     *
     * @return string|bool
     */
    protected function populateTemplate($template, $classCode, $isoCode = '')
    {
        if ($isoCode && empty($this->isoList[$isoCode])) {
            return false;
        }
        return $isoCode
            ? str_replace(
                ['{SINGULAR}', '{PLURAL}', '{LOWER_PLURAL}', '{ISO_CODE}', '{NAME}', '{CLASS_CODE}'],
                [$this->singular, $this->plural, $this->lowerPlural, $isoCode, $this->isoList[$isoCode], $classCode],
                $template
            ) : str_replace(
                ['{SINGULAR}', '{PLURAL}', '{LOWER_PLURAL}', '{CLASS_CODE}'],
                [$this->singular, $this->plural, $this->lowerPlural, $classCode],
                $template
            );
    }
}
