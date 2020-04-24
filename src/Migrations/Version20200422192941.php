<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200422192941 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event_comment_like (id INT AUTO_INCREMENT NOT NULL, event_comment_id INT NOT NULL, posted_by_id INT NOT NULL, INDEX IDX_6746E8ACA087A43C (event_comment_id), INDEX IDX_6746E8AC5A6D2235 (posted_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_comment_reply (id INT AUTO_INCREMENT NOT NULL, event_comment_id INT NOT NULL, posted_by_id INT NOT NULL, posted_at DATETIME NOT NULL, body LONGTEXT NOT NULL, INDEX IDX_706BEEBDA087A43C (event_comment_id), INDEX IDX_706BEEBD5A6D2235 (posted_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_comment_like (id INT AUTO_INCREMENT NOT NULL, post_comment_id INT NOT NULL, posted_by_id INT NOT NULL, INDEX IDX_21689F8CDB1174D2 (post_comment_id), INDEX IDX_21689F8C5A6D2235 (posted_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_comment_reply (id INT AUTO_INCREMENT NOT NULL, post_comment_id INT NOT NULL, posted_by_id INT NOT NULL, posted_at DATETIME NOT NULL, body LONGTEXT NOT NULL, INDEX IDX_4B43E002DB1174D2 (post_comment_id), INDEX IDX_4B43E0025A6D2235 (posted_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_comment_like ADD CONSTRAINT FK_6746E8ACA087A43C FOREIGN KEY (event_comment_id) REFERENCES event_comment (id)');
        $this->addSql('ALTER TABLE event_comment_like ADD CONSTRAINT FK_6746E8AC5A6D2235 FOREIGN KEY (posted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_comment_reply ADD CONSTRAINT FK_706BEEBDA087A43C FOREIGN KEY (event_comment_id) REFERENCES event_comment (id)');
        $this->addSql('ALTER TABLE event_comment_reply ADD CONSTRAINT FK_706BEEBD5A6D2235 FOREIGN KEY (posted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_comment_like ADD CONSTRAINT FK_21689F8CDB1174D2 FOREIGN KEY (post_comment_id) REFERENCES post_comment (id)');
        $this->addSql('ALTER TABLE post_comment_like ADD CONSTRAINT FK_21689F8C5A6D2235 FOREIGN KEY (posted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_comment_reply ADD CONSTRAINT FK_4B43E002DB1174D2 FOREIGN KEY (post_comment_id) REFERENCES post_comment (id)');
        $this->addSql('ALTER TABLE post_comment_reply ADD CONSTRAINT FK_4B43E0025A6D2235 FOREIGN KEY (posted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE climbing_club CHANGE ville_id ville_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event CHANGE posted_by_id posted_by_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE event_date event_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE event_like CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE post_comment CHANGE post_id post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE level_id level_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE event_comment_like');
        $this->addSql('DROP TABLE event_comment_reply');
        $this->addSql('DROP TABLE post_comment_like');
        $this->addSql('DROP TABLE post_comment_reply');
        $this->addSql('ALTER TABLE climbing_club CHANGE ville_id ville_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event CHANGE posted_by_id posted_by_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE event_date event_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE event_like CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE post_comment CHANGE post_id post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE level_id level_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
