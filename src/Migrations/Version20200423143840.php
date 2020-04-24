<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200423143840 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE climbing_club_climbing_categorie (climbing_club_id INT NOT NULL, climbing_categorie_id INT NOT NULL, INDEX IDX_297B4F6EF802E2CC (climbing_club_id), INDEX IDX_297B4F6EE8CA1D04 (climbing_categorie_id), PRIMARY KEY(climbing_club_id, climbing_categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE climbing_club_climbing_categorie ADD CONSTRAINT FK_297B4F6EF802E2CC FOREIGN KEY (climbing_club_id) REFERENCES climbing_club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE climbing_club_climbing_categorie ADD CONSTRAINT FK_297B4F6EE8CA1D04 FOREIGN KEY (climbing_categorie_id) REFERENCES climbing_categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE climbing_club ADD image VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD telephone INT DEFAULT NULL, ADD addresse VARCHAR(255) DEFAULT NULL, CHANGE ville_id ville_id INT DEFAULT NULL');
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

        $this->addSql('DROP TABLE climbing_club_climbing_categorie');
        $this->addSql('ALTER TABLE climbing_club DROP image, DROP email, DROP telephone, DROP addresse, CHANGE ville_id ville_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event CHANGE posted_by_id posted_by_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE event_date event_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE event_like CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE post_comment CHANGE post_id post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE level_id level_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
