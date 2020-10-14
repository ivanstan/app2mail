<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201014090854 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', email LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE submission (id INT AUTO_INCREMENT NOT NULL, application_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created INT NOT NULL, data JSON DEFAULT NULL, INDEX IDX_DB055AF33E030ACD (application_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE submission ADD CONSTRAINT FK_DB055AF33E030ACD FOREIGN KEY (application_id) REFERENCES application (uuid)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE submission DROP FOREIGN KEY FK_DB055AF33E030ACD');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE submission');
    }
}
