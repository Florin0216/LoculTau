<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251008091316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seating__event (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, title VARCHAR(128) NOT NULL, INDEX IDX_1FD185EA54177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seating__reservation (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, email VARCHAR(64) NOT NULL, name VARCHAR(64) NOT NULL, INDEX IDX_CEA0295271F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seating__reservation_seat (reservation_id INT NOT NULL, seat_id INT NOT NULL, INDEX IDX_73919DC2B83297E7 (reservation_id), INDEX IDX_73919DC2C1DAFE35 (seat_id), PRIMARY KEY(reservation_id, seat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seating__room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seating__seat (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, row_no INT NOT NULL, number INT NOT NULL, section VARCHAR(32) NOT NULL, INDEX IDX_F670DD9554177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE seating__event ADD CONSTRAINT FK_1FD185EA54177093 FOREIGN KEY (room_id) REFERENCES seating__room (id)');
        $this->addSql('ALTER TABLE seating__reservation ADD CONSTRAINT FK_CEA0295271F7E88B FOREIGN KEY (event_id) REFERENCES seating__event (id)');
        $this->addSql('ALTER TABLE seating__reservation_seat ADD CONSTRAINT FK_73919DC2B83297E7 FOREIGN KEY (reservation_id) REFERENCES seating__reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seating__reservation_seat ADD CONSTRAINT FK_73919DC2C1DAFE35 FOREIGN KEY (seat_id) REFERENCES seating__seat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seating__seat ADD CONSTRAINT FK_F670DD9554177093 FOREIGN KEY (room_id) REFERENCES seating__room (id)');
        $this->addSql('ALTER TABLE seat DROP FOREIGN KEY FK_3D5C366654177093');
        $this->addSql('ALTER TABLE reservation_seat DROP FOREIGN KEY FK_2B65FB0EB83297E7');
        $this->addSql('ALTER TABLE reservation_seat DROP FOREIGN KEY FK_2B65FB0EC1DAFE35');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495571F7E88B');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA754177093');
        $this->addSql('DROP TABLE seat');
        $this->addSql('DROP TABLE reservation_seat');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE event');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seat (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, row_no INT NOT NULL, number INT NOT NULL, status VARCHAR(16) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, section VARCHAR(32) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_3D5C366654177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation_seat (reservation_id INT NOT NULL, seat_id INT NOT NULL, INDEX IDX_2B65FB0EB83297E7 (reservation_id), INDEX IDX_2B65FB0EC1DAFE35 (seat_id), PRIMARY KEY(reservation_id, seat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, email VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_42C8495571F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, `rows` INT NOT NULL, cols INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, title VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_3BAE0AA754177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE seat ADD CONSTRAINT FK_3D5C366654177093 FOREIGN KEY (room_id) REFERENCES room (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE reservation_seat ADD CONSTRAINT FK_2B65FB0EB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_seat ADD CONSTRAINT FK_2B65FB0EC1DAFE35 FOREIGN KEY (seat_id) REFERENCES seat (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495571F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA754177093 FOREIGN KEY (room_id) REFERENCES room (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE seating__event DROP FOREIGN KEY FK_1FD185EA54177093');
        $this->addSql('ALTER TABLE seating__reservation DROP FOREIGN KEY FK_CEA0295271F7E88B');
        $this->addSql('ALTER TABLE seating__reservation_seat DROP FOREIGN KEY FK_73919DC2B83297E7');
        $this->addSql('ALTER TABLE seating__reservation_seat DROP FOREIGN KEY FK_73919DC2C1DAFE35');
        $this->addSql('ALTER TABLE seating__seat DROP FOREIGN KEY FK_F670DD9554177093');
        $this->addSql('DROP TABLE seating__event');
        $this->addSql('DROP TABLE seating__reservation');
        $this->addSql('DROP TABLE seating__reservation_seat');
        $this->addSql('DROP TABLE seating__room');
        $this->addSql('DROP TABLE seating__seat');
    }
}
