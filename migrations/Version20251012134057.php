<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251012134057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seating__reservation ADD claimed_by_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE seating__reservation ADD CONSTRAINT FK_CEA02952F67E7A38 FOREIGN KEY (claimed_by_id) REFERENCES user__user (id)');
        $this->addSql('CREATE INDEX IDX_CEA02952F67E7A38 ON seating__reservation (claimed_by_id)');
        $this->addSql('ALTER TABLE user__user ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user__user ADD is_disabled TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user__user DROP is_disabled');
        $this->addSql('ALTER TABLE seating__reservation DROP FOREIGN KEY FK_CEA02952F67E7A38');
        $this->addSql('DROP INDEX IDX_CEA02952F67E7A38 ON seating__reservation');
        $this->addSql('ALTER TABLE seating__reservation DROP claimed_by_id, DROP created_at, CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user__user DROP created_at, DROP updated_at');
    }
}
