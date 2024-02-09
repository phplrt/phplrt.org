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
        $this->addSql(<<<'SQL'
            CREATE TYPE pages_type AS ENUM ('document', 'link');
            SQL);

        $this->addSql('CREATE TABLE pages (
            id UUID NOT NULL,
            menu_id UUID DEFAULT NULL,
            title VARCHAR(255) DEFAULT NULL,
            url VARCHAR(255) NOT NULL,
            type pages_type NOT NULL DEFAULT \'document\',
            content_source TEXT DEFAULT \'\',       /* For "document" type only */
            content_rendered TEXT DEFAULT NULL,     /* For "document" type only */
            sorting_order SMALLINT NOT NULL DEFAULT 0,
            created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE INDEX IDX_2074E575CCD7E912 ON pages (menu_id)');
        $this->addSql('CREATE INDEX pages_url_idx ON pages (url)');
        $this->addSql('CREATE INDEX page_sorting_order_idx ON pages (sorting_order)');

        $this->addSql('ALTER TABLE pages ADD CONSTRAINT FK_2074E575CCD7E912
            FOREIGN KEY (menu_id) REFERENCES menu (id)
                NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('COMMENT ON COLUMN pages.id IS \'(DC2Type:App\\Domain\\Documentation\\PageId)\'');
        $this->addSql('COMMENT ON COLUMN pages.menu_id IS \'(DC2Type:App\\Domain\\Documentation\\MenuId)\'');
        $this->addSql('COMMENT ON COLUMN pages.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN pages.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pages DROP CONSTRAINT FK_2074E575CCD7E912');
        $this->addSql('DROP TABLE pages');
        $this->addSql('DROP TYPE pages_type');
    }
}
