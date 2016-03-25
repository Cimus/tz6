#!/usr/bin/env php
<?php
/**
 * @author Sergey Ageev (Cimus <s_ageev@mail.ru>)
 */

require __DIR__. '/vendor/autoload.php';

$loader = new \Composer\Autoload\ClassLoader();
$loader->add('Command', __DIR__. '/src');
$loader->add('Service', __DIR__. '/src');
$loader->add('Util', __DIR__. '/src');
$loader->register();





class Application extends Symfony\Component\Console\Application
{
    
    private $dsn = 'mysql:dbname=tz6;host=127.0.0.1;charset=utf8';
    private $dbUser = 'root';
    private $dbPassword = '4592';
    
    private $pdo;
    
    /**
     * 
     * @return PDO
     * @throws PDOException
     */
    public function getPdo()
    {
        if(!($this->pdo instanceof PDO)){
            try {
                $this->pdo = new PDO($this->dsn, $this->dbUser, $this->dbPassword, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_LOCAL_INFILE => true
                ]);
            }
            catch(PDOException $ex)
            {
                throw $ex;
            }
        }
        
        return $this->pdo;
    }
    
    public function getRootDir()
    {
        return __DIR__;
    }
}



$application = new Application();
$application->add(new Command\CampaignsLoadCommand());
$application->add(new Command\BannersLoadCommand());
$application->add(new Command\StatCommand());






$application->run();