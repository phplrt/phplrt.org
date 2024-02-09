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
            id UUID NOT NULL,
            title VARCHAR(255) NOT NULL,
            sorting_order SMALLINT NOT NULL DEFAULT 0,
            created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE INDEX menu_sorting_order_idx ON menu (sorting_order)');

        $this->addSql('COMMENT ON COLUMN menu.id IS \'(DC2Type:App\\Domain\\Documentation\\MenuId)\'');
        $this->addSql('COMMENT ON COLUMN menu.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN menu.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE menu');
    }
}
