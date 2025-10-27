<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251027072247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            INSERT INTO app__page (title, slug, section, content)
            VALUES
            ('Acasa - ', 'homepage', 'Text principal', '<h1>Welcome to our homepage!</h1><p>This is your main entry point.</p>')
        ");

        $this->addSql("
            INSERT INTO app__page (title, slug, section, content) VALUES
            ('About - Our Story', 'about', 'Text principal', '<p>This is our story section.</p>'),
            ('About - Card 1', 'about', 'card_1', '<p>Card 1 description and details.</p>'),
            ('About - Card 2', 'about', 'card_2', '<p>Card 2 description and details.</p>'),
            ('About - Card 3', 'about', 'card_3', '<p>Card 3 description and details.</p>')
        ");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
