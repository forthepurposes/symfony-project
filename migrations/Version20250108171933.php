<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108171933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application (id SERIAL NOT NULL, name VARCHAR(160) NOT NULL, description VARCHAR(3000) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE company_data (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, street_number VARCHAR(255) NOT NULL, flat_number VARCHAR(10) DEFAULT NULL, post_code VARCHAR(10) NOT NULL, city VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, phone VARCHAR(15) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE invoice (id SERIAL NOT NULL, company_name VARCHAR(255) NOT NULL, company_street VARCHAR(100) NOT NULL, company_street_number VARCHAR(10) NOT NULL, company_street_flat_number VARCHAR(10) DEFAULT NULL, company_city VARCHAR(100) NOT NULL, company_post_code VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_data (id SERIAL NOT NULL, name VARCHAR(50) NOT NULL, surname VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE company_data');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE user_data');
    }
}
