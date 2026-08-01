<?php

declare(strict_types=1);

namespace Local\ContentRenderer;

use Local\ContentRenderer\Extension\ImportHeaderClasses;
use Local\ContentRenderer\Extension\NormalizeAnchors;
use Local\ContentRenderer\Extension\NumberedCodeBlocks;
use Local\ContentRenderer\Extension\QuotesFormatter;
use Local\ContentRenderer\Extension\RemoveEmptyParagraphs;
use Local\ContentRenderer\Extension\RemoveStyleTags;
use League\CommonMark\Util\HtmlFilter;
use Local\ContentRenderer\Extension\ShortQuotesFormatter;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;
use Tempest\Highlight\CommonMark\HighlightExtension;
use Tempest\Highlight\Highlighter;

class DocumentationRenderer extends Renderer
{
    public function __construct(
        SluggerInterface $slugger = new AsciiSlugger(),
        Highlighter $highlighter = new Highlighter(),
    ) {
        parent::__construct([
            'html_input' => HtmlFilter::ALLOW,
        ]);

        // Fenced and inline code blocks are highlighted while the markdown is
        // converted, so the stored HTML is ready to be served as is.
        $this->env->addExtension(new HighlightExtension($highlighter));

        // …and a fenced block is numbered unless it says otherwise. This one
        // outranks the fenced-code renderer the line above registers; inline
        // code still belongs to that one.
        $this->env->addExtension(new NumberedCodeBlocks($highlighter));

        $this->env->addExtension(new ImportHeaderClasses($slugger));
        $this->env->addExtension(new ShortQuotesFormatter());
        $this->env->addExtension(new QuotesFormatter());
        $this->env->addExtension(new RemoveEmptyParagraphs());
        $this->env->addExtension(new NormalizeAnchors());
        $this->env->addExtension(new RemoveStyleTags());
    }
}
