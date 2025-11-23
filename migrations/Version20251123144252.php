<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251123144252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seating__seat ADD sponsor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE seating__seat ADD CONSTRAINT FK_F670DD9512F7FB51 FOREIGN KEY (sponsor_id) REFERENCES seating__sponsor (id)');
        $this->addSql('CREATE INDEX IDX_F670DD9512F7FB51 ON seating__seat (sponsor_id)');
        $this->addSql('ALTER TABLE seating__sponsor ADD color VARCHAR(128) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seating__seat DROP FOREIGN KEY FK_F670DD9512F7FB51');
        $this->addSql('DROP INDEX IDX_F670DD9512F7FB51 ON seating__seat');
        $this->addSql('ALTER TABLE seating__seat DROP sponsor_id');
        $this->addSql('ALTER TABLE seating__sponsor DROP color');
    }
}
