<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Bundle\MonologBundle\Tests\DependencyInjection\Compiler\AddProcessorsPassTest;
use Symfony\Component\Security\Guard\Provider\GuardAuthenticationProvider;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190829124209 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE date_period ADD user_id INT NOT NULL, ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE date_period ADD CONSTRAINT FK_210C212DA76ED395 FOREIGN KEY (user_id) REFERENCES se_user (id)');
        $this->addSql('CREATE INDEX IDX_210C212DA76ED395 ON date_period (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE date_period DROP FOREIGN KEY FK_210C212DA76ED395');
        $this->addSql('DROP INDEX IDX_210C212DA76ED395 ON date_period');
        $this->addSql('ALTER TABLE date_period DROP user_id, DROP created_at');
    }
}
