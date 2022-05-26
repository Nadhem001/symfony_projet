<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220521224608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emprunt CHANGE date_sortie date_sortie DATE NOT NULL, CHANGE date_retour date_retour DATE NOT NULL');
        $this->addSql('ALTER TABLE livres CHANGE date_ajout date_ajout DATE NOT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE date_reservation date_reservation DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emprunt CHANGE date_sortie date_sortie DATETIME NOT NULL, CHANGE date_retour date_retour DATETIME NOT NULL');
        $this->addSql('ALTER TABLE livres CHANGE date_ajout date_ajout DATETIME NOT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE date_reservation date_reservation DATETIME NOT NULL');
    }
}
