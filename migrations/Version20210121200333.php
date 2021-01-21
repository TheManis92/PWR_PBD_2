<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121200333 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE test1 (name VARCHAR(255) NOT NULL, shortname VARCHAR(255) NOT NULL, PRIMARY KEY(name, shortname)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test2 (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, shortname VARCHAR(255) DEFAULT NULL, INDEX IDX_13BB8D585E237E0664082763 (name, shortname), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE test2 ADD CONSTRAINT FK_13BB8D585E237E0664082763 FOREIGN KEY (name, shortname) REFERENCES test1 (name, shortname)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE test2 DROP FOREIGN KEY FK_13BB8D585E237E0664082763');
        $this->addSql('DROP TABLE test1');
        $this->addSql('DROP TABLE test2');
    }
}
