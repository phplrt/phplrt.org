<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240206221654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create search index table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE search_index (
            id CHAR(36) NOT NULL,
            page_id CHAR(36) NOT NULL,
            title VARCHAR(255) NOT NULL,
            level INT NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            PRIMARY KEY(id),
            CONSTRAINT FK_B446A4E81645DEA9
                FOREIGN KEY (page_id) REFERENCES pages (id)
                    NOT DEFERRABLE INITIALLY IMMEDIATE
        )');

        $this->addSql('CREATE INDEX IDX_B446A4E81645DEA9 ON search_index (page_id)');
    }

    public function down(Schema $schema): void
    {
        // Note: SQLite does not support dropping constraints, they are
        //       removed together with the table itself.
        $this->addSql('DROP TABLE search_index');
    }
}
