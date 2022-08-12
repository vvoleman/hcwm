<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810211757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trash_records_district ADD trash_type_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE trash_records_district ADD CONSTRAINT FK_219EE512F1A9A3 FOREIGN KEY (trash_type_id) REFERENCES trash_types (id)');
        $this->addSql('CREATE INDEX IDX_219EE512F1A9A3 ON trash_records_district (trash_type_id)');
        $this->addSql('ALTER TABLE trash_records_region ADD trash_type_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE trash_records_region ADD CONSTRAINT FK_13984A5C2F1A9A3 FOREIGN KEY (trash_type_id) REFERENCES trash_types (id)');
        $this->addSql('CREATE INDEX IDX_13984A5C2F1A9A3 ON trash_records_region (trash_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trash_records_district DROP FOREIGN KEY FK_219EE512F1A9A3');
        $this->addSql('DROP INDEX IDX_219EE512F1A9A3 ON trash_records_district');
        $this->addSql('ALTER TABLE trash_records_district DROP trash_type_id');
        $this->addSql('ALTER TABLE trash_records_region DROP FOREIGN KEY FK_13984A5C2F1A9A3');
        $this->addSql('DROP INDEX IDX_13984A5C2F1A9A3 ON trash_records_region');
        $this->addSql('ALTER TABLE trash_records_region DROP trash_type_id');
    }
}
