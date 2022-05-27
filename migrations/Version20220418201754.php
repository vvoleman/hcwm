<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418201754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE translation (id VARCHAR(255) NOT NULL, from_language_id VARCHAR(255) NOT NULL, to_language_id VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B469456F1CBE8B91 (from_language_id), INDEX IDX_B469456FAC628497 (to_language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456F1CBE8B91 FOREIGN KEY (from_language_id) REFERENCES languages (code) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456FAC628497 FOREIGN KEY (to_language_id) REFERENCES languages (code) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE translation');
    }
}
