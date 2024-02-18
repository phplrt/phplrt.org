<?php

declare(strict_types=1);

namespace Local\ContentRenderer;

use Local\ContentRenderer\Extension\ImportHeaderClasses;
use Local\ContentRenderer\Extension\NormalizeAnchors;
use Local\ContentRenderer\Extension\QuotesFormatter;
use Local\ContentRenderer\Extension\RemoveEmptyParagraphs;
use Local\ContentRenderer\Extension\RemoveStyleTags;
use League\CommonMark\Util\HtmlFilter;
use Local\ContentRenderer\Extension\ShortQuotesFormatter;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class DocumentationRenderer extends Renderer
{
    public function __construct(
        SluggerInterface $slugger = new AsciiSlugger(),
    ) {
        parent::__construct([
            'html_input' => HtmlFilter::ALLOW,
        ]);

        $this->env->addExtension(new ImportHeaderClasses($slugger));
        $this->env->addExtension(new ShortQuotesFormatter());
        $this->env->addExtension(new QuotesFormatter());
        $this->env->addExtension(new RemoveEmptyParagraphs());
        $this->env->addExtension(new NormalizeAnchors());
        $this->env->addExtension(new RemoveStyleTags());
    }
}
