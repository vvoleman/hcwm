<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220402130950 extends AbstractMigration
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
        $this->addSql('CREATE TABLE collections_languages (collection_id VARCHAR(255) NOT NULL, language_id VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_C251C7B0514956FD (collection_id), INDEX IDX_C251C7B082F1BAF4 (language_id), PRIMARY KEY(collection_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE items (id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE items_languages (item_id VARCHAR(255) NOT NULL, language_id VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_DC7AC77E126F525E (item_id), INDEX IDX_DC7AC77E82F1BAF4 (language_id), PRIMARY KEY(item_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE languages (code VARCHAR(255) NOT NULL, flag VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A0D153795E237E06 (name), PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE authors ADD CONSTRAINT FK_8E0C2A51126F525E FOREIGN KEY (item_id) REFERENCES items (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collections ADD CONSTRAINT FK_D325D3EE727ACA70 FOREIGN KEY (parent_id) REFERENCES collections (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collections_languages ADD CONSTRAINT FK_C251C7B0514956FD FOREIGN KEY (collection_id) REFERENCES collections (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collections_languages ADD CONSTRAINT FK_C251C7B082F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (code) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE items_languages ADD CONSTRAINT FK_DC7AC77E126F525E FOREIGN KEY (item_id) REFERENCES items (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE items_languages ADD CONSTRAINT FK_DC7AC77E82F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (code) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collections DROP FOREIGN KEY FK_D325D3EE727ACA70');
        $this->addSql('ALTER TABLE collections_languages DROP FOREIGN KEY FK_C251C7B0514956FD');
        $this->addSql('ALTER TABLE authors DROP FOREIGN KEY FK_8E0C2A51126F525E');
        $this->addSql('ALTER TABLE items_languages DROP FOREIGN KEY FK_DC7AC77E126F525E');
        $this->addSql('ALTER TABLE collections_languages DROP FOREIGN KEY FK_C251C7B082F1BAF4');
        $this->addSql('ALTER TABLE items_languages DROP FOREIGN KEY FK_DC7AC77E82F1BAF4');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE collections');
        $this->addSql('DROP TABLE collections_languages');
        $this->addSql('DROP TABLE items');
        $this->addSql('DROP TABLE items_languages');
        $this->addSql('DROP TABLE languages');
    }
}
