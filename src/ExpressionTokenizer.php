<?php

namespace Icmbio\ValidateXpathExpression;

use RuntimeException;

class ExpressionTokenizer
{
    public function tokenize(string $expression): array
    {
        $length = strlen($expression);
        $position = 0;
        $tokens = [];

        while ($position < $length) {
            $char = $expression[$position];

            if (ctype_space($char)) {
                $position++;
                continue;
            }

            $twoChars = substr($expression, $position, 2);
            if (in_array($twoChars, ['->', '..'], true)) {
                throw new RuntimeException("Token nao suportado: {$twoChars}");
            }

            if (in_array($twoChars, ['&&', '||', '==', '!=', '<=', '>='], true)) {
                $tokens[] = ['type' => 'operator', 'value' => $twoChars];
                $position += 2;
                continue;
            }

            if (in_array($char, ['(', ')', ',', '+', '-', '*', '/', '%', '<', '>'], true)) {
                $tokens[] = ['type' => 'symbol', 'value' => $char];
                $position++;
                continue;
            }

            if (ctype_digit($char)) {
                $tokens[] = $this->readNumberToken($expression, $position);
                continue;
            }

            if (in_array($char, ["'", '"'], true)) {
                $tokens[] = $this->readStringToken($expression, $position);
                continue;
            }

            if (preg_match('/[A-Za-z_]/', $char) === 1) {
                $tokens[] = $this->readIdentifierToken($expression, $position);
                continue;
            }

            throw new RuntimeException("Token nao suportado: {$char}");
        }

        return $tokens;
    }

    protected function readNumberToken(string $expression, int &$position): array
    {
        $remaining = substr($expression, $position);

        if (preg_match('/^\d+(?:\.\d+)?/', $remaining, $matches) !== 1) {
            throw new RuntimeException('Numero invalido');
        }

        $nextChar = $remaining[strlen($matches[0])] ?? null;
        if ($nextChar !== null && preg_match('/[A-Za-z_\.]/', $nextChar) === 1) {
            throw new RuntimeException('Numero invalido');
        }

        $position += strlen($matches[0]);

        return [
            'type' => 'number',
            'value' => str_contains($matches[0], '.') ? (float) $matches[0] : (int) $matches[0],
        ];
    }

    protected function readStringToken(string $expression, int &$position): array
    {
        $quote = $expression[$position];
        $position++;
        $value = '';
        $length = strlen($expression);

        while ($position < $length) {
            $char = $expression[$position];

            if ($char === '\\') {
                if ($position + 1 >= $length) {
                    throw new RuntimeException('String malformada');
                }

                $value .= '\\' . $expression[$position + 1];
                $position += 2;
                continue;
            }

            if ($char === $quote) {
                $position++;

                return [
                    'type' => 'string',
                    'value' => $this->decodeStringLiteral($value, $quote),
                ];
            }

            $value .= $char;
            $position++;
        }

        throw new RuntimeException('String nao terminada');
    }

    protected function decodeStringLiteral(string $value, string $quote): string
    {
        if ($quote === "'") {
            return str_replace(["\\\\", "\\'"], ["\\", "'"], $value);
        }

        return stripcslashes($value);
    }

    protected function readIdentifierToken(string $expression, int &$position): array
    {
        $remaining = substr($expression, $position);

        if (preg_match('/^[A-Za-z_][A-Za-z0-9_-]*/', $remaining, $matches) !== 1) {
            throw new RuntimeException('Identificador invalido');
        }

        $position += strlen($matches[0]);

        return [
            'type' => 'identifier',
            'value' => $matches[0],
        ];
    }
}
