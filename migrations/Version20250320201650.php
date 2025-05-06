<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320201650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add menu items to table';
    }

    public function up(Schema $schema): void
    {
        foreach ($this->getMenu() as $item) {
            $this->connection->insert('menu', [
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
                'price' => $item['price'],
                'category' => $item['category'],
                'is_available' => 1,
            ]);
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE TABLE menu');
    }

    private function getMenu(): array
    {
        return [
            ['name' => 'The Brooklyn Classic', 'price' => 11.99, 'description' => 'Roast beef, cheddar, caramelized onions, and horseradish mayo.', 'category' => 'sandwiches'],
            ['name' => 'The Midtown Club', 'price' => 10.99, 'description' => 'Turkey, ham, bacon, Swiss, lettuce, tomato, and honey mustard.', 'category' => 'sandwiches'],
            ['name' => "Amy's Italian Supreme", 'price' => 11.49, 'description' => 'Salami, pepperoni, capicola, provolone, lettuce, tomato, onions, banana peppers, and Italian dressing.', 'category' => 'sandwiches'],
            ['name' => 'Jake’s Philly Steak', 'price' => 12.49, 'description' => 'Grilled steak, provolone, sautéed onions, and bell peppers.', 'category' => 'sandwiches'],
            ['name' => 'The Pizza Sub', 'price' => 10.99, 'description' => 'Pepperoni, marinara sauce, mozzarella cheese, and Italian seasoning, toasted to perfection.', 'category' => 'sandwiches'],
            ['name' => 'The Captain’s Catch', 'price' => 11.99, 'description' => 'Crispy fried fish, lettuce, tomato, and tartar sauce.', 'category' => 'sandwiches'],
            ['name' => 'The Garden Fresh', 'price' => 9.99, 'description' => 'Grilled zucchini, mushrooms, roasted red peppers, spinach, and hummus spread.', 'category' => 'sandwiches'],
            ['name' => 'The Smoky Chicken Melt', 'price' => 11.29, 'description' => 'Grilled chicken, bacon, pepper jack cheese, and chipotle mayo.', 'category' => 'sandwiches'],

            ['name' => 'Crinkle-Cut Fries', 'price' => 3.99, 'category' => 'sides'],
            ['name' => 'Garlic Parmesan Tots', 'price' => 3.39, 'category' => 'sides'],
            ['name' => 'Loaded Nacho Fries', 'price' => 5.99, 'category' => 'sides'],
            ['name' => 'House-Made Coleslaw', 'price' => 2.99, 'category' => 'sides'],
            ['name' => 'Classic Mac & Cheese', 'price' => 4.99, 'category' => 'sides'],
            ['name' => 'Mozzarella Sticks (6 pcs)', 'price' => 6.49, 'category' => 'sides'],

            ['name' => 'Fountain Sodas 20 oz.', 'price' => 2.49, 'description' => 'Coke, Diet Coke, Sprite, Dr. Pepper, Root Beer', 'category' => 'beverages'],
            ['name' => 'Fresh-Brewed Iced Tea 20 oz.', 'price' => 2.49, 'description' => 'Sweet or Unsweet', 'category' => 'beverages'],
            ['name' => 'Lemonade 20 oz.', 'price' => 2.99, 'category' => 'beverages'],
            ['name' => 'Bottled Water', 'price' => 1.99, 'category' => 'beverages'],
            ['name' => 'Cold Brew Coffee 16 oz.', 'price' => 3.99, 'category' => 'beverages'],
        ];
    }
}
