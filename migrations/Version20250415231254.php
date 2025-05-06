<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250415231254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create OrderItem table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, menu_id INT NOT NULL, entire_order_id INT NOT NULL, INDEX IDX_52EA1F09CCD7E912 (menu_id), INDEX IDX_52EA1F09FA4C7A5D (entire_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09FA4C7A5D FOREIGN KEY (entire_order_id) REFERENCES entire_order (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09CCD7E912');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09FA4C7A5D');
        $this->addSql('DROP TABLE order_item');
    }
}
