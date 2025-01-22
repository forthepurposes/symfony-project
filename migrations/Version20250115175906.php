<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115175906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_data ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company_data ADD CONSTRAINT FK_EE9EE98D9D86650F FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EE9EE98D9D86650F ON company_data (user_id_id)');
        $this->addSql('ALTER TABLE invoice ALTER user_id DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE company_data DROP CONSTRAINT FK_EE9EE98D9D86650F');
        $this->addSql('DROP INDEX IDX_EE9EE98D9D86650F');
        $this->addSql('ALTER TABLE company_data DROP user_id');
        $this->addSql('ALTER TABLE invoice ALTER user_id SET DEFAULT 8');
    }
}
