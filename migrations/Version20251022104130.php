<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251022104130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app__gallery ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app__gallery ADD CONSTRAINT FK_3BE3F9EA71F7E88B FOREIGN KEY (event_id) REFERENCES seating__event (id)');
        $this->addSql('CREATE INDEX IDX_3BE3F9EA71F7E88B ON app__gallery (event_id)');
        $this->addSql('ALTER TABLE app__gallery_item ADD thumbnail_original_name VARCHAR(255) DEFAULT NULL, ADD thumbnail_name VARCHAR(255) DEFAULT NULL, ADD thumbnail_mime_type VARCHAR(32) DEFAULT NULL, ADD thumbnail_size NUMERIC(10, 0) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app__gallery_item DROP thumbnail_original_name, DROP thumbnail_name, DROP thumbnail_mime_type, DROP thumbnail_size');
        $this->addSql('ALTER TABLE app__gallery DROP FOREIGN KEY FK_3BE3F9EA71F7E88B');
        $this->addSql('DROP INDEX IDX_3BE3F9EA71F7E88B ON app__gallery');
        $this->addSql('ALTER TABLE app__gallery DROP event_id');
    }
}
