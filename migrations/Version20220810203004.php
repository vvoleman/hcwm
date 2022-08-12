<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810203004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trash_records (id INT AUTO_INCREMENT NOT NULL, year INT NOT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trash_records_district (id INT AUTO_INCREMENT NOT NULL, district_id VARCHAR(255) DEFAULT NULL, year INT NOT NULL, value DOUBLE PRECISION NOT NULL, INDEX IDX_219EE51B08FA272 (district_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trash_records_region (id INT AUTO_INCREMENT NOT NULL, region_id VARCHAR(255) DEFAULT NULL, year INT NOT NULL, value DOUBLE PRECISION NOT NULL, INDEX IDX_13984A5C98260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trash_types (id VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trash_records_district ADD CONSTRAINT FK_219EE51B08FA272 FOREIGN KEY (district_id) REFERENCES districts (id)');
        $this->addSql('ALTER TABLE trash_records_region ADD CONSTRAINT FK_13984A5C98260155 FOREIGN KEY (region_id) REFERENCES regions (id)');
        $this->addSql('ALTER TABLE districts DROP FOREIGN KEY FK_68E318DC5932F377');
        $this->addSql('DROP INDEX IDX_68E318DC5932F377 ON districts');
        $this->addSql('ALTER TABLE districts ADD longitude DOUBLE PRECISION NOT NULL, ADD latitude DOUBLE PRECISION NOT NULL, DROP center_id');
        $this->addSql('ALTER TABLE regions DROP FOREIGN KEY FK_A26779F35932F377');
        $this->addSql('DROP INDEX IDX_A26779F35932F377 ON regions');
        $this->addSql('ALTER TABLE regions ADD longitude DOUBLE PRECISION NOT NULL, ADD latitude DOUBLE PRECISION NOT NULL, DROP center_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE trash_records');
        $this->addSql('DROP TABLE trash_records_district');
        $this->addSql('DROP TABLE trash_records_region');
        $this->addSql('DROP TABLE trash_types');
        $this->addSql('ALTER TABLE districts ADD center_id VARCHAR(255) DEFAULT NULL, DROP longitude, DROP latitude');
        $this->addSql('ALTER TABLE districts ADD CONSTRAINT FK_68E318DC5932F377 FOREIGN KEY (center_id) REFERENCES cities (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_68E318DC5932F377 ON districts (center_id)');
        $this->addSql('ALTER TABLE regions ADD center_id VARCHAR(255) DEFAULT NULL, DROP longitude, DROP latitude');
        $this->addSql('ALTER TABLE regions ADD CONSTRAINT FK_A26779F35932F377 FOREIGN KEY (center_id) REFERENCES cities (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A26779F35932F377 ON regions (center_id)');
    }
}
