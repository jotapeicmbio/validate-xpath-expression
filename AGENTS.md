# AGENTS.md

Guidance for AI assistants and automated contributors working in this repository.

## Project purpose

This repository provides a small PHP library for validating and evaluating a focused XPath-like expression dialect.

It is intentionally not a full XPath engine.

## Core design rules

- Do not reintroduce `eval()`.
- Keep responsibilities separated by object.
- Prefer small, explicit classes over large multi-purpose classes.
- Keep function execution logic inside `src/Functions`.
- Keep the public entry point centered on `Xpath`.
- Treat rejection behavior as part of the contract, not as optional hardening.

## Expression scope

This project supports a constrained expression dialect.

Supported concepts currently include:

- numeric literals
- quoted string literals
- `true`, `false`, `null`
- function calls registered in `FunctionRegistry`
- arithmetic operators
- comparison operators
- boolean operators
- parentheses
- placeholder replacement through `.` and `${name}`

Do not silently expand the language to support unrelated syntax without explicit intent and tests.

## Architectural boundaries

- `Xpath` orchestrates execution.
- `ExpressionPreparer` normalizes user-facing syntax.
- `ExpressionTokenizer` performs lexical analysis.
- `ExpressionEvaluator` parses and evaluates token streams.
- `FunctionRegistry` defines allowed functions.
- `src/Functions/*` contains one handler per function.

If a class starts to accumulate multiple responsibilities, prefer extraction.

## Function handler rules

When adding a new XPath-like function:

1. Create a dedicated class in `src/Functions`.
2. Implement `XPathFunctionInterface`.
3. Accept inputs through the constructor.
4. Expose execution through `handle(): mixed`.
5. Register the function in `FunctionRegistry`.
6. Add focused unit tests for the handler.
7. Add integration coverage if the function changes parser-visible behavior.

## Testing conventions

Prefer file parity between source files and test files:

- `src/ExpressionTokenizer.php` -> `tests/Unit/ExpressionTokenizerTest.php`
- `src/ExpressionEvaluator.php` -> `tests/Unit/ExpressionEvaluatorTest.php`

When a component has many scenarios, split by context using a dedicated test grouping style, for example:

- `ExpressionTokenizerTest`
- `ExpressionTokenizerRejectionTest`

## Security expectations

- Invalid expressions should fail predictably.
- Unsupported syntax should be rejected explicitly.
- Unknown functions must not be executed.
- Constructor arity mismatches must not leak as uncontrolled behavior.
- Rejection tests are expected for dangerous or malformed inputs.

## Documentation expectations

- Keep user-facing documentation in English and Portuguese (Brazil) when the change is significant.
- Keep `README` concise and navigable.
- Put deeper implementation details under `docs/en` and `docs/pt-BR`.
- If the expression language changes, update the specification files as part of the same work.

## Change management

When making non-trivial changes:

- update or add tests first or together with implementation
- preserve current passing behavior unless intentionally changing the contract
- document newly accepted syntax
- document newly rejected syntax if it affects callers

## Commit style

Prefer context-based commits, for example:

- `feat: add xpath function handlers and interface`
- `refactor: extract function registry and expression services`
- `test: add rejection coverage for xpath parsing`
- `docs: add bilingual project and architecture documentation`

## Commit hygiene

Commits should tell the implementation story in small, readable steps.

Prefer:

- one responsibility per commit
- one context per commit
- tests committed with the behavior they document or protect
- documentation committed with the feature, refactor, or rule it explains when practical

Avoid:

- mixing unrelated refactors in the same commit
- mixing broad code changes, tests, and docs without a clear narrative
- hiding security-sensitive changes inside generic refactor commits when they can be isolated

Recommended grouping patterns:

- `feat`: new function handlers, new supported syntax, new public capabilities
- `refactor`: internal architecture changes without intended behavior change
- `test`: coverage expansion, regression tests, rejection tests
- `fix`: bug fixes, parser hardening, validation tightening
- `docs`: README, wiki-oriented docs, specifications, contributor guidance
- `chore`: repository maintenance, ignore rules, tooling metadata

When a change affects the expression language contract:

1. update or add tests
2. update the specification files
3. update user-facing docs if the change is externally visible

When a change affects security or rejection behavior:

1. add or update rejection tests
2. keep the implementation change small and explicit
3. prefer a dedicated commit when the security impact is material

## If you are unsure

- prefer smaller, incremental changes
- prefer explicit rejection over permissive parsing
- prefer adding tests before broadening language support
- prefer preserving architecture over short-term shortcuts
