<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211207113317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_item_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, project_item_id INT NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, model_count INT NOT NULL, INDEX IDX_F2B0C69A4584665A (product_id), INDEX IDX_F2B0C69A84715E3B (project_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_item_product ADD CONSTRAINT FK_F2B0C69A4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE project_item_product ADD CONSTRAINT FK_F2B0C69A84715E3B FOREIGN KEY (project_item_id) REFERENCES project_item (id)');
        $this->addSql('ALTER TABLE feature_value DROP FOREIGN KEY FK_D429523D84715E3B');
        $this->addSql('DROP INDEX IDX_D429523D84715E3B ON feature_value');
        $this->addSql('ALTER TABLE feature_value CHANGE project_item_id project_item_product_id INT NOT NULL');
        $this->addSql('ALTER TABLE feature_value ADD CONSTRAINT FK_D429523DC0043BA0 FOREIGN KEY (project_item_product_id) REFERENCES project_item_product (id)');
        $this->addSql('CREATE INDEX IDX_D429523DC0043BA0 ON feature_value (project_item_product_id)');
        $this->addSql('ALTER TABLE project_item DROP FOREIGN KEY FK_268AED064584665A');
        $this->addSql('DROP INDEX IDX_268AED064584665A ON project_item');
        $this->addSql('ALTER TABLE project_item DROP product_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feature_value DROP FOREIGN KEY FK_D429523DC0043BA0');
        $this->addSql('DROP TABLE project_item_product');
        $this->addSql('DROP INDEX IDX_D429523DC0043BA0 ON feature_value');
        $this->addSql('ALTER TABLE feature_value CHANGE project_item_product_id project_item_id INT NOT NULL');
        $this->addSql('ALTER TABLE feature_value ADD CONSTRAINT FK_D429523D84715E3B FOREIGN KEY (project_item_id) REFERENCES project_item (id)');
        $this->addSql('CREATE INDEX IDX_D429523D84715E3B ON feature_value (project_item_id)');
        $this->addSql('ALTER TABLE project_item ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE project_item ADD CONSTRAINT FK_268AED064584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_268AED064584665A ON project_item (product_id)');
    }
}
