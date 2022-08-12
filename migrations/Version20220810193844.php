<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810193844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cities (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, longitude DOUBLE PRECISION NOT NULL, latitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE countries ADD center_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE countries ADD CONSTRAINT FK_5D66EBAD5932F377 FOREIGN KEY (center_id) REFERENCES cities (id)');
        $this->addSql('CREATE INDEX IDX_5D66EBAD5932F377 ON countries (center_id)');
        $this->addSql('ALTER TABLE districts ADD center_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE districts ADD CONSTRAINT FK_68E318DC5932F377 FOREIGN KEY (center_id) REFERENCES cities (id)');
        $this->addSql('CREATE INDEX IDX_68E318DC5932F377 ON districts (center_id)');
        $this->addSql('ALTER TABLE regions ADD center_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE regions ADD CONSTRAINT FK_A26779F35932F377 FOREIGN KEY (center_id) REFERENCES cities (id)');
        $this->addSql('CREATE INDEX IDX_A26779F35932F377 ON regions (center_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE countries DROP FOREIGN KEY FK_5D66EBAD5932F377');
        $this->addSql('ALTER TABLE districts DROP FOREIGN KEY FK_68E318DC5932F377');
        $this->addSql('ALTER TABLE regions DROP FOREIGN KEY FK_A26779F35932F377');
        $this->addSql('DROP TABLE cities');
        $this->addSql('DROP INDEX IDX_5D66EBAD5932F377 ON countries');
        $this->addSql('ALTER TABLE countries DROP center_id');
        $this->addSql('DROP INDEX IDX_68E318DC5932F377 ON districts');
        $this->addSql('ALTER TABLE districts DROP center_id');
        $this->addSql('DROP INDEX IDX_A26779F35932F377 ON regions');
        $this->addSql('ALTER TABLE regions DROP center_id');
    }
}
