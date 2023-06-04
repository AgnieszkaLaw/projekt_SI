<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230604121753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, UNIQUE INDEX uq_categories_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contacts (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(191) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, note LONGTEXT DEFAULT NULL, INDEX IDX_33401573F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contacts_tags (contact_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_6FDD317FE7A1254A (contact_id), INDEX IDX_6FDD317FBAD26311 (tag_id), PRIMARY KEY(contact_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, author_id INT NOT NULL, name VARCHAR(255) NOT NULL, note LONGTEXT DEFAULT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, INDEX IDX_5387574A12469DE2 (category_id), INDEX IDX_5387574AF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events_tags (event_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_3EC905C71F7E88B (event_id), INDEX IDX_3EC905CBAD26311 (tag_id), PRIMARY KEY(event_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, UNIQUE INDEX uq_tags_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contacts ADD CONSTRAINT FK_33401573F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE contacts_tags ADD CONSTRAINT FK_6FDD317FE7A1254A FOREIGN KEY (contact_id) REFERENCES contacts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contacts_tags ADD CONSTRAINT FK_6FDD317FBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574AF675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE events_tags ADD CONSTRAINT FK_3EC905C71F7E88B FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events_tags ADD CONSTRAINT FK_3EC905CBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contacts DROP FOREIGN KEY FK_33401573F675F31B');
        $this->addSql('ALTER TABLE contacts_tags DROP FOREIGN KEY FK_6FDD317FE7A1254A');
        $this->addSql('ALTER TABLE contacts_tags DROP FOREIGN KEY FK_6FDD317FBAD26311');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A12469DE2');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574AF675F31B');
        $this->addSql('ALTER TABLE events_tags DROP FOREIGN KEY FK_3EC905C71F7E88B');
        $this->addSql('ALTER TABLE events_tags DROP FOREIGN KEY FK_3EC905CBAD26311');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE contacts');
        $this->addSql('DROP TABLE contacts_tags');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE events_tags');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
