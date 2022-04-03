<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220403193747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE items ADD collection_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94D514956FD FOREIGN KEY (collection_id) REFERENCES collections (id)');
        $this->addSql('CREATE INDEX IDX_E11EE94D514956FD ON items (collection_id)');
        $this->addSql('ALTER TABLE tags_languages ADD text VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE items DROP FOREIGN KEY FK_E11EE94D514956FD');
        $this->addSql('DROP INDEX IDX_E11EE94D514956FD ON items');
        $this->addSql('ALTER TABLE items DROP collection_id');
        $this->addSql('ALTER TABLE tags_languages DROP text');
    }
}
