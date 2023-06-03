<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230602221927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organization_member (id UUID NOT NULL, user_id UUID DEFAULT NULL, organization_id UUID DEFAULT NULL, owner BOOLEAN NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_756A2A8DA76ED395 ON organization_member (user_id)');
        $this->addSql('CREATE INDEX IDX_756A2A8D32C8A3DE ON organization_member (organization_id)');
        $this->addSql('COMMENT ON COLUMN organization_member.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization_member.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization_member.organization_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization_member.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('ALTER TABLE organization_member ADD CONSTRAINT FK_756A2A8DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organization_member ADD CONSTRAINT FK_756A2A8D32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organization_user DROP CONSTRAINT fk_b49ae8d4a76ed395');
        $this->addSql('ALTER TABLE organization_user DROP CONSTRAINT fk_b49ae8d432c8a3de');
        $this->addSql('DROP TABLE organization_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE organization_user (id UUID NOT NULL, user_id UUID DEFAULT NULL, organization_id UUID DEFAULT NULL, owner BOOLEAN NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_b49ae8d432c8a3de ON organization_user (organization_id)');
        $this->addSql('CREATE INDEX idx_b49ae8d4a76ed395 ON organization_user (user_id)');
        $this->addSql('COMMENT ON COLUMN organization_user.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization_user.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization_user.organization_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization_user.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('ALTER TABLE organization_user ADD CONSTRAINT fk_b49ae8d4a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organization_user ADD CONSTRAINT fk_b49ae8d432c8a3de FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organization_member DROP CONSTRAINT FK_756A2A8DA76ED395');
        $this->addSql('ALTER TABLE organization_member DROP CONSTRAINT FK_756A2A8D32C8A3DE');
        $this->addSql('DROP TABLE organization_member');
    }
}
