<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Subscriber;

use App\Domain\Documentation\Document;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Local\ContentRenderer\ContentRendererInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(Events::prePersist)]
#[AsDoctrineListener(Events::preUpdate)]
final readonly class RenderPageSubscriber
{
    public function __construct(
        private ContentRendererInterface $renderer,
    ) {}

    public function prePersist(PrePersistEventArgs $events): void
    {
        $this->render($events->getObject());
    }

    public function preUpdate(PreUpdateEventArgs $events): void
    {
        $this->render($events->getObject());
    }

    private function render(object $entity): void
    {
        if ($entity instanceof Document) {
            $content = $entity->getContent();

            if (!$content->isRendered()) {
                $html = $this->renderer->render($content->getRawContent());

                $content->render($html);
            }
        }
    }
}
