<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190902083726 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tag_plan_configuration ADD user_id INT NOT NULL, ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tag_plan_configuration ADD CONSTRAINT FK_FC3053D9A76ED395 FOREIGN KEY (user_id) REFERENCES se_user (id)');
        $this->addSql('CREATE INDEX IDX_FC3053D9A76ED395 ON tag_plan_configuration (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tag_plan_configuration DROP FOREIGN KEY FK_FC3053D9A76ED395');
        $this->addSql('DROP INDEX IDX_FC3053D9A76ED395 ON tag_plan_configuration');
        $this->addSql('ALTER TABLE tag_plan_configuration DROP user_id, DROP created_at');
    }
}
