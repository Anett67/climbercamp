<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200707115241 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE climbing_club CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE telephone telephone INT DEFAULT NULL, CHANGE addresse addresse VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event CHANGE posted_by_id posted_by_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE event_date event_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE event_comment DROP FOREIGN KEY FK_1123FBC371F7E88B');
        $this->addSql('ALTER TABLE event_comment ADD CONSTRAINT FK_1123FBC371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_comment_like DROP FOREIGN KEY FK_6746E8ACA087A43C');
        $this->addSql('ALTER TABLE event_comment_like ADD CONSTRAINT FK_6746E8ACA087A43C FOREIGN KEY (event_comment_id) REFERENCES event_comment (id)');
        $this->addSql('ALTER TABLE event_comment_reply DROP FOREIGN KEY FK_706BEEBDA087A43C');
        $this->addSql('ALTER TABLE event_comment_reply ADD CONSTRAINT FK_706BEEBDA087A43C FOREIGN KEY (event_comment_id) REFERENCES event_comment (id)');
        $this->addSql('ALTER TABLE event_like DROP FOREIGN KEY FK_B3A80C1871F7E88B');
        $this->addSql('ALTER TABLE event_like CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event_like ADD CONSTRAINT FK_B3A80C185A6D2235 FOREIGN KEY (posted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_like ADD CONSTRAINT FK_B3A80C1871F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE post CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE post_comment DROP FOREIGN KEY FK_A99CE55F4B89032C');
        $this->addSql('ALTER TABLE post_comment CHANGE post_id post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post_comment ADD CONSTRAINT FK_A99CE55F4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_comment_like DROP FOREIGN KEY FK_21689F8CDB1174D2');
        $this->addSql('ALTER TABLE post_comment_like ADD CONSTRAINT FK_21689F8CDB1174D2 FOREIGN KEY (post_comment_id) REFERENCES post_comment (id)');
        $this->addSql('ALTER TABLE post_comment_reply DROP FOREIGN KEY FK_4B43E002DB1174D2');
        $this->addSql('ALTER TABLE post_comment_reply ADD CONSTRAINT FK_4B43E002DB1174D2 FOREIGN KEY (post_comment_id) REFERENCES post_comment (id)');
        $this->addSql('ALTER TABLE post_like DROP FOREIGN KEY FK_653627B84B89032C');
        $this->addSql('ALTER TABLE post_like ADD CONSTRAINT FK_653627B84B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE user CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE level_id level_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE climbing_club CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone BIGINT DEFAULT NULL, CHANGE addresse addresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE event CHANGE posted_by_id posted_by_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE event_date event_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE event_comment DROP FOREIGN KEY FK_1123FBC371F7E88B');
        $this->addSql('ALTER TABLE event_comment ADD CONSTRAINT FK_1123FBC371F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_comment_like DROP FOREIGN KEY FK_6746E8ACA087A43C');
        $this->addSql('ALTER TABLE event_comment_like ADD CONSTRAINT FK_6746E8ACA087A43C FOREIGN KEY (event_comment_id) REFERENCES event_comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_comment_reply DROP FOREIGN KEY FK_706BEEBDA087A43C');
        $this->addSql('ALTER TABLE event_comment_reply ADD CONSTRAINT FK_706BEEBDA087A43C FOREIGN KEY (event_comment_id) REFERENCES event_comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_like DROP FOREIGN KEY FK_B3A80C185A6D2235');
        $this->addSql('ALTER TABLE event_like DROP FOREIGN KEY FK_B3A80C1871F7E88B');
        $this->addSql('ALTER TABLE event_like CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event_like ADD CONSTRAINT FK_B3A80C1871F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE post_comment DROP FOREIGN KEY FK_A99CE55F4B89032C');
        $this->addSql('ALTER TABLE post_comment CHANGE post_id post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post_comment ADD CONSTRAINT FK_A99CE55F4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_comment_like DROP FOREIGN KEY FK_21689F8CDB1174D2');
        $this->addSql('ALTER TABLE post_comment_like ADD CONSTRAINT FK_21689F8CDB1174D2 FOREIGN KEY (post_comment_id) REFERENCES post_comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_comment_reply DROP FOREIGN KEY FK_4B43E002DB1174D2');
        $this->addSql('ALTER TABLE post_comment_reply ADD CONSTRAINT FK_4B43E002DB1174D2 FOREIGN KEY (post_comment_id) REFERENCES post_comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_like DROP FOREIGN KEY FK_653627B84B89032C');
        $this->addSql('ALTER TABLE post_like ADD CONSTRAINT FK_653627B84B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE level_id level_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
