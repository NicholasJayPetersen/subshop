<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408003853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Order entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entire_order (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, started_at DATETIME DEFAULT NULL, completed_at DATETIME DEFAULT NULL, total_price DOUBLE PRECISION NOT NULL, status VARCHAR(255) DEFAULT NULL, INDEX IDX_3FCC595CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entire_order ADD CONSTRAINT FK_3FCC595CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entire_order DROP FOREIGN KEY FK_3FCC595CA76ED395');
        $this->addSql('DROP TABLE entire_order');
    }
}
