<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200422180832 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE climbing_categorie (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_climbing_categorie (user_id INT NOT NULL, climbing_categorie_id INT NOT NULL, INDEX IDX_C1D70FADA76ED395 (user_id), INDEX IDX_C1D70FADE8CA1D04 (climbing_categorie_id), PRIMARY KEY(user_id, climbing_categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_climbing_categorie ADD CONSTRAINT FK_C1D70FADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_climbing_categorie ADD CONSTRAINT FK_C1D70FADE8CA1D04 FOREIGN KEY (climbing_categorie_id) REFERENCES climbing_categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE climbing_club CHANGE ville_id ville_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_climbing_categorie DROP FOREIGN KEY FK_C1D70FADE8CA1D04');
        $this->addSql('DROP TABLE climbing_categorie');
        $this->addSql('DROP TABLE user_climbing_categorie');
        $this->addSql('ALTER TABLE climbing_club CHANGE ville_id ville_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE ville_id ville_id INT DEFAULT NULL, CHANGE image image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
