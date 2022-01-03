<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211117153551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_feature DROP FOREIGN KEY FK_CE0E6ED64584665A');
        $this->addSql('DROP INDEX IDX_CE0E6ED64584665A ON product_feature');
        $this->addSql('ALTER TABLE product_feature ADD position INT NOT NULL, CHANGE product_id group_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_feature ADD CONSTRAINT FK_CE0E6ED6FE54D947 FOREIGN KEY (group_id) REFERENCES product_feature_group (id)');
        $this->addSql('CREATE INDEX IDX_CE0E6ED6FE54D947 ON product_feature (group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_feature DROP FOREIGN KEY FK_CE0E6ED6FE54D947');
        $this->addSql('DROP INDEX IDX_CE0E6ED6FE54D947 ON product_feature');
        $this->addSql('ALTER TABLE product_feature ADD product_id INT NOT NULL, DROP group_id, DROP position');
        $this->addSql('ALTER TABLE product_feature ADD CONSTRAINT FK_CE0E6ED64584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_CE0E6ED64584665A ON product_feature (product_id)');
    }
}
