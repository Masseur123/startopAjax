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
final class Version20190802115042 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE com_quotation ADD CONSTRAINT FK_94979C86D0D5425D FOREIGN KEY (supply_request_id) REFERENCES com_supply_request (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE com_quotation DROP FOREIGN KEY FK_94979C86D0D5425D');
    }
}
