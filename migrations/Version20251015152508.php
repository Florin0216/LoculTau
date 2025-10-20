<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251015152508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seating__event_sponsor (event_id INT NOT NULL, sponsor_id INT NOT NULL, INDEX IDX_6281B8C71F7E88B (event_id), INDEX IDX_6281B8C12F7FB51 (sponsor_id), PRIMARY KEY(event_id, sponsor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE seating__event_sponsor ADD CONSTRAINT FK_6281B8C71F7E88B FOREIGN KEY (event_id) REFERENCES seating__event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seating__event_sponsor ADD CONSTRAINT FK_6281B8C12F7FB51 FOREIGN KEY (sponsor_id) REFERENCES seating__sponsor (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seating__event_sponsor DROP FOREIGN KEY FK_6281B8C71F7E88B');
        $this->addSql('ALTER TABLE seating__event_sponsor DROP FOREIGN KEY FK_6281B8C12F7FB51');
        $this->addSql('DROP TABLE seating__event_sponsor');
    }
}
