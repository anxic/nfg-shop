<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241025202512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update image_url column (string) to image_data (json) to items table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE items ADD image_data JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE items DROP img_url');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E11EE94D5E237E06 ON items (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_E11EE94D5E237E06');
        $this->addSql('ALTER TABLE items ADD img_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE items DROP image_data');
    }
}
