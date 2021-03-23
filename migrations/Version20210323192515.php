<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210323192515 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FB46B9EE8');
        $this->addSql('DROP INDEX IDX_C53D045FB46B9EE8 ON image');
        $this->addSql('ALTER TABLE image CHANGE trick_id_id trick_id INT NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_C53D045FB281BE2E ON image (trick_id)');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2CB46B9EE8');
        $this->addSql('DROP INDEX IDX_7CC7DA2CB46B9EE8 ON video');
        $this->addSql('ALTER TABLE video CHANGE trick_id_id trick_id INT NOT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2CB281BE2E ON video (trick_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FB281BE2E');
        $this->addSql('DROP INDEX IDX_C53D045FB281BE2E ON image');
        $this->addSql('ALTER TABLE image CHANGE trick_id trick_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FB46B9EE8 FOREIGN KEY (trick_id_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_C53D045FB46B9EE8 ON image (trick_id_id)');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2CB281BE2E');
        $this->addSql('DROP INDEX IDX_7CC7DA2CB281BE2E ON video');
        $this->addSql('ALTER TABLE video CHANGE trick_id trick_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CB46B9EE8 FOREIGN KEY (trick_id_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2CB46B9EE8 ON video (trick_id_id)');
    }
}