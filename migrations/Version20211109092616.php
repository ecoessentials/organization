<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211109092616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE feature (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', default_label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feature_value (id INT AUTO_INCREMENT NOT NULL, product_feature_id INT NOT NULL, project_item_id INT NOT NULL, integer_value INT DEFAULT NULL, boolean_value TINYINT(1) DEFAULT NULL, storage_type VARCHAR(255) NOT NULL, INDEX IDX_D429523DF383E752 (product_feature_id), INDEX IDX_D429523D84715E3B (project_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_feature (id INT AUTO_INCREMENT NOT NULL, feature_id INT NOT NULL, product_id INT NOT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_CE0E6ED660E4B879 (feature_id), INDEX IDX_CE0E6ED64584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE feature_value ADD CONSTRAINT FK_D429523DF383E752 FOREIGN KEY (product_feature_id) REFERENCES product_feature (id)');
        $this->addSql('ALTER TABLE feature_value ADD CONSTRAINT FK_D429523D84715E3B FOREIGN KEY (project_item_id) REFERENCES project_item (id)');
        $this->addSql('ALTER TABLE product_feature ADD CONSTRAINT FK_CE0E6ED660E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id)');
        $this->addSql('ALTER TABLE product_feature ADD CONSTRAINT FK_CE0E6ED64584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE project_item ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE project_item ADD CONSTRAINT FK_268AED064584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_268AED064584665A ON project_item (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_feature DROP FOREIGN KEY FK_CE0E6ED660E4B879');
        $this->addSql('ALTER TABLE product_feature DROP FOREIGN KEY FK_CE0E6ED64584665A');
        $this->addSql('ALTER TABLE project_item DROP FOREIGN KEY FK_268AED064584665A');
        $this->addSql('ALTER TABLE feature_value DROP FOREIGN KEY FK_D429523DF383E752');
        $this->addSql('DROP TABLE feature');
        $this->addSql('DROP TABLE feature_value');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_feature');
        $this->addSql('DROP INDEX IDX_268AED064584665A ON project_item');
        $this->addSql('ALTER TABLE project_item DROP product_id');
    }
}
