<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190830110749 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE general_area ADD user_id INT NOT NULL, ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE general_area ADD CONSTRAINT FK_2F572D49A76ED395 FOREIGN KEY (user_id) REFERENCES se_user (id)');
        $this->addSql('CREATE INDEX IDX_2F572D49A76ED395 ON general_area (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE general_area DROP FOREIGN KEY FK_2F572D49A76ED395');
        $this->addSql('DROP INDEX IDX_2F572D49A76ED395 ON general_area');
        $this->addSql('ALTER TABLE general_area DROP user_id, DROP created_at');
    }
}
