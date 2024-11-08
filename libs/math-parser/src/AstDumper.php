<?php

declare(strict_types=1);

namespace Local\MathParser;

use Highlight\Highlighter;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class AstDumper
{
    private CliDumper $dumper;

    public function __construct()
    {
        $this->dumper = $this->createCliDumper();
    }

    private function createCliDumper(): CliDumper
    {
        $dumper = new CliDumper();
        $dumper->setColors(false);
        $dumper->setCharset('utf-8');
        $dumper->setDisplayOptions([]);
        $dumper->setStyles([]);

        return $dumper;
    }

    public function dump(mixed $value, string $namespace = ''): string
    {
        if (\is_array($value)) {
            return $this->dump($value[0], $namespace);
        }

        $data = (new VarCloner())
            ->cloneVar($value)
            ->withRefHandles(false);

        return $this->dumper->dump($data, true);
    }

    public function highlight(Highlighter $hl, mixed $value, string $namespace = ''): string
    {
        $result = $this->dump($value, $namespace);

        $result = $hl->highlight('ast', $result)->value;

        return $this->replace($result, $namespace);
    }

    private function replace(string $output, string $namespace): string
    {
        $replacements = [
            \trim($namespace, '\\') . '\\' => '',
            '"integer"' => 'integer',
            '"float"'   => 'float',
            '+a' => 'a',
            '+b' => 'b',
            '-value' => 'value',
        ];

        return \str_replace(
            search: \array_keys($replacements),
            replace: \array_values($replacements),
            subject: $output,
        );
    }
}
