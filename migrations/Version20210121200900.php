<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121200900 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE movie_country');
        $this->addSql('DROP TABLE movie_submission_country');
        $this->addSql('ALTER TABLE country MODIFY id INT UNSIGNED NOT NULL');
        $this->addSql('DROP INDEX UNIQ_5373C9665E237E06 ON country');
        $this->addSql('ALTER TABLE country DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE country DROP id, CHANGE short_name short_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE country ADD PRIMARY KEY (name, short_name)');
        $this->addSql('ALTER TABLE movie ADD name VARCHAR(255) DEFAULT NULL, ADD shortName VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F5E237E06C43A885D FOREIGN KEY (name, shortName) REFERENCES country (name, shortName)');
        $this->addSql('CREATE INDEX IDX_1D5EF26F5E237E06C43A885D ON movie (name, shortName)');
        $this->addSql('ALTER TABLE movie_submission ADD name VARCHAR(255) DEFAULT NULL, ADD shortName VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE movie_submission ADD CONSTRAINT FK_32F52ACF5E237E06C43A885D FOREIGN KEY (name, shortName) REFERENCES country (name, shortName)');
        $this->addSql('CREATE INDEX IDX_32F52ACF5E237E06C43A885D ON movie_submission (name, shortName)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movie_country (movie_id INT UNSIGNED NOT NULL, country_id INT UNSIGNED NOT NULL, INDEX IDX_73E58B48F92F3E70 (country_id), INDEX IDX_73E58B488F93B6FC (movie_id), PRIMARY KEY(movie_id, country_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE movie_submission_country (movie_submission_id INT UNSIGNED NOT NULL, country_id INT UNSIGNED NOT NULL, INDEX IDX_71CE781E5E0EDEC4 (movie_submission_id), INDEX IDX_71CE781EF92F3E70 (country_id), PRIMARY KEY(movie_submission_id, country_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE movie_country ADD CONSTRAINT FK_73E58B488F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_country ADD CONSTRAINT FK_73E58B48F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_submission_country ADD CONSTRAINT FK_71CE781E5E0EDEC4 FOREIGN KEY (movie_submission_id) REFERENCES movie_submission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_submission_country ADD CONSTRAINT FK_71CE781EF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE country ADD id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE short_name short_name VARCHAR(32) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5373C9665E237E06 ON country (name)');
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26F5E237E06C43A885D');
        $this->addSql('DROP INDEX IDX_1D5EF26F5E237E06C43A885D ON movie');
        $this->addSql('ALTER TABLE movie DROP name, DROP shortName');
        $this->addSql('ALTER TABLE movie_submission DROP FOREIGN KEY FK_32F52ACF5E237E06C43A885D');
        $this->addSql('DROP INDEX IDX_32F52ACF5E237E06C43A885D ON movie_submission');
        $this->addSql('ALTER TABLE movie_submission DROP name, DROP shortName');
    }
}
