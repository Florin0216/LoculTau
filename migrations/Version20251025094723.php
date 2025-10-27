<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251025094723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seating__reservation DROP INDEX UNIQ_CEA02952C1DAFE35, ADD INDEX IDX_CEA02952C1DAFE35 (seat_id)');
        $this->addSql('CREATE UNIQUE INDEX seating__unique_event_seat ON seating__reservation (event_id, sponsor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seating__reservation DROP INDEX IDX_CEA02952C1DAFE35, ADD UNIQUE INDEX UNIQ_CEA02952C1DAFE35 (seat_id)');
        $this->addSql('DROP INDEX seating__unique_event_seat ON seating__reservation');
    }
}
