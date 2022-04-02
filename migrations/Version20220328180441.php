<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328180441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE authors (id INT AUTO_INCREMENT NOT NULL, item_id VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, INDEX IDX_8E0C2A51126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collections (id VARCHAR(255) NOT NULL, parent_id VARCHAR(255) DEFAULT NULL, INDEX IDX_D325D3EE727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE items (id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE languages (id VARCHAR(255) NOT NULL, flag VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A0D153795E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language_collection (language_id VARCHAR(255) NOT NULL, collection_id VARCHAR(255) NOT NULL, INDEX IDX_BC540E2F82F1BAF4 (language_id), INDEX IDX_BC540E2F514956FD (collection_id), PRIMARY KEY(language_id, collection_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language_item (language_id VARCHAR(255) NOT NULL, item_id VARCHAR(255) NOT NULL, INDEX IDX_2D12CF5282F1BAF4 (language_id), INDEX IDX_2D12CF52126F525E (item_id), PRIMARY KEY(language_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE authors ADD CONSTRAINT FK_8E0C2A51126F525E FOREIGN KEY (item_id) REFERENCES items (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collections ADD CONSTRAINT FK_D325D3EE727ACA70 FOREIGN KEY (parent_id) REFERENCES collections (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE language_collection ADD CONSTRAINT FK_BC540E2F82F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE language_collection ADD CONSTRAINT FK_BC540E2F514956FD FOREIGN KEY (collection_id) REFERENCES collections (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE language_item ADD CONSTRAINT FK_2D12CF5282F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE language_item ADD CONSTRAINT FK_2D12CF52126F525E FOREIGN KEY (item_id) REFERENCES items (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collections DROP FOREIGN KEY FK_D325D3EE727ACA70');
        $this->addSql('ALTER TABLE language_collection DROP FOREIGN KEY FK_BC540E2F514956FD');
        $this->addSql('ALTER TABLE authors DROP FOREIGN KEY FK_8E0C2A51126F525E');
        $this->addSql('ALTER TABLE language_item DROP FOREIGN KEY FK_2D12CF52126F525E');
        $this->addSql('ALTER TABLE language_collection DROP FOREIGN KEY FK_BC540E2F82F1BAF4');
        $this->addSql('ALTER TABLE language_item DROP FOREIGN KEY FK_2D12CF5282F1BAF4');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE collections');
        $this->addSql('DROP TABLE items');
        $this->addSql('DROP TABLE languages');
        $this->addSql('DROP TABLE language_collection');
        $this->addSql('DROP TABLE language_item');
    }
}
