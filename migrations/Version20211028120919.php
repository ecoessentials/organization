<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211028120919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_item (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, chosen_supplier_estimate_id INT DEFAULT NULL, description LONGTEXT NOT NULL, INDEX IDX_268AED06166D1F9C (project_id), UNIQUE INDEX UNIQ_268AED06A465347 (chosen_supplier_estimate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_item_supplier_estimate (id INT AUTO_INCREMENT NOT NULL, supplier_id INT NOT NULL, project_item_id INT NOT NULL, INDEX IDX_441CA6A32ADD6D8C (supplier_id), INDEX IDX_441CA6A384715E3B (project_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_item ADD CONSTRAINT FK_268AED06166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_item ADD CONSTRAINT FK_268AED06A465347 FOREIGN KEY (chosen_supplier_estimate_id) REFERENCES project_item_supplier_estimate (id)');
        $this->addSql('ALTER TABLE project_item_supplier_estimate ADD CONSTRAINT FK_441CA6A32ADD6D8C FOREIGN KEY (supplier_id) REFERENCES third_party (id)');
        $this->addSql('ALTER TABLE project_item_supplier_estimate ADD CONSTRAINT FK_441CA6A384715E3B FOREIGN KEY (project_item_id) REFERENCES project_item (id)');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE9395C3F3');
        $this->addSql('DROP INDEX IDX_2FB3D0EE9395C3F3 ON project');
        $this->addSql('ALTER TABLE project DROP customer_id');
        $this->addSql('ALTER TABLE third_party ADD email VARCHAR(255) DEFAULT NULL, ADD telephone VARCHAR(255) DEFAULT NULL, ADD website VARCHAR(255) DEFAULT NULL, ADD customer TINYINT(1) NOT NULL, ADD supplier TINYINT(1) NOT NULL, ADD postal_address_street LONGTEXT NOT NULL, ADD postal_address_postal_code VARCHAR(255) NOT NULL, ADD postal_address_city VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_item_supplier_estimate DROP FOREIGN KEY FK_441CA6A384715E3B');
        $this->addSql('ALTER TABLE project_item DROP FOREIGN KEY FK_268AED06A465347');
        $this->addSql('DROP TABLE project_item');
        $this->addSql('DROP TABLE project_item_supplier_estimate');
        $this->addSql('ALTER TABLE project ADD customer_id INT NOT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE9395C3F3 FOREIGN KEY (customer_id) REFERENCES third_party (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE9395C3F3 ON project (customer_id)');
        $this->addSql('ALTER TABLE third_party DROP email, DROP telephone, DROP website, DROP customer, DROP supplier, DROP postal_address_street, DROP postal_address_postal_code, DROP postal_address_city');
    }
}
