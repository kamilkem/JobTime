<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230223235943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organization (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN organization.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE organization_user (user_id UUID NOT NULL, organization_id UUID NOT NULL, owner BOOLEAN NOT NULL, PRIMARY KEY(user_id, organization_id))');
        $this->addSql('CREATE INDEX IDX_B49AE8D4A76ED395 ON organization_user (user_id)');
        $this->addSql('CREATE INDEX IDX_B49AE8D432C8A3DE ON organization_user (organization_id)');
        $this->addSql('COMMENT ON COLUMN organization_user.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization_user.organization_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project (id UUID NOT NULL, project_group_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEC31A529C ON project (project_group_id)');
        $this->addSql('COMMENT ON COLUMN project.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project.project_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project_group (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN project_group.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE task (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN task.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE task_time_entry (id UUID NOT NULL, task_id UUID DEFAULT NULL, start_date TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_724037A38DB60186 ON task_time_entry (task_id)');
        $this->addSql('COMMENT ON COLUMN task_time_entry.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task_time_entry.task_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task_time_entry.start_date IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('COMMENT ON COLUMN task_time_entry.end_date IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, roles TEXT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, birth_date TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, confirmed BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".roles IS \'(DC2Type:simple_array)\'');
        $this->addSql('COMMENT ON COLUMN "user".birth_date IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D4A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D432C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEC31A529C FOREIGN KEY (project_group_id) REFERENCES project_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_time_entry ADD CONSTRAINT FK_724037A38DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE organization_user DROP CONSTRAINT FK_B49AE8D4A76ED395');
        $this->addSql('ALTER TABLE organization_user DROP CONSTRAINT FK_B49AE8D432C8A3DE');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEC31A529C');
        $this->addSql('ALTER TABLE task_time_entry DROP CONSTRAINT FK_724037A38DB60186');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE organization_user');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_group');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_time_entry');
        $this->addSql('DROP TABLE "user"');
    }
}
