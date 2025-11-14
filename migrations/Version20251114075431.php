<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251114075431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etudiant (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, filiere VARCHAR(255) NOT NULL, niveau VARCHAR(255) NOT NULL, numero_carte VARCHAR(255) NOT NULL, solde NUMERIC(10, 2) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_717E22E3E7927C74 ON etudiant (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_717E22E3158BD4C0 ON etudiant (numero_carte)');
        $this->addSql('CREATE TABLE ligne_panier (id SERIAL NOT NULL, etudiant_id INT NOT NULL, plat_id INT NOT NULL, quantite INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_21691B4DDEAB1A3 ON ligne_panier (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_21691B4D73DB560 ON ligne_panier (plat_id)');
        $this->addSql('COMMENT ON COLUMN ligne_panier.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE panier (id SERIAL NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_21691B4DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_21691B4D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ligne_panier DROP CONSTRAINT FK_21691B4DDEAB1A3');
        $this->addSql('ALTER TABLE ligne_panier DROP CONSTRAINT FK_21691B4D73DB560');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE ligne_panier');
        $this->addSql('DROP TABLE panier');
    }
}
