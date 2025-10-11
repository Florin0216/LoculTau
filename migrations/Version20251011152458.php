<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251011152458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seating__reservation ADD qr_code_original_name VARCHAR(255) DEFAULT NULL, ADD qr_code_name VARCHAR(255) DEFAULT NULL, ADD qr_code_mime_type VARCHAR(32) DEFAULT NULL, ADD qr_code_size NUMERIC(10, 0) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seating__reservation DROP qr_code_original_name, DROP qr_code_name, DROP qr_code_mime_type, DROP qr_code_size');
    }
}
