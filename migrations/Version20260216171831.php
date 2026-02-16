<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260216171831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flag ADD CONSTRAINT FK_D1F4EB9A54177093 FOREIGN KEY (room_id) REFERENCES room (id) NOT DEFERRABLE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D1F4EB9A54177093 ON flag (room_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flag DROP CONSTRAINT FK_D1F4EB9A54177093');
        $this->addSql('DROP INDEX UNIQ_D1F4EB9A54177093');
    }
}
