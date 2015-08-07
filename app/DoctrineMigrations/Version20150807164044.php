<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150807164044 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE opus_sheet DROP CONSTRAINT opus_sheet_ibfk_3');
        $this->addSql('CREATE SEQUENCE opus_sheet_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE opus_sheet_template_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE opus_sheet_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, str_code VARCHAR(255) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE opus_sheet_status (id SERIAL NOT NULL , label VARCHAR(255) NOT NULL, intCode INT NOT NULL, strCode VARCHAR(255) NOT NULL, PRIMARY KEY(intCode))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_817DDBAFBF396750 ON opus_sheet_status (id)');
        $this->addSql('CREATE TABLE opus_sheet_template (id INT NOT NULL, type_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, status BIGINT DEFAULT NULL, confFile_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7451860EC54C8C93 ON opus_sheet_template (type_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7451860EC16272AC ON opus_sheet_template (confFile_id)');
        $this->addSql('ALTER table opus_info RENAME to opus_campaign');
        $this->addSql('ALTER TABLE opus_campaign ADD COLUMN type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE opus_campaign ADD COLUMN template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE opus_campaign ADD COLUMN until_sheet_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_476A4B245DA0FB8 ON opus_campaign (template_id)');
        $this->addSql('CREATE TABLE media_media (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, enabled BOOLEAN NOT NULL, provider_name VARCHAR(255) NOT NULL, provider_status INT NOT NULL, provider_reference VARCHAR(255) NOT NULL, provider_metadata TEXT DEFAULT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, length NUMERIC(10, 0) DEFAULT NULL, content_type VARCHAR(255) DEFAULT NULL, content_size INT DEFAULT NULL, copyright VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) DEFAULT NULL, context VARCHAR(64) DEFAULT NULL, cdn_is_flushable BOOLEAN DEFAULT NULL, cdn_flush_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, cdn_status INT DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN media_media.provider_metadata IS \'(DC2Type:json)\'');
        $this->addSql('CREATE TABLE media_gallery (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, context VARCHAR(64) NOT NULL, default_format VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE media_gallery_has_media (id SERIAL NOT NULL, position INT NOT NULL, enabled BOOLEAN NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE opus_sheet_template ADD CONSTRAINT FK_7451860EC54C8C93 FOREIGN KEY (type_id) REFERENCES opus_sheet_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE opus_sheet_template ADD CONSTRAINT FK_7451860EC16272AC FOREIGN KEY (confFile_id) REFERENCES media_media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE opus_campaign ADD CONSTRAINT FK_476A4B24C54C8C93 FOREIGN KEY (type_id) REFERENCES opus_sheet_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE opus_campaign ADD CONSTRAINT FK_476A4B245DA0FB8 FOREIGN KEY (template_id) REFERENCES opus_sheet_template (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('INSERT INTO opus_sheet_status(label, intCode, strCode ) VALUES (\'Supprimée\',-1,\'supprimee\')');
        $this->addSql('INSERT INTO opus_sheet_status(label, intCode, strCode ) VALUES (\'Génerée\',1,\'generee\')');
        $this->addSql('INSERT INTO opus_sheet_status(label, intCode, strCode ) VALUES (\'En création\',2,\'creation\')');
        $this->addSql('INSERT INTO opus_sheet_status(label, intCode, strCode ) VALUES (\'Attente de validation évalué\',3,\'valid_evaluate\')');
        $this->addSql('INSERT INTO opus_sheet_status(label, intCode, strCode ) VALUES (\'Attente de validation évaluateur\',4,\'valid_evaluator\')');
        $this->addSql('INSERT INTO opus_sheet_status(label, intCode, strCode ) VALUES (\'Attente de validation second évaluateur\',5,\'valid_second_evaluator\')');
        $this->addSql('INSERT INTO opus_sheet_status(label, intCode, strCode ) VALUES (\'Attente de validation du directeur\',6,\'valid_director\')');
        $this->addSql('INSERT INTO opus_sheet_status(label, intCode, strCode ) VALUES (\'Attente de validation RH\',7,\'valid_rh\')');
        $this->addSql('INSERT INTO opus_sheet_status(label, intCode, strCode ) VALUES (\'Clôturée\',8,\'close\')');
        $this->addSql('INSERT INTO opus_sheet_status(label, intCode, strCode ) VALUES (\'Clôturée (non finie)\',9,\'close_finish\')');
        $this->addSql('INSERT INTO opus_sheet_status(label, intCode, strCode ) VALUES (\'Attente de validation définitive évaluateur \',10,\'valid_final_evaluator\')');
        $this->addSql('DROP INDEX opus_users_func_manager_id');
        $this->addSql('ALTER TABLE opus_users ADD roles TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE opus_users ADD sids TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE opus_users ADD password VARCHAR(32) DEFAULT NULL');
        $this->addSql('ALTER TABLE opus_users ADD salt VARCHAR(32) DEFAULT NULL');
        $this->addSql('ALTER TABLE opus_users ADD fullname VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE opus_users ADD username VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE opus_users ALTER id TYPE INT');
        $this->addSql('ALTER TABLE opus_users ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE opus_users ALTER manager_id TYPE INT');
        $this->addSql('ALTER TABLE opus_users ALTER job_id DROP NOT NULL');
        $this->addSql('ALTER TABLE opus_users ALTER responsable DROP DEFAULT');
        $this->addSql('ALTER TABLE opus_users ALTER last_name DROP NOT NULL');
        $this->addSql('ALTER TABLE opus_users ALTER last_name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE opus_users ALTER first_name DROP NOT NULL');
        $this->addSql('ALTER TABLE opus_users ALTER first_name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE opus_users ALTER login TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE opus_users ALTER mail DROP NOT NULL');
        $this->addSql('ALTER TABLE opus_users ALTER division TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE opus_users ALTER department TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE opus_users ALTER classification TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN opus_users.roles IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN opus_users.sids IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE opus_attribute ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN opus_attribute.value_base64 IS NULL');
        $this->addSql('DROP INDEX opus_sheet_info_id');
        $this->addSql('ALTER TABLE opus_sheet ADD template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE opus_sheet RENAME COLUMN info_id TO campaign_id');
        $this->addSql('ALTER TABLE opus_sheet ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE opus_sheet ALTER evaluate_id TYPE INT');
        $this->addSql('ALTER TABLE opus_sheet ALTER evaluate_id DROP NOT NULL');
        $this->addSql('ALTER TABLE opus_sheet ALTER evaluator_id TYPE INT');
        $this->addSql('ALTER TABLE opus_sheet ALTER superior_id TYPE INT');
        $this->addSql('ALTER TABLE opus_sheet ALTER director_id TYPE INT');
        $this->addSql('ALTER TABLE opus_sheet ALTER responsable_id TYPE INT');
        $this->addSql('ALTER TABLE opus_sheet ALTER status TYPE INT');
        $this->addSql('ALTER TABLE opus_sheet ALTER status DROP NOT NULL');
        $this->addSql('ALTER TABLE opus_sheet ADD CONSTRAINT FK_B6337DC143575BE2 FOREIGN KEY (evaluator_id) REFERENCES opus_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE opus_sheet ADD CONSTRAINT FK_B6337DC163D7ADF1 FOREIGN KEY (superior_id) REFERENCES opus_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE opus_sheet ADD CONSTRAINT FK_B6337DC1899FB366 FOREIGN KEY (director_id) REFERENCES opus_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE opus_sheet ADD CONSTRAINT FK_B6337DC153C59D72 FOREIGN KEY (responsable_id) REFERENCES opus_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE opus_sheet ADD CONSTRAINT FK_B6337DC17B00651C FOREIGN KEY (status) REFERENCES opus_sheet_status (intCode) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE opus_sheet ADD CONSTRAINT FK_B6337DC1F639F774 FOREIGN KEY (campaign_id) REFERENCES opus_campaign (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE opus_sheet ADD CONSTRAINT FK_B6337DC15DA0FB8 FOREIGN KEY (template_id) REFERENCES opus_sheet_template (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B6337DC143575BE2 ON opus_sheet (evaluator_id)');
        $this->addSql('CREATE INDEX IDX_B6337DC163D7ADF1 ON opus_sheet (superior_id)');
        $this->addSql('CREATE INDEX IDX_B6337DC1899FB366 ON opus_sheet (director_id)');
        $this->addSql('CREATE INDEX IDX_B6337DC153C59D72 ON opus_sheet (responsable_id)');
        $this->addSql('CREATE INDEX IDX_B6337DC17B00651C ON opus_sheet (status)');
        $this->addSql('CREATE INDEX IDX_B6337DC1F639F774 ON opus_sheet (campaign_id)');
        $this->addSql('CREATE INDEX IDX_B6337DC15DA0FB8 ON opus_sheet (template_id)');
        $this->addSql('ALTER TABLE opus_job ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE opus_log ALTER id DROP DEFAULT');
        $this->addSql('DROP INDEX opus_collection_info_id');
        $this->addSql('ALTER TABLE opus_collection DROP info_id');
        $this->addSql('ALTER TABLE opus_collection ALTER id DROP DEFAULT');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE opus_sheet_template DROP CONSTRAINT FK_7451860EC54C8C93');
        $this->addSql('ALTER TABLE opus_campaign DROP CONSTRAINT FK_476A4B24C54C8C93');
        $this->addSql('ALTER TABLE opus_sheet DROP CONSTRAINT FK_B6337DC17B00651C');
        $this->addSql('ALTER TABLE opus_sheet DROP CONSTRAINT FK_B6337DC15DA0FB8');
        $this->addSql('ALTER TABLE opus_campaign DROP CONSTRAINT FK_476A4B245DA0FB8');
        $this->addSql('ALTER TABLE opus_sheet DROP CONSTRAINT FK_B6337DC1F639F774');
        $this->addSql('ALTER TABLE opus_sheet_template DROP CONSTRAINT FK_7451860EC16272AC');
        $this->addSql('DROP SEQUENCE opus_sheet_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE opus_sheet_template_id_seq CASCADE');
        $this->addSql('CREATE TABLE opus_info (id BIGSERIAL NOT NULL, year BIGINT NOT NULL, template BYTEA DEFAULT NULL, mail_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, limit_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, status BIGINT DEFAULT \'0\', created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE opus_sheet_type');
        $this->addSql('DROP TABLE opus_sheet_status');
        $this->addSql('DROP TABLE opus_sheet_template');
        $this->addSql('DROP TABLE opus_campaign');
        $this->addSql('DROP TABLE media_media');
        $this->addSql('DROP TABLE media_gallery');
        $this->addSql('DROP TABLE media_gallery_has_media');
        $this->addSql('CREATE SEQUENCE opus_log_id_seq');
        $this->addSql('SELECT setval(\'opus_log_id_seq\', (SELECT MAX(id) FROM opus_log))');
        $this->addSql('ALTER TABLE opus_log ALTER id SET DEFAULT nextval(\'opus_log_id_seq\')');
        $this->addSql('ALTER TABLE opus_users DROP roles');
        $this->addSql('ALTER TABLE opus_users DROP sids');
        $this->addSql('ALTER TABLE opus_users DROP password');
        $this->addSql('ALTER TABLE opus_users DROP salt');
        $this->addSql('ALTER TABLE opus_users DROP fullname');
        $this->addSql('ALTER TABLE opus_users DROP username');
        $this->addSql('ALTER TABLE opus_users ALTER id TYPE BIGSERIAL');
        $this->addSql('CREATE SEQUENCE opus_users_id_seq');
        $this->addSql('SELECT setval(\'opus_users_id_seq\', (SELECT MAX(id) FROM opus_users))');
        $this->addSql('ALTER TABLE opus_users ALTER id SET DEFAULT nextval(\'opus_users_id_seq\')');
        $this->addSql('ALTER TABLE opus_users ALTER manager_id TYPE BIGINT');
        $this->addSql('ALTER TABLE opus_users ALTER job_id SET NOT NULL');
        $this->addSql('ALTER TABLE opus_users ALTER responsable SET DEFAULT \'0\'');
        $this->addSql('ALTER TABLE opus_users ALTER last_name SET NOT NULL');
        $this->addSql('ALTER TABLE opus_users ALTER last_name TYPE VARCHAR(128)');
        $this->addSql('ALTER TABLE opus_users ALTER first_name SET NOT NULL');
        $this->addSql('ALTER TABLE opus_users ALTER first_name TYPE VARCHAR(128)');
        $this->addSql('ALTER TABLE opus_users ALTER login TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE opus_users ALTER mail SET NOT NULL');
        $this->addSql('ALTER TABLE opus_users ALTER division TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE opus_users ALTER department TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE opus_users ALTER classification TYPE VARCHAR(32)');
        $this->addSql('CREATE INDEX opus_users_func_manager_id ON opus_users (func_manager_id)');
        $this->addSql('CREATE SEQUENCE opus_attribute_id_seq');
        $this->addSql('SELECT setval(\'opus_attribute_id_seq\', (SELECT MAX(id) FROM opus_attribute))');
        $this->addSql('ALTER TABLE opus_attribute ALTER id SET DEFAULT nextval(\'opus_attribute_id_seq\')');
        $this->addSql('ALTER TABLE opus_collection ADD info_id BIGINT DEFAULT NULL');
        $this->addSql('CREATE SEQUENCE opus_collection_id_seq');
        $this->addSql('SELECT setval(\'opus_collection_id_seq\', (SELECT MAX(id) FROM opus_collection))');
        $this->addSql('ALTER TABLE opus_collection ALTER id SET DEFAULT nextval(\'opus_collection_id_seq\')');
        $this->addSql('CREATE INDEX opus_collection_info_id ON opus_collection (info_id)');
        $this->addSql('DROP INDEX IDX_B6337DC143575BE2');
        $this->addSql('DROP INDEX IDX_B6337DC163D7ADF1');
        $this->addSql('DROP INDEX IDX_B6337DC1899FB366');
        $this->addSql('DROP INDEX IDX_B6337DC153C59D72');
        $this->addSql('DROP INDEX IDX_B6337DC17B00651C');
        $this->addSql('DROP INDEX IDX_B6337DC1F639F774');
        $this->addSql('DROP INDEX IDX_B6337DC15DA0FB8');
        $this->addSql('ALTER TABLE opus_sheet ADD info_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE opus_sheet DROP campaign_id');
        $this->addSql('ALTER TABLE opus_sheet DROP template_id');
        $this->addSql('CREATE SEQUENCE opus_sheet_id_seq');
        $this->addSql('SELECT setval(\'opus_sheet_id_seq\', (SELECT MAX(id) FROM opus_sheet))');
        $this->addSql('ALTER TABLE opus_sheet ALTER id SET DEFAULT nextval(\'opus_sheet_id_seq\')');
        $this->addSql('ALTER TABLE opus_sheet ALTER evaluate_id TYPE BIGINT');
        $this->addSql('ALTER TABLE opus_sheet ALTER evaluate_id SET NOT NULL');
        $this->addSql('ALTER TABLE opus_sheet ALTER evaluator_id TYPE BIGINT');
        $this->addSql('ALTER TABLE opus_sheet ALTER superior_id TYPE BIGINT');
        $this->addSql('ALTER TABLE opus_sheet ALTER director_id TYPE BIGINT');
        $this->addSql('ALTER TABLE opus_sheet ALTER responsable_id TYPE BIGINT');
        $this->addSql('ALTER TABLE opus_sheet ALTER status TYPE BIGINT');
        $this->addSql('ALTER TABLE opus_sheet ALTER status SET NOT NULL');
        $this->addSql('ALTER TABLE opus_sheet ADD CONSTRAINT opus_sheet_ibfk_3 FOREIGN KEY (info_id) REFERENCES opus_info (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX opus_sheet_info_id ON opus_sheet (info_id)');
        $this->addSql('CREATE SEQUENCE opus_job_id_seq');
        $this->addSql('SELECT setval(\'opus_job_id_seq\', (SELECT MAX(id) FROM opus_job))');
        $this->addSql('ALTER TABLE opus_job ALTER id SET DEFAULT nextval(\'opus_job_id_seq\')');
    }
}
