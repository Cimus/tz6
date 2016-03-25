<?php

namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Service\DirectApi;

/**
 * @author Sergey Ageev (Cimus <s_ageev@mail.ru>)
 */
class CampaignsLoadCommand extends Command
{
    protected function configure()
    {
        $this->setName('load:campaigns')
            ->setDescription('Загружает кампании');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Начали загружать кампании<info>');
        
        $api = new DirectApi();
        $list = $api->getCampaignsList();
        $cntInsert = $this->saveCampaigns($list['data']);

        $output->writeln("<info>Загрузили $cntInsert кампаний<info>");
    }
    
    /**
     * Сохраняет новые кампании в БД 
     * 
     * @param array $data
     * @return integer $cntInsert Количество вставленных данных
     */
    protected function saveCampaigns($data)
    {
        $bufer = [];
        
        $i = 0;
        $cntInsert = 0;
        
        foreach((array) $data as $item){
            $bufer[] = "({$item['CampaignID']}, {$this->getApplication()->getPdo()->quote($item['Name'])})";
            
            $i++;
            
            if(($i % 1000) == 0){
                $cntInsert += $this->flush($bufer);
                $i = 0;
            }
        }
        
        if($i){
            $cntInsert += $this->flush($bufer);
        }
        
        
        return $cntInsert;
    }
    
    
    /**
     * 
     * @param array $bufer
     * @return integer
     */
    protected function flush($bufer)
    {
        if(!$bufer) return false;
        
        $q = 'INSERT IGNORE INTO `Campaigns` (`id`, `name`) VALUES ' . implode(', ', $bufer);
        
        return $this->getApplication()->getPdo()->exec($q);
    }
    
    
    
}
