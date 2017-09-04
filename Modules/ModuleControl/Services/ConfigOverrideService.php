<?php

namespace Modules\ModuleControl\Services;

use File;

// TODO Refact this class
class ConfigOverrideService
{
    /**
     * Write a config value in a specified config file.
     *
     * @param  string $indexes
     * @param  mixed $value
     * @return self
     */
    public function write($indexes, $value)
    {
        $payload = explode('.', $indexes);
        $filePath = config_path(sprintf('%s.php', array_shift($payload)));
        $data = File::get($filePath);

        if ($indexes == 'app.providers' || $indexes == 'app.aliases') {
            $contents = $this->attachContent($data, implode('.', $payload), $value);
        } else {
            $contents = $this->parseContent($data, [implode('.', $payload) => $value]);
        }

        File::put($filePath, $contents);

        return $this;
    }

    private function attachContent($contents, $key, $value)
    {
        // TODO Providers and Alias must be added case not exist, or replaced.
        $lines = explode("\n", $contents);
        $index = null;
        $locked = false;


        foreach ($lines as $lineKey => $line) {
            if (strpos($line, "'" . $key . "' => [") !== false) {
                $locked = true;
            }

            if ($locked === true && strpos($line, "]") && $index == null) {
                $index = $lineKey - 1;
            }
        }

        return implode("\n", array_merge(
            array_slice($lines, 0, $index, true),
            [$index => '        '.$value.','],
            array_slice($lines, $index, count($lines) - 1, true)
        ));
    }

    /**
     * Parse the config file content.
     *
     * @param  string $contents
     * @param  mixed $newValues
     * @return string
     */
    private function parseContent($contents, $newValues)
    {
        $patterns = [];
        $replacements = [];

        foreach ($newValues as $path => $value) {
            $items = explode('.', $path);
            $key = array_pop($items);

            $replaceValue = $this->formatValue($value);

            $patterns[] = $this->buildStringExpression($key, $items);
            $replacements[] = '${1}${2}'.$replaceValue;

            $patterns[] = $this->buildStringExpression($key, $items, '"', strpos($replaceValue, '::class'));
            $replacements[] = '${1}${2}'.$replaceValue;

            $patterns[] = $this->buildConstantExpression($key, $items);
            $replacements[] = '${1}${2}'.$replaceValue;
        }

        return preg_replace($patterns, $replacements, $contents, 1);
    }


    /**
     * Format value by type.
     *
     * @param  mixed $value
     * @return string
     */
    private function formatValue($value)
    {
        if (is_string($value) && strpos($value, "'") === false && strpos($value, '::class') === false) {
            return "'".$value."'";
        }

        if (is_string($value) && strpos($value, '"') === false && strpos($value, '::class') === false) {
            return '"'.$value.'"';
        }

        if (is_bool($value)) {
            return ($value ? 'true' : 'false');
        }

        if (is_null($value)) {
            return 'null';
        }

        return $value;
    }

    /**
     * Build regex from string expressions.
     *
     * @param  string  $targetKey
     * @param  array   $arrayItems
     * @param  string  $quoteChar
     * @param  boolean $namespace
     * @return string
     */
    private function buildStringExpression($targetKey, $arrayItems = [], $quoteChar = "'", $namespace = false)
    {
        $expression = [];
        $expression[] = $this->buildArrayOpeningExpression($arrayItems);

        if ($namespace === false) {
            $expression[] = '([\'|"]'.$targetKey.'[\'|"]\s*=>\s*)['.$quoteChar.']';
            $expression[] = '([^'.$quoteChar.']*)';
            $expression[] = '['.$quoteChar.']';

            return $this->formatExpression($expression);
        }

        $expression[] = '([>]\s)(?:([aA][pP][pP])*?)[^\']*(?:[:][:][c][l][a][s]{2}\*?)';

        return $this->formatExpression($expression);
    }

    /**
     * Build regex from common constants only (true, false, null, integers).
     *
     * @param  string $targetKey
     * @param  array  $arrayItems
     * @return string
     */
    private function buildConstantExpression($targetKey, array $arrayItems = [])
    {
        $expression = [];
        $expression[] = $this->buildArrayOpeningExpression($arrayItems);
        $expression[] = '([\'|"]' . $targetKey . '[\'|"]\s*=>\s*)';
        $expression[] = '([tT][rR][uU][eE]|[fF][aA][lL][sS][eE]|[nN][uU][lL]{2}|[\d]+)';

        return $this->formatExpression($expression);
    }

    /**
     * Format the regex expression.
     *
     * @param  array $expression
     * @return string
     */
    private function formatExpression(array $expression)
    {
        return '/' . implode('', $expression) . '/';
    }

    /**
     * Build regex from array openin.
     * @param  mixed $arrayItems
     * @return string
     */
    private function buildArrayOpeningExpression($arrayItems)
    {
        if (count($arrayItems)) {
            $itemOpen = [];

            foreach ($arrayItems as $item) {
                $itemOpen[] = '[\'|"]' . $item . '[\'|"]\s*=>\s*(?:[aA][rR]{2}[aA][yY]\(|[\[])';
            }

            return '(' . implode('[\s\S]*', $itemOpen) . '[\s\S]*?)';
        }

        return '()';
    }
}
