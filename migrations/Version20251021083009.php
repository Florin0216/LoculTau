<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251021083009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app__gallery (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app__gallery_item (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, title VARCHAR(128) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', image_original_name VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_mime_type VARCHAR(32) DEFAULT NULL, image_size NUMERIC(10, 0) DEFAULT NULL, INDEX IDX_4C2252974E7AF8F (gallery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app__gallery_item ADD CONSTRAINT FK_4C2252974E7AF8F FOREIGN KEY (gallery_id) REFERENCES app__gallery (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app__gallery_item DROP FOREIGN KEY FK_4C2252974E7AF8F');
        $this->addSql('DROP TABLE app__gallery');
        $this->addSql('DROP TABLE app__gallery_item');
    }
}
