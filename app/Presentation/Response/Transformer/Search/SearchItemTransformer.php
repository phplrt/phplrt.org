<?php

declare(strict_types=1);

namespace App\Presentation\Response\Transformer\Search;

use App\Presentation\Response\DTO\Documentation\SearchItemResponseDTO;
use App\Domain\Documentation\Page;
use App\Domain\Documentation\Search\Index;
use App\Presentation\Response\Transformer\ResponseTransformer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @template-extends ResponseTransformer<Index, SearchItemResponseDTO>
 */
final readonly class SearchItemTransformer extends ResponseTransformer
{
    public function __construct(
        private UrlGeneratorInterface $generator,
        private SluggerInterface $slugger,
    ) {}

    /**
     * @param Index $entry
     * @param list<non-empty-string> $occurrences
     */
    public function transform(mixed $entry, array $occurrences = []): SearchItemResponseDTO
    {
        assert($entry instanceof Index);

        $page = $entry->getDocument();

        return new SearchItemResponseDTO(
            page: $page->getTitle(),
            title: $entry->getTitle(),
            url: $this->getUrl($page, $entry),
            found: \array_filter($occurrences),
        );
    }

    private function getUrl(Page $page, Index $index): string
    {
        $url = $this->generator->generate('docs.show', [
            'path' => $page->getUrl(),
        ]);

        return $url . '#' . $this->slugger->slug($index->getTitle());
    }
}
