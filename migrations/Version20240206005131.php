<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240206005131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Admin migrations';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE moonshine_user_roles (
            id BIGSERIAL NOT NULL,
            name VARCHAR(255) NOT NULL,
            created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL DEFAULT NOW(),
            updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE moonshine_users (
            id BIGSERIAL NOT NULL,
            moonshine_user_role_id BIGINT DEFAULT 1 NOT NULL,
            email VARCHAR(190) NOT NULL,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            avatar VARCHAR(255) DEFAULT NULL,
            remember_token VARCHAR(100) DEFAULT NULL,
            created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL DEFAULT NOW(),
            updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE UNIQUE INDEX moonshine_users_email_unique ON moonshine_users (email)');

        $this->addSql('CREATE INDEX IDX_3DECC13EB5574C09 ON moonshine_users (moonshine_user_role_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE moonshine_user_roles');
        $this->addSql('DROP TABLE moonshine_users');
    }
}
