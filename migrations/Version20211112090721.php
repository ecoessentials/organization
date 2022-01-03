<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211112090721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE feature_option');
        $this->addSql('ALTER TABLE `option` ADD feature_id INT NOT NULL, ADD position INT NOT NULL');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B060E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id)');
        $this->addSql('CREATE INDEX IDX_5A8600B060E4B879 ON `option` (feature_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE feature_option (id INT AUTO_INCREMENT NOT NULL, option_id INT NOT NULL, feature_id INT NOT NULL, position INT NOT NULL, INDEX IDX_2393E61EA7C41D6F (option_id), INDEX IDX_2393E61E60E4B879 (feature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE feature_option ADD CONSTRAINT FK_2393E61E60E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id)');
        $this->addSql('ALTER TABLE feature_option ADD CONSTRAINT FK_2393E61EA7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id)');
        $this->addSql('ALTER TABLE `option` DROP FOREIGN KEY FK_5A8600B060E4B879');
        $this->addSql('DROP INDEX IDX_5A8600B060E4B879 ON `option`');
        $this->addSql('ALTER TABLE `option` DROP feature_id, DROP position');
    }
}
