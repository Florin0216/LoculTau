<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251206163120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE seating__seat SET row_no = 1 WHERE section = 'Loja Stanga' AND row_no = 2 AND number BETWEEN 5 AND 8;");
        $this->addSql("DELETE FROM seating__seat WHERE section = 'Loja Dreapta' AND row_no = 2 AND number BETWEEN 5 AND 8;");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
