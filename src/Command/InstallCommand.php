<?php

namespace App\Command;
 
use Doctrine\DBAL\Connection; 
use Symfony\Component\Process\Process; 
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface; 
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:install',
    description: 'This command installs project',
)]
class InstallCommand extends Command
{

    private string $projectRoot = ''; 
    private Connection $connection;

    public function __construct(KernelInterface $kernel, Connection $connection)
    {
        parent::__construct(); 
        $this->projectRoot = $kernel->getProjectDir();
        $this->connection = $connection;
        /* $this->productRepository = $productRepository; */
        /* $this->entityManager = $entityManager; */
    }
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper("question");
    
        $io->writeln('');
        //$this->installComposer();
        $io->writeln('');    

        $io->info("==== Please answer below questions with your system credentials ==== ");
        

        $this->setEnv($helper, $input, $output);
        $this->setProgressBar($io, $output);
        $this->createDb();
        $this->startServerAndOpenBrowser($io);
        
        return Command::SUCCESS;
    }

    private function createDb(){
        $createDB = new Process(['php','bin/console','doctrine:database:create']);
        $createDB->run();

        $migrateTable = new Process(['php' , 'bin/console', 'doctrine:migrations:migrate']);
        $migrateTable->run();

       /*  $loadFixtures = new Process(['php' , 'bin/console', 'doctrine:fixtures:load']);
        $loadFixtures->run(); */
    }

    private function startServerAndOpenBrowser($io){
        $startServer = new Process(['symfony','server:start', '-d']);
        $openBrowser = new Process(['symfony','open:local']);
        $startServer->setWorkingDirectory($this->projectRoot);
        $startServer->run();
        $io->success("IMPORTANT: To get API product list visit: http://127.0.0.1:8000/products");
        $io->success("** Your development server started: http://127.0.0.1:8000 and opening in browser. ");
        sleep(2);
        $openBrowser->run(); 
    }

    private function setProgressBar($io, $output, int $max = 5){
        $io->writeln('');

        $progressBar = new ProgressBar($output,$max);
        $progressBar->maxSecondsBetweenRedraws(0.8);
        $progressBar->start();

        for($i = 0; $i<=100; $i++){
            $progressBar->advance();
        }

        $progressBar->finish();

        $io->writeln('');
    }

    private function setEnv($helper, $input, $output){
        $question1 = new Question("Database Name ". ("[mytheresa_muratcan]: "), 'mytheresa_muratcan');
        $question2 = new Question("Database User ". ("[root]: "), 'root');
        $question3 = new Question("Database Password ". ("[empty]: "), '');
        $question4 = new Question("Database Host ". ("[127.0.0.1]: "), '127.0.0.1');
        $question5 = new Question("Database Port ". ("[3306]: "), '3306'); 
        
        $dbName = ($helper->ask($input, $output, $question1));
        $dbUser = ($helper->ask($input, $output, $question2));
        $dbPass = ($helper->ask($input, $output, $question3));
        $dbHost = ($helper->ask($input, $output, $question4));
        $dbPort = ($helper->ask($input, $output, $question5));
 
        $envStub = file_get_contents($this->projectRoot."/"."env.stub");
        $dbUrlStr = "mysql://$dbUser:$dbPass@$dbHost:$dbPort/$dbName?charset=utf8mb4";
        $envStub = str_replace('%%DB_URL%%', 'DATABASE_URL="'.$dbUrlStr.'"', $envStub);
        
        file_put_contents(".env", $envStub);
    }

    private function setProductTable(){
        $productsArray = json_decode(file_get_contents($this->projectRoot."/products.json"), true);
        $insertSql = 'INSERT INTO product (sku, name, category, price) VALUES(:sku, :name, :category, :price)';
        $statement = $this->connection->prepare($insertSql);
 
        foreach($productsArray as $product){
            $statement->bindValue(':sku', $product["sku"]);
            $statement->bindValue(':name', $product["name"]);
            $statement->bindValue(':category', $product["category"]);
            $statement->bindValue(':price', $product["price"]);
            $statement->executeStatement();
        }
    }

    private function installComposer(){ 
        $install = new Process(['composer','install']);
        $install->run(function($type, $buffer){
            echo $buffer;
        }); 
    }
}
