<?php

declare(strict_types=1);

namespace App\Presentation\Response\Transformer;

/**
 * @template TInput
 * @template TOutput
 *
 * @template-implements ResponseTransformerInterface<TInput, TOutput>
 */
abstract readonly class ResponseTransformer implements ResponseTransformerInterface
{
    /**
     * @template TArgKey of array-key
     *
     * @param iterable<TArgKey, TInput> $entries
     *
     * @return iterable<TArgKey, TOutput>
     */
    public function map(iterable $entries, mixed ...$args): iterable
    {
        foreach ($entries as $i => $entry) {
            yield $i => $this->transform($entry, ...$args);
        }
    }

    /**
     * @param TInput|null $entry
     *
     * @return TOutput|null
     */
    public function optional(mixed $entry): mixed
    {
        if ($entry === null) {
            return $entry;
        }

        return $this->transform($entry);
    }

    /**
     * @param iterable<TInput> $entries
     *
     * @return list<TOutput>
     */
    public function mapToArray(iterable $entries, mixed ...$args): array
    {
        return \iterator_to_array(
            iterator: $this->map($entries, ...$args),
            preserve_keys: false,
        );
    }
}
