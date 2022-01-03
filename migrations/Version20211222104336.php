<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211222104336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feature_value DROP FOREIGN KEY FK_D429523D80CD149D');
        $this->addSql('ALTER TABLE feature_value ADD CONSTRAINT FK_D429523D80CD149D FOREIGN KEY (feature_value_id) REFERENCES feature_value (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feature_value DROP FOREIGN KEY FK_D429523D80CD149D');
        $this->addSql('ALTER TABLE feature_value ADD CONSTRAINT FK_D429523D80CD149D FOREIGN KEY (feature_value_id) REFERENCES feature_value (id)');
    }
}
