<?php

namespace Icmbio\ValidateXpathExpression;

use Throwable;

class Repl
{
    /** @var list<string> */
    protected array $suggestions = [
        'validate(',
        'escape_expression(',
        'Xpath::validate(',
        'Xpath::escapeExpression(',
        'new Xpath(',
        'true',
        'false',
        'null',
        'selected(',
        'string-length(',
        'string_length(',
        'int(',
        'floor(',
        'ceiling(',
        'number(',
        'string(',
        'contains(',
        'starts-with(',
        'normalize-space(',
        'choose(',
        'not(',
        'uuid(',
        'format-date-time(',
        'substring-after(',
        'substring-before(',
    ];

    public function __construct(
        protected ?string $historyFile = null
    ) {
    }

    public function run(): int
    {
        $this->bootReadline();
        $this->writeBanner();

        while (true) {
            $line = readline('xpath> ');
            if ($line === false) {
                fwrite(STDOUT, PHP_EOL);
                return 0;
            }

            $input = trim($line);

            if ($input === '') {
                continue;
            }

            if ($this->handleCommand($input)) {
                continue;
            }

            readline_add_history($line);
            $this->persistHistory();

            try {
                $result = $this->evaluate($input);
                fwrite(STDOUT, $this->formatResult($result) . PHP_EOL);
            } catch (Throwable $exception) {
                fwrite(STDERR, '[error] ' . $exception->getMessage() . PHP_EOL);
            }
        }
    }

    public function evaluate(string $input): mixed
    {
        $bootstrap = <<<'PHP'
use Icmbio\ValidateXpathExpression\Xpath;
use function Icmbio\ValidateXpathExpression\escape_expression;
use function Icmbio\ValidateXpathExpression\validate;

return %s;
PHP;

        return eval(sprintf($bootstrap, $input) . ';');
    }

    /**
     * @return list<string>
     */
    public function complete(string $input): array
    {
        $matches = array_values(array_filter(
            $this->suggestions,
            static fn(string $suggestion): bool => str_starts_with($suggestion, $input)
        ));

        sort($matches);

        return $matches;
    }

    protected function bootReadline(): void
    {
        if (!function_exists('readline')) {
            throw new \RuntimeException('The readline extension is required to run the REPL.');
        }

        $historyFile = $this->resolveHistoryFile();

        if (is_file($historyFile)) {
            @readline_read_history($historyFile);
        }

        if (function_exists('readline_completion_function')) {
            readline_completion_function(fn(string $input): array => $this->complete($input));
        }
    }

    protected function persistHistory(): void
    {
        if (function_exists('readline_write_history')) {
            @readline_write_history($this->resolveHistoryFile());
        }
    }

    protected function writeBanner(): void
    {
        fwrite(STDOUT, 'Validate XPath Expression REPL' . PHP_EOL);
        fwrite(STDOUT, 'Available aliases: validate(), escape_expression(), Xpath' . PHP_EOL);
        fwrite(STDOUT, 'Commands: :help, :clear, :quit' . PHP_EOL);
    }

    protected function handleCommand(string $input): bool
    {
        if ($input === ':quit' || $input === ':exit') {
            exit(0);
        }

        if ($input === ':help') {
            fwrite(STDOUT, 'Examples:' . PHP_EOL);
            fwrite(STDOUT, "  validate('. >= 1 and . <= 100', 10)" . PHP_EOL);
            fwrite(STDOUT, "  Xpath::validate('string-length(.)', 'abacate', [], false)" . PHP_EOL);
            fwrite(STDOUT, "  escape_expression('\${value}')" . PHP_EOL);
            return true;
        }

        if ($input === ':clear') {
            fwrite(STDOUT, "\033[2J\033[;H");
            return true;
        }

        return false;
    }

    protected function resolveHistoryFile(): string
    {
        if ($this->historyFile !== null) {
            return $this->historyFile;
        }

        return getcwd() . '/.xpath-repl_history';
    }

    protected function formatResult(mixed $result): string
    {
        if (is_bool($result)) {
            return $result ? 'true' : 'false';
        }

        if (is_string($result)) {
            return $result;
        }

        return var_export($result, true);
    }
}
