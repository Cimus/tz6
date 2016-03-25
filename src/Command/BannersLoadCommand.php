<?php
namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;


use Service\DirectApi;


/**
 * @author Sergey Ageev (Cimus <s_ageev@mail.ru>)
 */
class BannersLoadCommand extends Command
{
    protected function configure()
    {
        $this->setName('load:banners')
            ->setDescription('Загружает объявления');
    }
    
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $campaignIds = $this->getCampaignIds();
        
        if(!$campaignIds){
            $output->writeln('<error>Нет кампаний для которых можно загрузить данные</error>');
            return;
        }
        
        $output->writeln('<info>Начали загружать объявления<info>');
        
        
        $api = new DirectApi();
        
        $bannersCsvName = tempnam('/tmp', 'banners');
        $phrasesCsvName  = tempnam('/tmp', 'prases');
        
        $bannersHandle = fopen($bannersCsvName, 'w');
        $phrasesHandle = fopen($phrasesCsvName, 'w');
        
        $progress = new ProgressBar($output, count($campaignIds));
        $progress->start();
        
        
        foreach($campaignIds as $id){
            //Тут мы получили все объявления по конкретной кампании и уже сожрали уйму памяти, 
            //ну что ж, будем относится к ней бережно
            $data = $api->getBanners($id);
            //В цикле проходимся по объявлениям и раскидываем их в 2 файла
            //1-й файл c обявлениями
            //2-й файл с фразами
            foreach((array)$data['data'] as $i => $item){
                $this->saveBanerInCsv($item, $bannersHandle, $phrasesHandle);
                $data['data'][$i] = null;//Освобождаем память, она очень дорогая ;)
            }
            
            $progress->advance();
        }
        
        fclose($bannersHandle);
        fclose($phrasesHandle);
        
        $this->loadCsvInDb($bannersCsvName, $phrasesCsvName);
        $progress->finish();
        $output->writeln('');
        
        //Чистим мусор
        unlink($bannersCsvName);
        unlink($phrasesCsvName);
        
        $output->writeln('<info>Загрузили объявления по всем кампаниям<info>');
    }
    
    /**
     * Загружает CSV файлы в БД, для оптимизации можно отключить на время проверку внешних ключей
     * 
     * @param string $bannersCsvName
     * @param string $phrasesCsvName
     * @return boolean
     */
    protected function loadCsvInDb($bannersCsvName, $phrasesCsvName)
    {
        $this->getApplication()->getPdo()->exec(" LOAD DATA LOCAL INFILE '$bannersCsvName' IGNORE INTO TABLE Banners FIELDS TERMINATED BY ',' ENCLOSED BY '\"'");
        $this->getApplication()->getPdo()->exec(" LOAD DATA LOCAL INFILE '$phrasesCsvName' IGNORE INTO TABLE Phrases FIELDS TERMINATED BY ',' ENCLOSED BY '\"'");
        
        return true;
    }


    /**
     * Собираем/подготавливаем нужные данные
     * 
     * @param array $data
     * @return array
     */
    protected function saveBanerInCsv($data, $bannersHandle, $phrasesHandle)
    {
        
        fputcsv($bannersHandle, [
            $data['BannerID'],
            $data['CampaignID'],
            $data['Title'],
        ]);
        
        
        foreach((array)$data['Phrases'] as $phrase){
            fputcsv($phrasesHandle, [
                $phrase['PhraseID'],
                $data['BannerID'],
                $this->clearPrase($phrase['Phrase'])
            ]);
        }
        
        return true;
    }

    
    protected function clearPrase($phrase)
    {
        //Тут удаляем -минус слова, можно это сделать и регуляркой
        $words = explode(' ', $phrase);
        
        foreach($words as &$word){
            if($word[0] == '-'){
                $word = '';
            }
        }
        
        $phrase = implode(' ', $words);
        
        //Регулярки можно заменить на конечный автомат
        //Удаляем управляющие символы
        $phrase = preg_replace('#[\(\)\|\+"\!]+#ui', '', $phrase);
        //Сжимаем двойние пробелы
        $phrase = preg_replace('#[\s]+#ui', ' ', $phrase);
        
        return trim($phrase);
    }



    /**
     * По хорошему, надо бы сделать автоматическую порционную подгрузку айдишников, т.к. если их много, 
     * то скрипт отвалится по нехватке памяти
     * 
     * @return array массив идентивикаторов кампаний
     */
    protected function getCampaignIds()
    {
        $q = 'SELECT id from `Campaigns`';
        
        return $this->getApplication()->getPdo()->query($q)->fetchAll(\PDO::FETCH_COLUMN);
    }
    

}
