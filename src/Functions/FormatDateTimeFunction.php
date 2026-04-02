<?php

namespace Icmbio\ValidateXpathExpression\Functions;

use DateTime;
use Exception;

class FormatDateTimeFunction implements XPathFunctionInterface
{
    public function __construct(
        protected mixed $date,
        protected mixed $format
    ) {
    }

    public function handle(): mixed
    {
        if (!$this->date) {
            return null;
        }

        try {
            $datetime = rtrim((string) $this->date, 'Z');
            $date = new DateTime($datetime);

            $map = [
                '%Y' => 'Y',
                '%m' => 'm',
                '%d' => 'd',
                '%H' => 'H',
                '%M' => 'i',
                '%S' => 's',
            ];

            $phpFormat = strtr((string) $this->format, $map);

            return $date->format($phpFormat);
        } catch (Exception) {
            return null;
        }
    }
}
