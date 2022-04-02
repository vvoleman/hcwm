<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328182532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE collections_languages (collection_id VARCHAR(255) NOT NULL, language_id VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_C251C7B0514956FD (collection_id), INDEX IDX_C251C7B082F1BAF4 (language_id), PRIMARY KEY(collection_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collections_languages ADD CONSTRAINT FK_C251C7B0514956FD FOREIGN KEY (collection_id) REFERENCES collections (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collections_languages ADD CONSTRAINT FK_C251C7B082F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (code) ON DELETE CASCADE');
        $this->addSql('DROP TABLE language_collection');
        $this->addSql('DROP TABLE language_item');
        $this->addSql('ALTER TABLE languages DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE languages CHANGE id code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE languages ADD PRIMARY KEY (code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE language_collection (language_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, collection_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_BC540E2F514956FD (collection_id), INDEX IDX_BC540E2F82F1BAF4 (language_id), PRIMARY KEY(language_id, collection_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE language_item (language_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, item_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_2D12CF52126F525E (item_id), INDEX IDX_2D12CF5282F1BAF4 (language_id), PRIMARY KEY(language_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE language_collection ADD CONSTRAINT FK_BC540E2F514956FD FOREIGN KEY (collection_id) REFERENCES collections (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE language_collection ADD CONSTRAINT FK_BC540E2F82F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE language_item ADD CONSTRAINT FK_2D12CF52126F525E FOREIGN KEY (item_id) REFERENCES items (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE language_item ADD CONSTRAINT FK_2D12CF5282F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE collections_languages');
        $this->addSql('ALTER TABLE languages DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE languages CHANGE code id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE languages ADD PRIMARY KEY (id)');
    }
}
