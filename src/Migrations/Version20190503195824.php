<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190503195824 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE nova_poshta_post_office ADD city_id INT NOT NULL');
        $this->addSql('ALTER TABLE nova_poshta_post_office ADD CONSTRAINT FK_C95AE62F8BAC62AF FOREIGN KEY (city_id) REFERENCES nova_poshta_city (id)');
        $this->addSql('CREATE INDEX IDX_C95AE62F8BAC62AF ON nova_poshta_post_office (city_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE nova_poshta_post_office DROP FOREIGN KEY FK_C95AE62F8BAC62AF');
        $this->addSql('DROP INDEX IDX_C95AE62F8BAC62AF ON nova_poshta_post_office');
        $this->addSql('ALTER TABLE nova_poshta_post_office DROP city_id');
    }
}
