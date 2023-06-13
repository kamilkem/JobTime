<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613164815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organization_invitation (id UUID NOT NULL, organization_id UUID DEFAULT NULL, user_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, accepted_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL, canceled_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL, invitation_email VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1846F34D32C8A3DE ON organization_invitation (organization_id)');
        $this->addSql('CREATE INDEX IDX_1846F34DA76ED395 ON organization_invitation (user_id)');
        $this->addSql('CREATE INDEX IDX_1846F34DB03A8386 ON organization_invitation (created_by_id)');
        $this->addSql('COMMENT ON COLUMN organization_invitation.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization_invitation.organization_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization_invitation.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization_invitation.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization_invitation.accepted_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('COMMENT ON COLUMN organization_invitation.canceled_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('COMMENT ON COLUMN organization_invitation.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('ALTER TABLE organization_invitation ADD CONSTRAINT FK_1846F34D32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organization_invitation ADD CONSTRAINT FK_1846F34DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organization_invitation ADD CONSTRAINT FK_1846F34DB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE organization_invitation DROP CONSTRAINT FK_1846F34D32C8A3DE');
        $this->addSql('ALTER TABLE organization_invitation DROP CONSTRAINT FK_1846F34DA76ED395');
        $this->addSql('ALTER TABLE organization_invitation DROP CONSTRAINT FK_1846F34DB03A8386');
        $this->addSql('DROP TABLE organization_invitation');
    }
}
