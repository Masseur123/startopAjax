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
final class Version20190829120335 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, branch_id INT NOT NULL, code VARCHAR(20) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_2D5B0234A76ED395 (user_id), INDEX IDX_2D5B0234DCD6CC49 (branch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, branch_id INT NOT NULL, rappel_id INT NOT NULL, created_at DATETIME NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526CDCD6CC49 (branch_id), INDEX IDX_9474526C7A752E96 (rappel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE custom_price (id INT AUTO_INCREMENT NOT NULL, housing_type_id INT NOT NULL, pricing_plan_id INT NOT NULL, day DATETIME NOT NULL, price NUMERIC(12, 2) NOT NULL, INDEX IDX_9C5DAB07CB1EF5B (housing_type_id), INDEX IDX_9C5DAB029628C71 (pricing_plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE date_period (id INT AUTO_INCREMENT NOT NULL, season_id INT NOT NULL, date_to DATETIME NOT NULL, date_from DATETIME NOT NULL, INDEX IDX_210C212D4EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE extra_meal (extra_id INT NOT NULL, meal_id INT NOT NULL, INDEX IDX_A8B4D822B959FC6 (extra_id), INDEX IDX_A8B4D82639666D6 (meal_id), PRIMARY KEY(extra_id, meal_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE general_area (id INT AUTO_INCREMENT NOT NULL, tax_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_2F572D49B2A824D8 (tax_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pension (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, file VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pricing_plan_housing_type (pricing_plan_id INT NOT NULL, housing_type_id INT NOT NULL, INDEX IDX_C544B70029628C71 (pricing_plan_id), INDEX IDX_C544B7007CB1EF5B (housing_type_id), PRIMARY KEY(pricing_plan_id, housing_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, roomtax_id INT NOT NULL, citytax_id INT NOT NULL, code VARCHAR(20) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_8BF21CDE722C69EF (roomtax_id), INDEX IDX_8BF21CDE7B1A6F13 (citytax_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rappel (id INT AUTO_INCREMENT NOT NULL, reservation_id INT NOT NULL, user_id INT NOT NULL, branch_id INT NOT NULL, expired_at DATETIME NOT NULL, INDEX IDX_303A29C9B83297E7 (reservation_id), INDEX IDX_303A29C9A76ED395 (user_id), INDEX IDX_303A29C9DCD6CC49 (branch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restriction (id INT AUTO_INCREMENT NOT NULL, housingtype_id INT NOT NULL, day DATETIME NOT NULL, INDEX IDX_7A999BCEA7C8AA66 (housingtype_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restriction_configuration (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restriction_configuration_restriction_type (restriction_configuration_id INT NOT NULL, restriction_type_id INT NOT NULL, INDEX IDX_AE632BB7D812E774 (restriction_configuration_id), INDEX IDX_AE632BB7BAECA5C8 (restriction_type_id), PRIMARY KEY(restriction_configuration_id, restriction_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restriction_configuration_restriction (restriction_configuration_id INT NOT NULL, restriction_id INT NOT NULL, INDEX IDX_2EF49F3FD812E774 (restriction_configuration_id), INDEX IDX_2EF49F3FE6160631 (restriction_id), PRIMARY KEY(restriction_configuration_id, restriction_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restriction_type (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, branch_id INT NOT NULL, created_at DATETIME NOT NULL, code VARCHAR(20) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_CA8D052EA76ED395 (user_id), INDEX IDX_CA8D052EDCD6CC49 (branch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_pricing_plan (tag_id INT NOT NULL, pricing_plan_id INT NOT NULL, INDEX IDX_565A7AC1BAD26311 (tag_id), INDEX IDX_565A7AC129628C71 (pricing_plan_id), PRIMARY KEY(tag_id, pricing_plan_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_plan_configuration (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_plan_configuration_tag (tag_plan_configuration_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_100940475356F548 (tag_plan_configuration_id), INDEX IDX_10094047BAD26311 (tag_id), PRIMARY KEY(tag_plan_configuration_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_plan_configuration_pricing_plan (tag_plan_configuration_id INT NOT NULL, pricing_plan_id INT NOT NULL, INDEX IDX_EB9D84135356F548 (tag_plan_configuration_id), INDEX IDX_EB9D841329628C71 (pricing_plan_id), PRIMARY KEY(tag_plan_configuration_id, pricing_plan_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234A76ED395 FOREIGN KEY (user_id) REFERENCES se_user (id)');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234DCD6CC49 FOREIGN KEY (branch_id) REFERENCES se_branch (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES se_user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CDCD6CC49 FOREIGN KEY (branch_id) REFERENCES se_branch (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7A752E96 FOREIGN KEY (rappel_id) REFERENCES rappel (id)');
        $this->addSql('ALTER TABLE custom_price ADD CONSTRAINT FK_9C5DAB07CB1EF5B FOREIGN KEY (housing_type_id) REFERENCES ho_housing_type (id)');
        $this->addSql('ALTER TABLE custom_price ADD CONSTRAINT FK_9C5DAB029628C71 FOREIGN KEY (pricing_plan_id) REFERENCES ho_pricing_plan (id)');
        $this->addSql('ALTER TABLE date_period ADD CONSTRAINT FK_210C212D4EC001D1 FOREIGN KEY (season_id) REFERENCES ho_season (id)');
        $this->addSql('ALTER TABLE extra_meal ADD CONSTRAINT FK_A8B4D822B959FC6 FOREIGN KEY (extra_id) REFERENCES ho_extra (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE extra_meal ADD CONSTRAINT FK_A8B4D82639666D6 FOREIGN KEY (meal_id) REFERENCES ho_meal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE general_area ADD CONSTRAINT FK_2F572D49B2A824D8 FOREIGN KEY (tax_id) REFERENCES co_tax (id)');
        $this->addSql('ALTER TABLE pricing_plan_housing_type ADD CONSTRAINT FK_C544B70029628C71 FOREIGN KEY (pricing_plan_id) REFERENCES ho_pricing_plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pricing_plan_housing_type ADD CONSTRAINT FK_C544B7007CB1EF5B FOREIGN KEY (housing_type_id) REFERENCES ho_housing_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE722C69EF FOREIGN KEY (roomtax_id) REFERENCES ho_housing (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE7B1A6F13 FOREIGN KEY (citytax_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE rappel ADD CONSTRAINT FK_303A29C9B83297E7 FOREIGN KEY (reservation_id) REFERENCES ho_reservation (id)');
        $this->addSql('ALTER TABLE rappel ADD CONSTRAINT FK_303A29C9A76ED395 FOREIGN KEY (user_id) REFERENCES se_user (id)');
        $this->addSql('ALTER TABLE rappel ADD CONSTRAINT FK_303A29C9DCD6CC49 FOREIGN KEY (branch_id) REFERENCES se_branch (id)');
        $this->addSql('ALTER TABLE restriction ADD CONSTRAINT FK_7A999BCEA7C8AA66 FOREIGN KEY (housingtype_id) REFERENCES ho_housing_type (id)');
        $this->addSql('ALTER TABLE restriction_configuration_restriction_type ADD CONSTRAINT FK_AE632BB7D812E774 FOREIGN KEY (restriction_configuration_id) REFERENCES restriction_configuration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restriction_configuration_restriction_type ADD CONSTRAINT FK_AE632BB7BAECA5C8 FOREIGN KEY (restriction_type_id) REFERENCES restriction_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restriction_configuration_restriction ADD CONSTRAINT FK_2EF49F3FD812E774 FOREIGN KEY (restriction_configuration_id) REFERENCES restriction_configuration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restriction_configuration_restriction ADD CONSTRAINT FK_2EF49F3FE6160631 FOREIGN KEY (restriction_id) REFERENCES restriction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restriction_type ADD CONSTRAINT FK_CA8D052EA76ED395 FOREIGN KEY (user_id) REFERENCES se_user (id)');
        $this->addSql('ALTER TABLE restriction_type ADD CONSTRAINT FK_CA8D052EDCD6CC49 FOREIGN KEY (branch_id) REFERENCES se_branch (id)');
        $this->addSql('ALTER TABLE tag_pricing_plan ADD CONSTRAINT FK_565A7AC1BAD26311 FOREIGN KEY (tag_id) REFERENCES ho_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_pricing_plan ADD CONSTRAINT FK_565A7AC129628C71 FOREIGN KEY (pricing_plan_id) REFERENCES ho_pricing_plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_plan_configuration_tag ADD CONSTRAINT FK_100940475356F548 FOREIGN KEY (tag_plan_configuration_id) REFERENCES tag_plan_configuration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_plan_configuration_tag ADD CONSTRAINT FK_10094047BAD26311 FOREIGN KEY (tag_id) REFERENCES ho_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_plan_configuration_pricing_plan ADD CONSTRAINT FK_EB9D84135356F548 FOREIGN KEY (tag_plan_configuration_id) REFERENCES tag_plan_configuration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_plan_configuration_pricing_plan ADD CONSTRAINT FK_EB9D841329628C71 FOREIGN KEY (pricing_plan_id) REFERENCES ho_pricing_plan (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE ho_extra_tax');
        $this->addSql('ALTER TABLE co_credit_card ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ho_extra DROP FOREIGN KEY FK_6D17183181BB44E6');
        $this->addSql('DROP INDEX IDX_6D17183181BB44E6 ON ho_extra');
        $this->addSql('ALTER TABLE ho_extra ADD general_area_id INT NOT NULL, ADD code VARCHAR(255) NOT NULL, ADD property VARCHAR(255) NOT NULL, DROP extra_category_id');
        $this->addSql('ALTER TABLE ho_extra ADD CONSTRAINT FK_6D171831CC2FEDE8 FOREIGN KEY (general_area_id) REFERENCES general_area (id)');
        $this->addSql('CREATE INDEX IDX_6D171831CC2FEDE8 ON ho_extra (general_area_id)');
        $this->addSql('ALTER TABLE ho_housing_option ADD housingtype_id INT NOT NULL');
        $this->addSql('ALTER TABLE ho_housing_option ADD CONSTRAINT FK_212A9C3EA7C8AA66 FOREIGN KEY (housingtype_id) REFERENCES ho_housing_type (id)');
        $this->addSql('CREATE INDEX IDX_212A9C3EA7C8AA66 ON ho_housing_option (housingtype_id)');
        $this->addSql('ALTER TABLE co_payment_method ADD is_valid TINYINT(1) DEFAULT NULL, ADD commission INT NOT NULL, ADD defaultdelay INT NOT NULL');
        $this->addSql('ALTER TABLE ho_pricing DROP FOREIGN KEY FK_56FBF0A08B39A426');
        $this->addSql('ALTER TABLE ho_pricing DROP FOREIGN KEY FK_56FBF0A0AD5873E3');
        $this->addSql('DROP INDEX IDX_56FBF0A0AD5873E3 ON ho_pricing');
        $this->addSql('DROP INDEX IDX_56FBF0A08B39A426 ON ho_pricing');
        $this->addSql('ALTER TABLE ho_pricing ADD season_id INT NOT NULL, ADD name VARCHAR(255) NOT NULL, ADD simple_price NUMERIC(12, 2) DEFAULT NULL, ADD extra_adult NUMERIC(12, 2) DEFAULT NULL, ADD extra_child NUMERIC(12, 2) DEFAULT NULL, ADD extra_baby NUMERIC(12, 2) DEFAULT NULL, ADD date DATETIME NOT NULL, DROP housing_id, DROP pricingplan_id, DROP is_enabled, DROP p_min, DROP p_max, DROP ordering');
        $this->addSql('ALTER TABLE ho_pricing ADD CONSTRAINT FK_56FBF0A04EC001D1 FOREIGN KEY (season_id) REFERENCES ho_season (id)');
        $this->addSql('CREATE INDEX IDX_56FBF0A04EC001D1 ON ho_pricing (season_id)');
        $this->addSql('ALTER TABLE ho_pricing_plan ADD pricing_id INT NOT NULL, ADD reduction NUMERIC(12, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE ho_pricing_plan ADD CONSTRAINT FK_234EC4B48864AF73 FOREIGN KEY (pricing_id) REFERENCES ho_pricing (id)');
        $this->addSql('CREATE INDEX IDX_234EC4B48864AF73 ON ho_pricing_plan (pricing_id)');
        $this->addSql('ALTER TABLE co_region ADD description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ho_season DROP start_at, DROP end_at');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE7B1A6F13');
        $this->addSql('ALTER TABLE ho_extra DROP FOREIGN KEY FK_6D171831CC2FEDE8');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7A752E96');
        $this->addSql('ALTER TABLE restriction_configuration_restriction DROP FOREIGN KEY FK_2EF49F3FE6160631');
        $this->addSql('ALTER TABLE restriction_configuration_restriction_type DROP FOREIGN KEY FK_AE632BB7D812E774');
        $this->addSql('ALTER TABLE restriction_configuration_restriction DROP FOREIGN KEY FK_2EF49F3FD812E774');
        $this->addSql('ALTER TABLE restriction_configuration_restriction_type DROP FOREIGN KEY FK_AE632BB7BAECA5C8');
        $this->addSql('ALTER TABLE tag_plan_configuration_tag DROP FOREIGN KEY FK_100940475356F548');
        $this->addSql('ALTER TABLE tag_plan_configuration_pricing_plan DROP FOREIGN KEY FK_EB9D84135356F548');
        $this->addSql('CREATE TABLE ho_extra_tax (extra_id INT NOT NULL, tax_id INT NOT NULL, INDEX IDX_AFC857F32B959FC6 (extra_id), INDEX IDX_AFC857F3B2A824D8 (tax_id), PRIMARY KEY(extra_id, tax_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ho_extra_tax ADD CONSTRAINT FK_AFC857F32B959FC6 FOREIGN KEY (extra_id) REFERENCES ho_extra (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ho_extra_tax ADD CONSTRAINT FK_AFC857F3B2A824D8 FOREIGN KEY (tax_id) REFERENCES co_tax (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE custom_price');
        $this->addSql('DROP TABLE date_period');
        $this->addSql('DROP TABLE extra_meal');
        $this->addSql('DROP TABLE general_area');
        $this->addSql('DROP TABLE pension');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE pricing_plan_housing_type');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE rappel');
        $this->addSql('DROP TABLE restriction');
        $this->addSql('DROP TABLE restriction_configuration');
        $this->addSql('DROP TABLE restriction_configuration_restriction_type');
        $this->addSql('DROP TABLE restriction_configuration_restriction');
        $this->addSql('DROP TABLE restriction_type');
        $this->addSql('DROP TABLE tag_pricing_plan');
        $this->addSql('DROP TABLE tag_plan_configuration');
        $this->addSql('DROP TABLE tag_plan_configuration_tag');
        $this->addSql('DROP TABLE tag_plan_configuration_pricing_plan');
        $this->addSql('ALTER TABLE co_credit_card DROP type');
        $this->addSql('ALTER TABLE co_payment_method DROP is_valid, DROP commission, DROP defaultdelay');
        $this->addSql('ALTER TABLE co_region DROP description');
        $this->addSql('DROP INDEX IDX_6D171831CC2FEDE8 ON ho_extra');
        $this->addSql('ALTER TABLE ho_extra ADD extra_category_id INT DEFAULT NULL, DROP general_area_id, DROP code, DROP property');
        $this->addSql('ALTER TABLE ho_extra ADD CONSTRAINT FK_6D17183181BB44E6 FOREIGN KEY (extra_category_id) REFERENCES ho_extra_category (id)');
        $this->addSql('CREATE INDEX IDX_6D17183181BB44E6 ON ho_extra (extra_category_id)');
        $this->addSql('ALTER TABLE ho_housing_option DROP FOREIGN KEY FK_212A9C3EA7C8AA66');
        $this->addSql('DROP INDEX IDX_212A9C3EA7C8AA66 ON ho_housing_option');
        $this->addSql('ALTER TABLE ho_housing_option DROP housingtype_id');
        $this->addSql('ALTER TABLE ho_pricing DROP FOREIGN KEY FK_56FBF0A04EC001D1');
        $this->addSql('DROP INDEX IDX_56FBF0A04EC001D1 ON ho_pricing');
        $this->addSql('ALTER TABLE ho_pricing ADD housing_id INT DEFAULT NULL, ADD pricingplan_id INT DEFAULT NULL, ADD is_enabled TINYINT(1) NOT NULL, ADD p_min NUMERIC(10, 0) DEFAULT NULL, ADD p_max NUMERIC(10, 0) DEFAULT NULL, ADD ordering INT DEFAULT NULL, DROP season_id, DROP name, DROP simple_price, DROP extra_adult, DROP extra_child, DROP extra_baby, DROP date');
        $this->addSql('ALTER TABLE ho_pricing ADD CONSTRAINT FK_56FBF0A08B39A426 FOREIGN KEY (pricingplan_id) REFERENCES ho_pricing_plan (id)');
        $this->addSql('ALTER TABLE ho_pricing ADD CONSTRAINT FK_56FBF0A0AD5873E3 FOREIGN KEY (housing_id) REFERENCES ho_housing (id)');
        $this->addSql('CREATE INDEX IDX_56FBF0A0AD5873E3 ON ho_pricing (housing_id)');
        $this->addSql('CREATE INDEX IDX_56FBF0A08B39A426 ON ho_pricing (pricingplan_id)');
        $this->addSql('ALTER TABLE ho_pricing_plan DROP FOREIGN KEY FK_234EC4B48864AF73');
        $this->addSql('DROP INDEX IDX_234EC4B48864AF73 ON ho_pricing_plan');
        $this->addSql('ALTER TABLE ho_pricing_plan DROP pricing_id, DROP reduction');
        $this->addSql('ALTER TABLE ho_season ADD start_at DATE NOT NULL, ADD end_at DATE NOT NULL');
    }
}
