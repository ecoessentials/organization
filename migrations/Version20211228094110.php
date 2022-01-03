<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211228094110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feature_value ADD model_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE feature_value ADD CONSTRAINT FK_D429523D7975B7E7 FOREIGN KEY (model_id) REFERENCES project_item_model (id)');
        $this->addSql('CREATE INDEX IDX_D429523D7975B7E7 ON feature_value (model_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feature_value DROP FOREIGN KEY FK_D429523D7975B7E7');
        $this->addSql('DROP INDEX IDX_D429523D7975B7E7 ON feature_value');
        $this->addSql('ALTER TABLE feature_value DROP model_id');
    }
}
