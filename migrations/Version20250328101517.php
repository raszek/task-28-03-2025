<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328101517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meal_comment (id INT AUTO_INCREMENT NOT NULL, meal_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_EDC7E075639666D6 (meal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meal_comment ADD CONSTRAINT FK_EDC7E075639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE meal CHANGE external_id external_id VARCHAR(10) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9EF68E9C9F75D7B0 ON meal (external_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meal_comment DROP FOREIGN KEY FK_EDC7E075639666D6');
        $this->addSql('DROP TABLE meal_comment');
        $this->addSql('DROP INDEX UNIQ_9EF68E9C9F75D7B0 ON meal');
        $this->addSql('ALTER TABLE meal CHANGE external_id external_id VARCHAR(255) NOT NULL');
    }
}
