<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220209144409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE enfant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE enfant_activite (enfant_id INTEGER NOT NULL, activite_id INTEGER NOT NULL, PRIMARY KEY(enfant_id, activite_id))');
        $this->addSql('CREATE INDEX IDX_71719BC5450D2529 ON enfant_activite (enfant_id)');
        $this->addSql('CREATE INDEX IDX_71719BC59B0F88B1 ON enfant_activite (activite_id)');
        $this->addSql('DROP INDEX IDX_B87555157F05C301');
        $this->addSql('CREATE TEMPORARY TABLE __temp__activite AS SELECT id, animateur_id, nom, description FROM activite');
        $this->addSql('DROP TABLE activite');
        $this->addSql('CREATE TABLE activite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, animateur_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, CONSTRAINT FK_B87555157F05C301 FOREIGN KEY (animateur_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO activite (id, animateur_id, nom, description) SELECT id, animateur_id, nom, description FROM __temp__activite');
        $this->addSql('DROP TABLE __temp__activite');
        $this->addSql('CREATE INDEX IDX_B87555157F05C301 ON activite (animateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE enfant');
        $this->addSql('DROP TABLE enfant_activite');
        $this->addSql('DROP INDEX IDX_B87555157F05C301');
        $this->addSql('CREATE TEMPORARY TABLE __temp__activite AS SELECT id, animateur_id, nom, description FROM activite');
        $this->addSql('DROP TABLE activite');
        $this->addSql('CREATE TABLE activite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, animateur_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO activite (id, animateur_id, nom, description) SELECT id, animateur_id, nom, description FROM __temp__activite');
        $this->addSql('DROP TABLE __temp__activite');
        $this->addSql('CREATE INDEX IDX_B87555157F05C301 ON activite (animateur_id)');
    }
}
