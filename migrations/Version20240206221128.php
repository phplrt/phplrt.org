<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240206221128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create menus table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE menu (
            id CHAR(36) NOT NULL,
            title VARCHAR(255) NOT NULL,
            sorting_order SMALLINT NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE INDEX menu_sorting_order_idx ON menu (sorting_order)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE menu');
    }
}
