<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211227092247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_item_model (id INT AUTO_INCREMENT NOT NULL, project_item_id INT NOT NULL, count INT NOT NULL, quantities LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', reference VARCHAR(255) DEFAULT NULL, INDEX IDX_E594123B84715E3B (project_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_item_model ADD CONSTRAINT FK_E594123B84715E3B FOREIGN KEY (project_item_id) REFERENCES project_item (id)');
        $this->addSql('ALTER TABLE project_item DROP quantities, DROP model_count, DROP quantities_by_model');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE project_item_model');
        $this->addSql('ALTER TABLE project_item ADD quantities LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:simple_array)\', ADD model_count INT DEFAULT 1 NOT NULL, ADD quantities_by_model TINYINT(1) DEFAULT \'1\' NOT NULL');
    }
}
