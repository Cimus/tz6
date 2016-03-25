<?php
namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;



/**
 * @author Sergey Ageev (Cimus <s_ageev@mail.ru>)
 */
class StatCommand extends Command
{
    protected function configure()
    {
        $this->setName('stat')
            ->setDescription('Выводит статистику слов в ключевых фразах');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $phrases = $this->getPrases();
        
        if(!$phrases){
            $output->writeln('<error>Нет фраз для которых можно подсчитать статистику</error>');
            return;
        }
        
        
        
        $stat = [];
        
        foreach($phrases as $phrase){
            $words = explode(' ', $phrase);
            foreach($words as $word){
                if(isset($stat[$word])){
                    $stat[$word]++;
                }
                else{
                    $stat[$word] = 1;
                }
            }
        }
        
        $tableRows = [];
        
//        Если раскоментить, то статистика будет выведена в консоль в табличном виде
//        foreach($stat as $phrase => $cnt){
//            $tableRows[] = [$phrase, $cnt];
//        }
//        
//        $table = new Table($output);
//        $table
//            ->setHeaders(array('Слово', 'Количество вхождений'))
//            ->setRows($tableRows);
//        $table->render();
        
        
        $file = $this->getApplication()->getRootDir() . '/stat.csv';
        $handle = fopen($file, 'w+');
        fwrite($handle, pack('C3', 0xef, 0xbb, 0xbf ));//BOM
        
        foreach($stat as $phrase => $cnt){
            fputcsv($handle, [$phrase, $cnt], ';', '"');
        }
        
        fclose($handle);

        
        
        $output->writeln("<info>Статистика выгружена в $file<info>");
        
    }
    
    /**
     * По хорошему, надо бы сделать автоматическую порционную подгрузку фраз, т.к. если их много, 
     * то скрипт отвалится по нехватке памяти
     * 
     * @return array массив фраз
     */
    protected function getPrases()
    {
        $q = 'SELECT phrase from `Phrases`';
        
        return $this->getApplication()->getPdo()->query($q)->fetchAll(\PDO::FETCH_COLUMN);
    }
    
    
}
