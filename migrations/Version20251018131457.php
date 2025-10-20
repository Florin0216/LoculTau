<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251018131457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seating__reminder (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, type VARCHAR(128) NOT NULL, status VARCHAR(128) NOT NULL, scheduled_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_44D4B9DE71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE seating__reminder ADD CONSTRAINT FK_44D4B9DE71F7E88B FOREIGN KEY (event_id) REFERENCES seating__event (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seating__reminder DROP FOREIGN KEY FK_44D4B9DE71F7E88B');
        $this->addSql('DROP TABLE seating__reminder');
    }
}
