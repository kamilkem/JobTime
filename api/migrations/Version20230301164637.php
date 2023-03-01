<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301164637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organization (id UUID NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN organization.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE organization_user (id UUID NOT NULL, user_id UUID DEFAULT NULL, organization_id UUID DEFAULT NULL, owner BOOLEAN NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B49AE8D4A76ED395 ON organization_user (user_id)');
        $this->addSql('CREATE INDEX IDX_B49AE8D432C8A3DE ON organization_user (organization_id)');
        $this->addSql('COMMENT ON COLUMN organization_user.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization_user.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization_user.organization_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN organization_user.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE project (id UUID NOT NULL, group_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEFE54D947 ON project (group_id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEB03A8386 ON project (created_by_id)');
        $this->addSql('COMMENT ON COLUMN project.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project.group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE project_group (id UUID NOT NULL, created_by_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7E954D5BB03A8386 ON project_group (created_by_id)');
        $this->addSql('COMMENT ON COLUMN project_group.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_group.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_group.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE task (id UUID NOT NULL, project_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_527EDB25166D1F9C ON task (project_id)');
        $this->addSql('CREATE INDEX IDX_527EDB25B03A8386 ON task (created_by_id)');
        $this->addSql('COMMENT ON COLUMN task.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE task_user (task_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(task_id, user_id))');
        $this->addSql('CREATE INDEX IDX_FE2042328DB60186 ON task_user (task_id)');
        $this->addSql('CREATE INDEX IDX_FE204232A76ED395 ON task_user (user_id)');
        $this->addSql('COMMENT ON COLUMN task_user.task_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task_user.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE task_time_entry (id UUID NOT NULL, task_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, start_date TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_724037A38DB60186 ON task_time_entry (task_id)');
        $this->addSql('CREATE INDEX IDX_724037A3B03A8386 ON task_time_entry (created_by_id)');
        $this->addSql('COMMENT ON COLUMN task_time_entry.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task_time_entry.task_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task_time_entry.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task_time_entry.start_date IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('COMMENT ON COLUMN task_time_entry.end_date IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('COMMENT ON COLUMN task_time_entry.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, roles TEXT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, birth_date TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, confirmed BOOLEAN NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".roles IS \'(DC2Type:simple_array)\'');
        $this->addSql('COMMENT ON COLUMN "user".birth_date IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D4A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D432C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEFE54D947 FOREIGN KEY (group_id) REFERENCES project_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_group ADD CONSTRAINT FK_7E954D5BB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_user ADD CONSTRAINT FK_FE2042328DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_user ADD CONSTRAINT FK_FE204232A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_time_entry ADD CONSTRAINT FK_724037A38DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_time_entry ADD CONSTRAINT FK_724037A3B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE organization_user DROP CONSTRAINT FK_B49AE8D4A76ED395');
        $this->addSql('ALTER TABLE organization_user DROP CONSTRAINT FK_B49AE8D432C8A3DE');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEFE54D947');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEB03A8386');
        $this->addSql('ALTER TABLE project_group DROP CONSTRAINT FK_7E954D5BB03A8386');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25166D1F9C');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25B03A8386');
        $this->addSql('ALTER TABLE task_user DROP CONSTRAINT FK_FE2042328DB60186');
        $this->addSql('ALTER TABLE task_user DROP CONSTRAINT FK_FE204232A76ED395');
        $this->addSql('ALTER TABLE task_time_entry DROP CONSTRAINT FK_724037A38DB60186');
        $this->addSql('ALTER TABLE task_time_entry DROP CONSTRAINT FK_724037A3B03A8386');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE organization_user');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_group');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_user');
        $this->addSql('DROP TABLE task_time_entry');
        $this->addSql('DROP TABLE "user"');
    }
}
