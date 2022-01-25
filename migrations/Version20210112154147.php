<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210112154147 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, retex_id INT NOT NULL, author_id INT NOT NULL, created_at DATETIME NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_9474526CA433097D (retex_id), INDEX IDX_9474526CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decision (id INT AUTO_INCREMENT NOT NULL, retex_id INT DEFAULT NULL, decision_id INT DEFAULT NULL, date_submit DATETIME DEFAULT NULL, message VARCHAR(255) DEFAULT NULL, INDEX IDX_84ACBE48A433097D (retex_id), INDEX IDX_84ACBE48BDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retex (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, author_id INT NOT NULL, objet LONGTEXT DEFAULT NULL, reference LONGTEXT DEFAULT NULL, titre LONGTEXT DEFAULT NULL, generalites LONGTEXT DEFAULT NULL, prepamission LONGTEXT DEFAULT NULL, ii1personnel LONGTEXT DEFAULT NULL, ii1apositif LONGTEXT DEFAULT NULL, ii1bperfectible LONGTEXT DEFAULT NULL, ii2materiel LONGTEXT DEFAULT NULL, ii2apositif LONGTEXT DEFAULT NULL, ii2bperfectible LONGTEXT DEFAULT NULL, ii2cameliration LONGTEXT DEFAULT NULL, ii3technique LONGTEXT DEFAULT NULL, ii3bperfectible LONGTEXT DEFAULT NULL, ii3camelioration LONGTEXT DEFAULT NULL, iii1personnel LONGTEXT DEFAULT NULL, iii1apositif LONGTEXT DEFAULT NULL, iii1bperfectible LONGTEXT DEFAULT NULL, iii1camelioration LONGTEXT DEFAULT NULL, iii2materiel LONGTEXT DEFAULT NULL, iii2apositif LONGTEXT DEFAULT NULL, iii2bamelioration LONGTEXT DEFAULT NULL, iii2camelioration LONGTEXT DEFAULT NULL, ivretour LONGTEXT DEFAULT NULL, iv1personnel LONGTEXT DEFAULT NULL, iv1apositif LONGTEXT DEFAULT NULL, iv1bperfectible LONGTEXT DEFAULT NULL, iv1camelioration LONGTEXT DEFAULT NULL, iv2materiel LONGTEXT DEFAULT NULL, iv2apositif LONGTEXT DEFAULT NULL, iv2bperfectible LONGTEXT DEFAULT NULL, iv2camelioration LONGTEXT DEFAULT NULL, iv3technique LONGTEXT DEFAULT NULL, iv3apositif LONGTEXT DEFAULT NULL, iv3camelioration LONGTEXT DEFAULT NULL, conclusion LONGTEXT DEFAULT NULL, ii3apositif LONGTEXT DEFAULT NULL, iii2bperfectible LONGTEXT DEFAULT NULL, iv3bperfectible LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, iii3apositif LONGTEXT DEFAULT NULL, iii3bperfectible LONGTEXT DEFAULT NULL, iii3camelioration LONGTEXT DEFAULT NULL, published TINYINT(1) DEFAULT NULL, piecejointe VARCHAR(255) DEFAULT NULL, standby TINYINT(1) DEFAULT NULL, INDEX IDX_5C64E5E3BCF5E72D (categorie_id), INDEX IDX_5C64E5E3F675F31B (author_id), FULLTEXT INDEX IDX_5C64E5E346CD4C38FF7747B49C1495A (objet, titre, generalites), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(255) NOT NULL, filename VARCHAR(255) DEFAULT NULL, activation_token VARCHAR(50) DEFAULT NULL, reset_token VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE validation (id INT AUTO_INCREMENT NOT NULL, retex_id INT DEFAULT NULL, validation_id INT DEFAULT NULL, date_submit DATETIME DEFAULT NULL, message VARCHAR(255) DEFAULT NULL, standby TINYINT(1) DEFAULT NULL, INDEX IDX_16AC5B6EA433097D (retex_id), INDEX IDX_16AC5B6EA2274850 (validation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA433097D FOREIGN KEY (retex_id) REFERENCES retex (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE48A433097D FOREIGN KEY (retex_id) REFERENCES retex (id)');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE48BDEE7539 FOREIGN KEY (decision_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE retex ADD CONSTRAINT FK_5C64E5E3BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE retex ADD CONSTRAINT FK_5C64E5E3F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE validation ADD CONSTRAINT FK_16AC5B6EA433097D FOREIGN KEY (retex_id) REFERENCES retex (id)');
        $this->addSql('ALTER TABLE validation ADD CONSTRAINT FK_16AC5B6EA2274850 FOREIGN KEY (validation_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE retex DROP FOREIGN KEY FK_5C64E5E3BCF5E72D');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA433097D');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE48A433097D');
        $this->addSql('ALTER TABLE validation DROP FOREIGN KEY FK_16AC5B6EA433097D');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE48BDEE7539');
        $this->addSql('ALTER TABLE retex DROP FOREIGN KEY FK_5C64E5E3F675F31B');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE validation DROP FOREIGN KEY FK_16AC5B6EA2274850');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE decision');
        $this->addSql('DROP TABLE retex');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE validation');
    }
}
