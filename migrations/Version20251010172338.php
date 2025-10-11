<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251010172338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user__user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(128) NOT NULL, username VARCHAR(128) NOT NULL, first_name VARCHAR(64) DEFAULT NULL, last_name VARCHAR(64) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, locale VARCHAR(8) DEFAULT NULL, UNIQUE INDEX UNIQ_32745D0AE7927C74 (email), UNIQUE INDEX UNIQ_32745D0AF85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user__user');
    }
}
