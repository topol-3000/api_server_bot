<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210706193826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE telegram_user (telegram_id INT NOT NULL, username VARCHAR(40) DEFAULT NULL, full_name VARCHAR(200) DEFAULT NULL, balance INT NOT NULL, is_manager BOOLEAN NOT NULL, is_admin BOOLEAN NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(telegram_id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE telegram_user');
    }
}
