<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211111134815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE feature_option (id INT AUTO_INCREMENT NOT NULL, option_id INT NOT NULL, feature_id INT NOT NULL, position INT NOT NULL, INDEX IDX_2393E61EA7C41D6F (option_id), INDEX IDX_2393E61E60E4B879 (feature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE feature_option ADD CONSTRAINT FK_2393E61EA7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id)');
        $this->addSql('ALTER TABLE feature_option ADD CONSTRAINT FK_2393E61E60E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feature_option DROP FOREIGN KEY FK_2393E61EA7C41D6F');
        $this->addSql('DROP TABLE feature_option');
        $this->addSql('DROP TABLE `option`');
    }
}
