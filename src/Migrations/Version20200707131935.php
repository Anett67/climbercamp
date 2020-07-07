<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200707131935 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE climbing_club CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE telephone telephone BIGINT DEFAULT NULL, CHANGE addresse addresse VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event CHANGE posted_by_id posted_by_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE event_date event_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE event_like CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE event_id event_id INT DEFAULT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE event_like ADD CONSTRAINT FK_B3A80C1871F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_like ADD CONSTRAINT FK_B3A80C185A6D2235 FOREIGN KEY (posted_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B3A80C1871F7E88B ON event_like (event_id)');
        $this->addSql('CREATE INDEX IDX_B3A80C185A6D2235 ON event_like (posted_by_id)');
        $this->addSql('ALTER TABLE post CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE level_id level_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE climbing_club CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone BIGINT DEFAULT NULL, CHANGE addresse addresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE event CHANGE posted_by_id posted_by_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE event_date event_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE event_like MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE event_like DROP FOREIGN KEY FK_B3A80C1871F7E88B');
        $this->addSql('ALTER TABLE event_like DROP FOREIGN KEY FK_B3A80C185A6D2235');
        $this->addSql('DROP INDEX IDX_B3A80C1871F7E88B ON event_like');
        $this->addSql('DROP INDEX IDX_B3A80C185A6D2235 ON event_like');
        $this->addSql('ALTER TABLE event_like DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE event_like CHANGE id id INT NOT NULL, CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE level_id level_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
