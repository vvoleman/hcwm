<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810215133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trash_records_country (id INT AUTO_INCREMENT NOT NULL, country_id VARCHAR(255) DEFAULT NULL, trash_type_id VARCHAR(255) DEFAULT NULL, year INT NOT NULL, value DOUBLE PRECISION NOT NULL, INDEX IDX_88D4FA0BF92F3E70 (country_id), INDEX IDX_88D4FA0B2F1A9A3 (trash_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trash_records_country ADD CONSTRAINT FK_88D4FA0BF92F3E70 FOREIGN KEY (country_id) REFERENCES countries (id)');
        $this->addSql('ALTER TABLE trash_records_country ADD CONSTRAINT FK_88D4FA0B2F1A9A3 FOREIGN KEY (trash_type_id) REFERENCES trash_types (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE trash_records_country');
    }
}
