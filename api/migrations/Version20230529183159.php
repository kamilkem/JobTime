<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230529183159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP CONSTRAINT fk_2fb3d0eefe54d947');
        $this->addSql('CREATE TABLE project_integration (id UUID NOT NULL, project_id UUID DEFAULT NULL, service_name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_35269A60166D1F9C ON project_integration (project_id)');
        $this->addSql('COMMENT ON COLUMN project_integration.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_integration.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_integration.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE user_integration (id UUID NOT NULL, user_id UUID DEFAULT NULL, service_name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, secret VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_54F2A40EA76ED395 ON user_integration (user_id)');
        $this->addSql('COMMENT ON COLUMN user_integration.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_integration.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_integration.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('ALTER TABLE project_integration ADD CONSTRAINT FK_35269A60166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_integration ADD CONSTRAINT FK_54F2A40EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_group DROP CONSTRAINT fk_7e954d5bb03a8386');
        $this->addSql('DROP TABLE project_group');
        $this->addSql('DROP INDEX idx_2fb3d0eefe54d947');
        $this->addSql('ALTER TABLE project RENAME COLUMN group_id TO organization_id');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE32C8A3DE ON project (organization_id)');
        $this->addSql('ALTER TABLE task ADD project_integration_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN task.project_integration_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB257735E948 FOREIGN KEY (project_integration_id) REFERENCES project_integration (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_527EDB257735E948 ON task (project_integration_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB257735E948');
        $this->addSql('CREATE TABLE project_group (id UUID NOT NULL, created_by_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_7e954d5bb03a8386 ON project_group (created_by_id)');
        $this->addSql('COMMENT ON COLUMN project_group.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_group.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_group.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('ALTER TABLE project_group ADD CONSTRAINT fk_7e954d5bb03a8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_integration DROP CONSTRAINT FK_35269A60166D1F9C');
        $this->addSql('ALTER TABLE user_integration DROP CONSTRAINT FK_54F2A40EA76ED395');
        $this->addSql('DROP TABLE project_integration');
        $this->addSql('DROP TABLE user_integration');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE32C8A3DE');
        $this->addSql('DROP INDEX IDX_2FB3D0EE32C8A3DE');
        $this->addSql('ALTER TABLE project RENAME COLUMN organization_id TO group_id');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT fk_2fb3d0eefe54d947 FOREIGN KEY (group_id) REFERENCES project_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_2fb3d0eefe54d947 ON project (group_id)');
        $this->addSql('DROP INDEX IDX_527EDB257735E948');
        $this->addSql('ALTER TABLE task DROP project_integration_id');
    }
}
