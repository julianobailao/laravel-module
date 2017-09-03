<?php

namespace Modules\ModuleControl\Services;

use File;

class ConfigOverrideService
{
    public function write($file, $indexes, $value)
    {
        // $configData = \Config::get($file);
        // $this->array_set_value($configData, implode('.', $indexes), $value);

        $filePath = config_path(sprintf('%s.php', $file));
        $data = File::get($filePath);
        $contents = $this->parseContent($data, ['providers.users.model' => $value]);
        file_put_contents('teste_config.php', $contents);
    }

    protected function array_set_value(array &$array, $parents, $value, $glue = '.')
    {
        if (!is_array($parents)) {
            $parents = explode($glue, (string) $parents);
        }

        $ref = &$array;

        foreach ($parents as $parent) {
            if (isset($ref) && !is_array($ref)) {
                $ref = [];
            }

            $ref = &$ref[$parent];
        }

        $ref = $value;

        return $this;
    }

    public function toContent($contents, $newValues, $useValidation = true)
    {
        $contents = $this->parseContent($contents, $newValues);

        if ($useValidation) {
            $result = eval('?>'.$contents);

                foreach ($newValues as $key => $expectedValue) {
                    $parts = explode('.', $key);

                    $array = $result;
                    foreach ($parts as $part) {
                        if (!is_array($array) || !array_key_exists($part, $array))
                            throw new Exception(sprintf('Unable to rewrite key "%s" in config, does it exist?', $key));

                        $array = $array[$part];
                    }
                    $actualValue = $array;

                if ($actualValue != $expectedValue)
                    throw new Exception(sprintf('Unable to rewrite key "%s" in config, rewrite failed', $key));
            }
        }

        return $contents;
    }

    private function parseContent($contents, $newValues)
    {
        $patterns = array();
        $replacements = array();

        foreach ($newValues as $path => $value) {
            $items = explode('.', $path);
            $key = array_pop($items);

            if (is_string($value) && strpos($value, "'") === false) {
                $replaceValue = "'".$value."'";
            } elseif (is_string($value) && strpos($value, '"') === false) {
                $replaceValue = '"'.$value.'"';
            } elseif (is_bool($value)) {
                $replaceValue = ($value ? 'true' : 'false');
            } elseif (is_null($value)) {
                $replaceValue = 'null';
            } else {
                $replaceValue = $value;
            }

            $patterns[] = $this->buildStringExpression($key, $items);
            $replacements[] = '${1}${2}'.$replaceValue;

            $patterns[] = $this->buildStringExpression($key, $items, '"');
            $replacements[] = '${1}${2}'.$replaceValue;

            $patterns[] = $this->buildConstantExpression($key, $items);
            $replacements[] = '${1}${2}'.$replaceValue;
        }

        dd(preg_replace($patterns, $replacements, $contents, 1), $patterns, $replacements);
        dd(preg_replace($patterns, $replacements, $contents, 1));

        return preg_replace($patterns, $replacements, $contents, 1);
    }

    private function buildStringExpression($targetKey, $arrayItems = array(), $quoteChar = "'")
    {
        $expression = array();

        // Opening expression for array items ($1)
        $expression[] = $this->buildArrayOpeningExpression($arrayItems);

        // The target key opening
        $expression[] = '([\'|"]'.$targetKey.'[\'|"]\s*=>\s*)['.$quoteChar.']';

        // The target value to be replaced ($2)
        $expression[] = '([^'.$quoteChar.']*)';

        // The target key closure
        $expression[] = '['.$quoteChar.']';

        dd($expression);

        return '/' . implode('', $expression) . '/';
    }

    /**
     * Common constants only (true, false, null, integers)
     */
    private function buildConstantExpression($targetKey, $arrayItems = array())
    {
        $expression = array();

        // Opening expression for array items ($1)
        $expression[] = $this->buildArrayOpeningExpression($arrayItems);

        // The target key opening ($2)
        $expression[] = '([\'|"]'.$targetKey.'[\'|"]\s*=>\s*)';

        // The target value to be replaced ($3)
        $expression[] = '([tT][rR][uU][eE]|[fF][aA][lL][sS][eE]|[nN][uU][lL]{2}|[\d]+)';

        return '/' . implode('', $expression) . '/';
    }

    private function buildArrayOpeningExpression($arrayItems)
    {
        if (count($arrayItems)) {
            $itemOpen = array();
            foreach ($arrayItems as $item) {
                // The left hand array assignment
                $itemOpen[] = '[\'|"]'.$item.'[\'|"]\s*=>\s*(?:[aA][rR]{2}[aA][yY]\(|[\[])';
            }

            // Capture all opening array (non greedy)
            $result = '(' . implode('[\s\S]*', $itemOpen) . '[\s\S]*?)';
        } else {
            // Gotta capture something for $1
            $result = '()';
        }

        return $result;
    }
}
