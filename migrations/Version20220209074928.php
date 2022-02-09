<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220209074928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__activite AS SELECT id, nom, description FROM activite');
        $this->addSql('DROP TABLE activite');
        $this->addSql('CREATE TABLE activite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, animateur_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, CONSTRAINT FK_B87555157F05C301 FOREIGN KEY (animateur_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO activite (id, nom, description) SELECT id, nom, description FROM __temp__activite');
        $this->addSql('DROP TABLE __temp__activite');
        $this->addSql('CREATE INDEX IDX_B87555157F05C301 ON activite (animateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_B87555157F05C301');
        $this->addSql('CREATE TEMPORARY TABLE __temp__activite AS SELECT id, nom, description FROM activite');
        $this->addSql('DROP TABLE activite');
        $this->addSql('CREATE TABLE activite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO activite (id, nom, description) SELECT id, nom, description FROM __temp__activite');
        $this->addSql('DROP TABLE __temp__activite');
    }
}
