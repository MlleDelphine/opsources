<?php
/**
 * Created by PhpStorm.
 * User: paul
 * Date: 06/08/15
 * Time: 13:42
 */

namespace AppBundle\Command;


use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;


class InstallCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:install')
            ->setDescription('Installation de l\'application')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->getFormatter()->setStyle('information', new OutputFormatterStyle('blue'));
        $output->getFormatter()->setStyle('success', new OutputFormatterStyle('green'));

        $dialog  = $this->getHelperSet()->get('dialog');
        do{
            $sure = $dialog->ask($output,"Installer l'application sur votre serveur (y/n)");
        }while(strtoupper($sure) !== "Y" && strtolower($sure) !== "N");
        if(strtoupper($sure) === "N")
            return;
        $this->install($output);
        $this->sqlQuery($output);
        $this->schemaUpdate($output);
        $this->fixtures($output);

        $output->writeln("\n<information>Anciennes fiches :</information>");
        $output->writeln("Vous devez maintenant créer un template via l'administration de l'application avec le fichier de configuration associé");
        do{
            $id = $dialog->ask($output,"Une fois créer veuillez indiquer l'id ici:");
        }while(is_int($id) && $this->templateExist($id) !== null);
        $this->changeTemplate($id);
        $output->writeln("\n<success>Mise en production finis</success>");

    }

    protected function install(OutputInterface $output)
    {
        $output->writeln("\n<information>Vendors :</information>");
        $c = shell_exec('php /usr/local/bin/composer.phar install');
        $output->writeln("\n<success>Vendors finis</success>");
    }

    private function sqlQuery(OutputInterface $output)
    {
        $output->writeln("\n<information>Requetes SQL:</information>");
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $connection = $em->getConnection();

        $queries = [
            "ALTER table opus_info RENAME to opus_campaign",
            "ALTER TABLE opus_sheet RENAME COLUMN info_id TO campaign_id"
        ];
        foreach($queries as $query)
        {
            try{
                $statement = $connection->prepare($query);
                $statement->execute();
            }catch (DBALException $p)
            {

            }catch(\PDOException $p){

            }

            $output->writeln($query);
        }
        $output->writeln("\n<success>SQL finis</success>");

    }

    private function fixtures(OutputInterface $output)
    {
        $output->writeln("\n<information>Fixtures SQL:</information>");
        $c = shell_exec('php app/console doctrine:fixtures:load --append');
        $output->writeln("\n<success>Fixtures finis</success>");

    }

    private function schemaUpdate(OutputInterface $output)
    {
        $output->writeln("\n<information>schema:update :</information>");
        $c = shell_exec('php app/console doctrine:schema:update --force');
        $output->writeln("\n<success>schema:update finis</success>");
    }

    private function templateExist($id)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $connection = $em->getConnection();
        $query = "SELECT * FROM opus_sheet_template WHERE id=:id";
        $statement = $connection->prepare($query);
        $statement->bindValue('id',$id);
        $statement->execute();
        dump($statement->fetch());die;
        return $statement->fetch();
    }

    private function changeTemplate($id)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $connection = $em->getConnection();
        $query = "UPDATE opus_sheet SET template_id = :id";
        $statement = $connection->prepare($query);
        $statement->bindValue('id',$id);
        $statement->execute();
    }


}
