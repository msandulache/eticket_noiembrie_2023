<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231116135919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, tmdb_id INT NOT NULL, title VARCHAR(255) NOT NULL, original_language VARCHAR(255) NOT NULL, original_title VARCHAR(255) NOT NULL, overview LONGTEXT NOT NULL, genre_ids VARCHAR(255) NOT NULL, backdrop_path VARCHAR(255) NOT NULL, poster_path VARCHAR(255) NOT NULL, adult TINYINT(1) NOT NULL, video TINYINT(1) NOT NULL, popularity DOUBLE PRECISION NOT NULL, vote_average DOUBLE PRECISION NOT NULL, vote_count INT NOT NULL, category_id INT NOT NULL, release_date DATE NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE movie');
    }
}
