<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201111145640 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recaptcha (id INT AUTO_INCREMENT NOT NULL, site_key VARCHAR(255) NOT NULL, secret_key VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE application ADD recaptcha_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC16A20617E FOREIGN KEY (recaptcha_id) REFERENCES recaptcha (id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC16A20617E ON application (recaptcha_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC16A20617E');
        $this->addSql('DROP TABLE recaptcha');
        $this->addSql('DROP INDEX IDX_A45BDDC16A20617E ON application');
        $this->addSql('ALTER TABLE application DROP recaptcha_id');
    }
}
