<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210122183644 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_835033F85E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lang (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_310984625E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie (id INT UNSIGNED AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, plot LONGTEXT DEFAULT NULL, year SMALLINT DEFAULT NULL, rating DOUBLE PRECISION DEFAULT NULL, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_cast (movie_id INT UNSIGNED NOT NULL, person_id INT UNSIGNED NOT NULL, INDEX IDX_E1DE98FB8F93B6FC (movie_id), INDEX IDX_E1DE98FB217BBB47 (person_id), PRIMARY KEY(movie_id, person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_genre (movie_id INT UNSIGNED NOT NULL, genre_id INT UNSIGNED NOT NULL, INDEX IDX_FD1229648F93B6FC (movie_id), INDEX IDX_FD1229644296D31F (genre_id), PRIMARY KEY(movie_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_country (movie_id INT UNSIGNED NOT NULL, country_id INT UNSIGNED NOT NULL, INDEX IDX_73E58B488F93B6FC (movie_id), INDEX IDX_73E58B48F92F3E70 (country_id), PRIMARY KEY(movie_id, country_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_lang (movie_id INT UNSIGNED NOT NULL, lang_id INT UNSIGNED NOT NULL, INDEX IDX_C26FA56F8F93B6FC (movie_id), INDEX IDX_C26FA56FB213FA4 (lang_id), PRIMARY KEY(movie_id, lang_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_request (id INT UNSIGNED AUTO_INCREMENT NOT NULL, movie_submission_id INT UNSIGNED DEFAULT NULL, user_id INT UNSIGNED NOT NULL, current_movie_id INT UNSIGNED DEFAULT NULL, action ENUM(\'add\', \'remove\', \'edit\') NOT NULL COMMENT \'(DC2Type:enum_movie_request_action)\', created DATETIME NOT NULL, UNIQUE INDEX UNIQ_1B01CDB15E0EDEC4 (movie_submission_id), INDEX IDX_1B01CDB1A76ED395 (user_id), INDEX IDX_1B01CDB112EBCCD6 (current_movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_submission (id INT UNSIGNED AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, plot LONGTEXT DEFAULT NULL, year SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_submissions_cast (movie_submission_id INT UNSIGNED NOT NULL, person_id INT UNSIGNED NOT NULL, INDEX IDX_4853AC935E0EDEC4 (movie_submission_id), INDEX IDX_4853AC93217BBB47 (person_id), PRIMARY KEY(movie_submission_id, person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_submission_genre (movie_submission_id INT UNSIGNED NOT NULL, genre_id INT UNSIGNED NOT NULL, INDEX IDX_F4F093CB5E0EDEC4 (movie_submission_id), INDEX IDX_F4F093CB4296D31F (genre_id), PRIMARY KEY(movie_submission_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_submission_country (movie_submission_id INT UNSIGNED NOT NULL, country_id INT UNSIGNED NOT NULL, INDEX IDX_71CE781E5E0EDEC4 (movie_submission_id), INDEX IDX_71CE781EF92F3E70 (country_id), PRIMARY KEY(movie_submission_id, country_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_submission_lang (movie_submission_id INT UNSIGNED NOT NULL, lang_id INT UNSIGNED NOT NULL, INDEX IDX_969921635E0EDEC4 (movie_submission_id), INDEX IDX_96992163B213FA4 (lang_id), PRIMARY KEY(movie_submission_id, lang_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT UNSIGNED AUTO_INCREMENT NOT NULL, movie_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, rating SMALLINT NOT NULL, title VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, is_accepted TINYINT(1) NOT NULL, created DATETIME NOT NULL, INDEX IDX_794381C68F93B6FC (movie_id), INDEX IDX_794381C6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT UNSIGNED AUTO_INCREMENT NOT NULL, role VARCHAR(32) NOT NULL, name VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_57698A6A57698A6A (role), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, role_id INT UNSIGNED NOT NULL, name VARCHAR(64) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(150) NOT NULL, joined DATETIME NOT NULL, last_visit DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D6495E237E06 (name), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_movie (user_id INT UNSIGNED NOT NULL, movie_id INT UNSIGNED NOT NULL, INDEX IDX_FF9C0937A76ED395 (user_id), INDEX IDX_FF9C09378F93B6FC (movie_id), PRIMARY KEY(user_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie_cast ADD CONSTRAINT FK_E1DE98FB8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_cast ADD CONSTRAINT FK_E1DE98FB217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_genre ADD CONSTRAINT FK_FD1229648F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_genre ADD CONSTRAINT FK_FD1229644296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_country ADD CONSTRAINT FK_73E58B488F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_country ADD CONSTRAINT FK_73E58B48F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_lang ADD CONSTRAINT FK_C26FA56F8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_lang ADD CONSTRAINT FK_C26FA56FB213FA4 FOREIGN KEY (lang_id) REFERENCES lang (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_request ADD CONSTRAINT FK_1B01CDB15E0EDEC4 FOREIGN KEY (movie_submission_id) REFERENCES movie_submission (id)');
        $this->addSql('ALTER TABLE movie_request ADD CONSTRAINT FK_1B01CDB1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE movie_request ADD CONSTRAINT FK_1B01CDB112EBCCD6 FOREIGN KEY (current_movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE movie_submissions_cast ADD CONSTRAINT FK_4853AC935E0EDEC4 FOREIGN KEY (movie_submission_id) REFERENCES movie_submission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_submissions_cast ADD CONSTRAINT FK_4853AC93217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_submission_genre ADD CONSTRAINT FK_F4F093CB5E0EDEC4 FOREIGN KEY (movie_submission_id) REFERENCES movie_submission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_submission_genre ADD CONSTRAINT FK_F4F093CB4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_submission_country ADD CONSTRAINT FK_71CE781E5E0EDEC4 FOREIGN KEY (movie_submission_id) REFERENCES movie_submission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_submission_country ADD CONSTRAINT FK_71CE781EF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_submission_lang ADD CONSTRAINT FK_969921635E0EDEC4 FOREIGN KEY (movie_submission_id) REFERENCES movie_submission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_submission_lang ADD CONSTRAINT FK_96992163B213FA4 FOREIGN KEY (lang_id) REFERENCES lang (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C68F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user_movie ADD CONSTRAINT FK_FF9C0937A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_movie ADD CONSTRAINT FK_FF9C09378F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie_country DROP FOREIGN KEY FK_73E58B48F92F3E70');
        $this->addSql('ALTER TABLE movie_submission_country DROP FOREIGN KEY FK_71CE781EF92F3E70');
        $this->addSql('ALTER TABLE movie_genre DROP FOREIGN KEY FK_FD1229644296D31F');
        $this->addSql('ALTER TABLE movie_submission_genre DROP FOREIGN KEY FK_F4F093CB4296D31F');
        $this->addSql('ALTER TABLE movie_lang DROP FOREIGN KEY FK_C26FA56FB213FA4');
        $this->addSql('ALTER TABLE movie_submission_lang DROP FOREIGN KEY FK_96992163B213FA4');
        $this->addSql('ALTER TABLE movie_cast DROP FOREIGN KEY FK_E1DE98FB8F93B6FC');
        $this->addSql('ALTER TABLE movie_genre DROP FOREIGN KEY FK_FD1229648F93B6FC');
        $this->addSql('ALTER TABLE movie_country DROP FOREIGN KEY FK_73E58B488F93B6FC');
        $this->addSql('ALTER TABLE movie_lang DROP FOREIGN KEY FK_C26FA56F8F93B6FC');
        $this->addSql('ALTER TABLE movie_request DROP FOREIGN KEY FK_1B01CDB112EBCCD6');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C68F93B6FC');
        $this->addSql('ALTER TABLE user_movie DROP FOREIGN KEY FK_FF9C09378F93B6FC');
        $this->addSql('ALTER TABLE movie_request DROP FOREIGN KEY FK_1B01CDB15E0EDEC4');
        $this->addSql('ALTER TABLE movie_submissions_cast DROP FOREIGN KEY FK_4853AC935E0EDEC4');
        $this->addSql('ALTER TABLE movie_submission_genre DROP FOREIGN KEY FK_F4F093CB5E0EDEC4');
        $this->addSql('ALTER TABLE movie_submission_country DROP FOREIGN KEY FK_71CE781E5E0EDEC4');
        $this->addSql('ALTER TABLE movie_submission_lang DROP FOREIGN KEY FK_969921635E0EDEC4');
        $this->addSql('ALTER TABLE movie_cast DROP FOREIGN KEY FK_E1DE98FB217BBB47');
        $this->addSql('ALTER TABLE movie_submissions_cast DROP FOREIGN KEY FK_4853AC93217BBB47');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('ALTER TABLE movie_request DROP FOREIGN KEY FK_1B01CDB1A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE user_movie DROP FOREIGN KEY FK_FF9C0937A76ED395');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE lang');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_cast');
        $this->addSql('DROP TABLE movie_genre');
        $this->addSql('DROP TABLE movie_country');
        $this->addSql('DROP TABLE movie_lang');
        $this->addSql('DROP TABLE movie_request');
        $this->addSql('DROP TABLE movie_submission');
        $this->addSql('DROP TABLE movie_submissions_cast');
        $this->addSql('DROP TABLE movie_submission_genre');
        $this->addSql('DROP TABLE movie_submission_country');
        $this->addSql('DROP TABLE movie_submission_lang');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_movie');
    }
}
