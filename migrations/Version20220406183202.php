<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220406183202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE items DROP FOREIGN KEY FK_E11EE94D514956FD');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94D514956FD FOREIGN KEY (collection_id) REFERENCES collections (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE items DROP FOREIGN KEY FK_E11EE94D514956FD');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94D514956FD FOREIGN KEY (collection_id) REFERENCES collections (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
