<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240206221400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create pages table';
    }

    public function up(Schema $schema): void
    {
        // Note: SQLite has no "ENUM" types, so the discriminator column is
        //       stored as a plain string. The set of allowed values is
        //       guarded by a CHECK constraint instead.
        $this->addSql('CREATE TABLE pages (
            id CHAR(36) NOT NULL,
            menu_id CHAR(36) DEFAULT NULL,
            title VARCHAR(255) DEFAULT NULL,
            url VARCHAR(255) NOT NULL,
            type VARCHAR(255) NOT NULL DEFAULT \'document\'
                CHECK (type IN (\'document\', \'link\')),
            content_source CLOB DEFAULT \'\',       /* For "document" type only */
            content_rendered CLOB DEFAULT NULL,     /* For "document" type only */
            sorting_order SMALLINT NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            PRIMARY KEY(id),
            CONSTRAINT FK_2074E575CCD7E912
                FOREIGN KEY (menu_id) REFERENCES menu (id)
                    NOT DEFERRABLE INITIALLY IMMEDIATE
        )');

        $this->addSql('CREATE INDEX IDX_2074E575CCD7E912 ON pages (menu_id)');
        $this->addSql('CREATE INDEX pages_url_idx ON pages (url)');
        $this->addSql('CREATE INDEX page_sorting_order_idx ON pages (sorting_order)');
    }

    public function down(Schema $schema): void
    {
        // Note: SQLite does not support dropping constraints, they are
        //       removed together with the table itself.
        $this->addSql('DROP TABLE pages');
    }
}
