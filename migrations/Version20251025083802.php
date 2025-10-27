<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251025083802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seating__reservation DROP INDEX IDX_CEA02952C1DAFE35, ADD UNIQUE INDEX UNIQ_CEA02952C1DAFE35 (seat_id)');
        $this->addSql('ALTER TABLE seating__sponsor ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD image_original_name VARCHAR(255) DEFAULT NULL, ADD image_name VARCHAR(255) DEFAULT NULL, ADD image_mime_type VARCHAR(32) DEFAULT NULL, ADD image_size NUMERIC(10, 0) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seating__sponsor DROP updated_at, DROP image_original_name, DROP image_name, DROP image_mime_type, DROP image_size');
        $this->addSql('ALTER TABLE seating__reservation DROP INDEX UNIQ_CEA02952C1DAFE35, ADD INDEX IDX_CEA02952C1DAFE35 (seat_id)');
    }
}
