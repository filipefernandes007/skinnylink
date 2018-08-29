<?php

declare(strict_types=1);

namespace AppBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180829090146 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add to skinny_link url fulltext index';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE FULLTEXT INDEX URL_IDX ON skinny_link(url)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX URL_IDX ON skinny_link');
    }

}
