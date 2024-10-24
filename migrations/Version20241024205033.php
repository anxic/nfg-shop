<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241024205033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add category, created_at and updated_at columns to items table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE items ADD category VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE items ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE items ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE items DROP category');
        $this->addSql('ALTER TABLE items DROP created_at');
        $this->addSql('ALTER TABLE items DROP updated_at');
    }
}
