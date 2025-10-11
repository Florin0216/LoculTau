<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251010165024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seating__reservation_seat DROP FOREIGN KEY FK_73919DC2B83297E7');
        $this->addSql('ALTER TABLE seating__reservation_seat DROP FOREIGN KEY FK_73919DC2C1DAFE35');
        $this->addSql('DROP TABLE seating__reservation_seat');
        $this->addSql('ALTER TABLE seating__reservation ADD seat_id INT NOT NULL, ADD uuid VARCHAR(64) NOT NULL, CHANGE event_id event_id INT NOT NULL, CHANGE email email VARCHAR(128) NOT NULL, CHANGE name name VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE seating__reservation ADD CONSTRAINT FK_CEA02952C1DAFE35 FOREIGN KEY (seat_id) REFERENCES seating__seat (id)');
        $this->addSql('CREATE INDEX IDX_CEA02952C1DAFE35 ON seating__reservation (seat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seating__reservation_seat (reservation_id INT NOT NULL, seat_id INT NOT NULL, INDEX IDX_73919DC2B83297E7 (reservation_id), INDEX IDX_73919DC2C1DAFE35 (seat_id), PRIMARY KEY(reservation_id, seat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE seating__reservation_seat ADD CONSTRAINT FK_73919DC2B83297E7 FOREIGN KEY (reservation_id) REFERENCES seating__reservation (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seating__reservation_seat ADD CONSTRAINT FK_73919DC2C1DAFE35 FOREIGN KEY (seat_id) REFERENCES seating__seat (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seating__reservation DROP FOREIGN KEY FK_CEA02952C1DAFE35');
        $this->addSql('DROP INDEX IDX_CEA02952C1DAFE35 ON seating__reservation');
        $this->addSql('ALTER TABLE seating__reservation DROP seat_id, DROP uuid, CHANGE event_id event_id INT DEFAULT NULL, CHANGE email email VARCHAR(64) NOT NULL, CHANGE name name VARCHAR(64) NOT NULL');
    }
}
