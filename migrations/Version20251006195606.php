<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251006195606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation_seat (reservation_id INT NOT NULL, seat_id INT NOT NULL, INDEX IDX_2B65FB0EB83297E7 (reservation_id), INDEX IDX_2B65FB0EC1DAFE35 (seat_id), PRIMARY KEY(reservation_id, seat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation_seat ADD CONSTRAINT FK_2B65FB0EB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_seat ADD CONSTRAINT FK_2B65FB0EC1DAFE35 FOREIGN KEY (seat_id) REFERENCES seat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955C1DAFE35');
        $this->addSql('DROP INDEX IDX_42C84955C1DAFE35 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP seat_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_seat DROP FOREIGN KEY FK_2B65FB0EB83297E7');
        $this->addSql('ALTER TABLE reservation_seat DROP FOREIGN KEY FK_2B65FB0EC1DAFE35');
        $this->addSql('DROP TABLE reservation_seat');
        $this->addSql('ALTER TABLE reservation ADD seat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C1DAFE35 FOREIGN KEY (seat_id) REFERENCES seat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_42C84955C1DAFE35 ON reservation (seat_id)');
    }
}
