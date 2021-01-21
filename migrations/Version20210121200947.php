<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121200947 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie CHANGE shortname short_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F5E237E063EE4B093 FOREIGN KEY (name, short_name) REFERENCES country (name, short_name)');
        $this->addSql('CREATE INDEX IDX_1D5EF26F5E237E063EE4B093 ON movie (name, short_name)');
        $this->addSql('ALTER TABLE movie_submission ADD name VARCHAR(255) DEFAULT NULL, ADD short_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE movie_submission ADD CONSTRAINT FK_32F52ACF5E237E063EE4B093 FOREIGN KEY (name, short_name) REFERENCES country (name, short_name)');
        $this->addSql('CREATE INDEX IDX_32F52ACF5E237E063EE4B093 ON movie_submission (name, short_name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26F5E237E063EE4B093');
        $this->addSql('DROP INDEX IDX_1D5EF26F5E237E063EE4B093 ON movie');
        $this->addSql('ALTER TABLE movie CHANGE short_name shortName VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE movie_submission DROP FOREIGN KEY FK_32F52ACF5E237E063EE4B093');
        $this->addSql('DROP INDEX IDX_32F52ACF5E237E063EE4B093 ON movie_submission');
        $this->addSql('ALTER TABLE movie_submission DROP name, DROP short_name');
    }
}
