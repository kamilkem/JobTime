<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231214164542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE directory (id UUID NOT NULL, space_id UUID DEFAULT NULL, directory_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_467844DA23575340 ON directory (space_id)');
        $this->addSql('CREATE INDEX IDX_467844DA2C94069F ON directory (directory_id)');
        $this->addSql('CREATE INDEX IDX_467844DAB03A8386 ON directory (created_by_id)');
        $this->addSql('COMMENT ON COLUMN directory.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN directory.space_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN directory.directory_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN directory.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN directory.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE invitation (id UUID NOT NULL, team_id UUID DEFAULT NULL, user_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, invitation_email VARCHAR(255) NOT NULL, accepted_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL, canceled_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F11D61A2296CD8AE ON invitation (team_id)');
        $this->addSql('CREATE INDEX IDX_F11D61A2A76ED395 ON invitation (user_id)');
        $this->addSql('CREATE INDEX IDX_F11D61A2B03A8386 ON invitation (created_by_id)');
        $this->addSql('COMMENT ON COLUMN invitation.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invitation.team_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invitation.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invitation.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invitation.accepted_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('COMMENT ON COLUMN invitation.canceled_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('COMMENT ON COLUMN invitation.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE member (id UUID NOT NULL, user_id UUID DEFAULT NULL, team_id UUID DEFAULT NULL, owner BOOLEAN NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_70E4FA78A76ED395 ON member (user_id)');
        $this->addSql('CREATE INDEX IDX_70E4FA78296CD8AE ON member (team_id)');
        $this->addSql('COMMENT ON COLUMN member.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN member.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN member.team_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN member.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE refresh_token (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74F2195C74F2195 ON refresh_token (refresh_token)');
        $this->addSql('CREATE TABLE space (id UUID NOT NULL, team_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2972C13A296CD8AE ON space (team_id)');
        $this->addSql('CREATE INDEX IDX_2972C13AB03A8386 ON space (created_by_id)');
        $this->addSql('COMMENT ON COLUMN space.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN space.team_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN space.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN space.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE task (id UUID NOT NULL, view_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_527EDB2531518C7 ON task (view_id)');
        $this->addSql('CREATE INDEX IDX_527EDB25B03A8386 ON task (created_by_id)');
        $this->addSql('COMMENT ON COLUMN task.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task.view_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE task_user (task_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(task_id, user_id))');
        $this->addSql('CREATE INDEX IDX_FE2042328DB60186 ON task_user (task_id)');
        $this->addSql('CREATE INDEX IDX_FE204232A76ED395 ON task_user (user_id)');
        $this->addSql('COMMENT ON COLUMN task_user.task_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task_user.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE team (id UUID NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN team.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN team.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE time_entry (id UUID NOT NULL, task_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, start_date TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6E537C0C8DB60186 ON time_entry (task_id)');
        $this->addSql('CREATE INDEX IDX_6E537C0CB03A8386 ON time_entry (created_by_id)');
        $this->addSql('COMMENT ON COLUMN time_entry.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN time_entry.task_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN time_entry.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN time_entry.start_date IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('COMMENT ON COLUMN time_entry.end_date IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('COMMENT ON COLUMN time_entry.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, confirmed BOOLEAN NOT NULL, roles TEXT NOT NULL, password VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".roles IS \'(DC2Type:simple_array)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE user_integration (id UUID NOT NULL, user_id UUID DEFAULT NULL, service_name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, secret VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_54F2A40EA76ED395 ON user_integration (user_id)');
        $this->addSql('COMMENT ON COLUMN user_integration.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_integration.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_integration.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE view (id UUID NOT NULL, directory_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FEFDAB8E2C94069F ON view (directory_id)');
        $this->addSql('CREATE INDEX IDX_FEFDAB8EB03A8386 ON view (created_by_id)');
        $this->addSql('COMMENT ON COLUMN view.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN view.directory_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN view.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN view.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('ALTER TABLE directory ADD CONSTRAINT FK_467844DA23575340 FOREIGN KEY (space_id) REFERENCES space (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE directory ADD CONSTRAINT FK_467844DA2C94069F FOREIGN KEY (directory_id) REFERENCES directory (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE directory ADD CONSTRAINT FK_467844DAB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE space ADD CONSTRAINT FK_2972C13A296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE space ADD CONSTRAINT FK_2972C13AB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2531518C7 FOREIGN KEY (view_id) REFERENCES view (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_user ADD CONSTRAINT FK_FE2042328DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_user ADD CONSTRAINT FK_FE204232A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE time_entry ADD CONSTRAINT FK_6E537C0C8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE time_entry ADD CONSTRAINT FK_6E537C0CB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_integration ADD CONSTRAINT FK_54F2A40EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE view ADD CONSTRAINT FK_FEFDAB8E2C94069F FOREIGN KEY (directory_id) REFERENCES directory (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE view ADD CONSTRAINT FK_FEFDAB8EB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('ALTER TABLE directory DROP CONSTRAINT FK_467844DA23575340');
        $this->addSql('ALTER TABLE directory DROP CONSTRAINT FK_467844DA2C94069F');
        $this->addSql('ALTER TABLE directory DROP CONSTRAINT FK_467844DAB03A8386');
        $this->addSql('ALTER TABLE invitation DROP CONSTRAINT FK_F11D61A2296CD8AE');
        $this->addSql('ALTER TABLE invitation DROP CONSTRAINT FK_F11D61A2A76ED395');
        $this->addSql('ALTER TABLE invitation DROP CONSTRAINT FK_F11D61A2B03A8386');
        $this->addSql('ALTER TABLE member DROP CONSTRAINT FK_70E4FA78A76ED395');
        $this->addSql('ALTER TABLE member DROP CONSTRAINT FK_70E4FA78296CD8AE');
        $this->addSql('ALTER TABLE space DROP CONSTRAINT FK_2972C13A296CD8AE');
        $this->addSql('ALTER TABLE space DROP CONSTRAINT FK_2972C13AB03A8386');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB2531518C7');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25B03A8386');
        $this->addSql('ALTER TABLE task_user DROP CONSTRAINT FK_FE2042328DB60186');
        $this->addSql('ALTER TABLE task_user DROP CONSTRAINT FK_FE204232A76ED395');
        $this->addSql('ALTER TABLE time_entry DROP CONSTRAINT FK_6E537C0C8DB60186');
        $this->addSql('ALTER TABLE time_entry DROP CONSTRAINT FK_6E537C0CB03A8386');
        $this->addSql('ALTER TABLE user_integration DROP CONSTRAINT FK_54F2A40EA76ED395');
        $this->addSql('ALTER TABLE view DROP CONSTRAINT FK_FEFDAB8E2C94069F');
        $this->addSql('ALTER TABLE view DROP CONSTRAINT FK_FEFDAB8EB03A8386');
        $this->addSql('DROP TABLE directory');
        $this->addSql('DROP TABLE invitation');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE space');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_user');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE time_entry');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_integration');
        $this->addSql('DROP TABLE view');
    }
}
