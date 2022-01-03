<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211208153449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feature_value ADD CONSTRAINT FK_D429523DC0043BA0 FOREIGN KEY (project_item_product_id) REFERENCES project_item_product (id)');
        $this->addSql('CREATE INDEX IDX_D429523DC0043BA0 ON feature_value (project_item_product_id)');
        $this->addSql('ALTER TABLE project_item DROP FOREIGN KEY FK_268AED064584665A');
        $this->addSql('DROP INDEX IDX_268AED064584665A ON project_item');
        $this->addSql('ALTER TABLE project_item DROP product_id');
        $this->addSql('ALTER TABLE project_item_product DROP model_count');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feature_value DROP FOREIGN KEY FK_D429523DC0043BA0');
        $this->addSql('DROP INDEX IDX_D429523DC0043BA0 ON feature_value');
        $this->addSql('ALTER TABLE project_item ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE project_item ADD CONSTRAINT FK_268AED064584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_268AED064584665A ON project_item (product_id)');
        $this->addSql('ALTER TABLE project_item_product ADD model_count INT NOT NULL');
    }
}
