<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251014130422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seating__sponsor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, uuid VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE seating__reservation ADD sponsor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE seating__reservation ADD CONSTRAINT FK_CEA0295212F7FB51 FOREIGN KEY (sponsor_id) REFERENCES seating__seat (id)');
        $this->addSql('CREATE INDEX IDX_CEA0295212F7FB51 ON seating__reservation (sponsor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE seating__sponsor');
        $this->addSql('ALTER TABLE seating__reservation DROP FOREIGN KEY FK_CEA0295212F7FB51');
        $this->addSql('DROP INDEX IDX_CEA0295212F7FB51 ON seating__reservation');
        $this->addSql('ALTER TABLE seating__reservation DROP sponsor_id');
    }
}
