<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Listener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\PostgreSQLSchemaManager;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Doctrine\ORM\Tools\ToolEvents;

#[AsDoctrineListener(event: ToolEvents::postGenerateSchema)]
final class SchemaMigrationsListener
{
    public function postGenerateSchema(GenerateSchemaEventArgs $event): void
    {
        $schemaManager = $this->getSchemaManager($event);

        if (!$schemaManager instanceof PostgreSQLSchemaManager) {
            return;
        }

        $schema = $event->getSchema();

        foreach ($schemaManager->listSchemaNames() as $namespace) {
            if (!$schema->hasNamespace($namespace)) {
                $schema->createNamespace($namespace);
            }
        }
    }

    private function getSchemaManager(GenerateSchemaEventArgs $event): AbstractSchemaManager
    {
        return $event
            ->getEntityManager()
            ->getConnection()
            ->createSchemaManager();
    }
}
