<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Web Essentials for Pixelant <ext-pxa_newstofb@web-essentials.asia>
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

require_once(t3lib_extMgm::extPath('pxa_newstofb', 'src/facebook.php'));
require_once(t3lib_extMgm::extPath('pxa_newstofb', 'lib/class.tx_pxanewstifb_news.php'));

define(PUBLISH, 1);

class tx_pxanewstofb_publish extends tx_scheduler_Task {

	/**
	 *  objectManager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	protected $objectManager;
	
	/**
	 * newsRepository
	 *
	 * @var Tx_News_Domain_Repository_NewsRepository
	 */
	protected $newsRepository = NULL;

    public $News; 
    public $facebook;
    public $messages;
    public $FbNews;

    /**
     * Executes the schedule task to post news to facebook
     */
    public function execute() {

      $config=$this->GetPublishConf();
      $publishLog = array();
      $this->initTsfe(intval($config['detailnewspid']));
      $this->FbNews = array();
      if ($config["type"] == 1 ) { 
        // using Tx_News items to publish
      	$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
		    $this->newsRepository = $objectManager->get('Tx_News_Domain_Repository_NewsRepository');
		    $query = $this->newsRepository->createQuery(); 
		    $query->getQuerySettings()->setRespectStoragePage(FALSE);
		    $query->matching(
          $query->logicalAnd(
				    $query->equals('pid', intval($config["storagepid"])),
				    $query->equals('tx_pxanewstofb_published', 0),					
				    $query->equals('tx_pxanewstofb_dont_publish', 0),
            $query->equals('deleted', 0),
            $query->equals('hidden', 0)
          )
		    );
		    $news = $query->execute();
        $this->FbNews= $this->TxNewsForPublish($news,$config);
      } else {
        // using tt_news items to publish
      	$this->News = new News($config['detailnewspid'], $config['storagepid'], $config['categoryid'], $config['desclength']);
      	$News = $this->News->getNews();
        $this->FbNews= $this->NewsForPublish($News, $config);
      }

      if(count($this->FbNews)) {
        $row = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'tx_pxanewstofb_config_app', 'uid = ' . intval($config['appid']));
        if($row) {
       	  $facebook =  new Facebook(array(
        	 'appId' => $row['0']['appid'],
         	 'secret' => $row['0']['secret'],
          ));
          $publishLog = $this->Publish($facebook, $this->FbNews, $config['pageid'], $publishLog, $config["type"]);
        } else {
       	  array_push($publishLog, 'ERR:No associated Facebook Application record found.');
        }
      }  
      if( count($publishLog) ) $this->writePublishLog($publishLog, $config['logfilepath']);

      return TRUE;
    }


/*
return poublishing record
*/

      private  function GetPublishConf()
      {
          global $GLOBALS, $TSFE, $TYPO3_CONF_VARS;
          $configUid =  t3lib_div::trimExplode(',', $this->pxanewstofbconfiguration, TRUE);
          $ConfigTemp = array();

          foreach($configUid as $uid) {
            $row = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'tx_pxanewstofb_config_social_publishing', 'hidden=0
                      AND uid = ' . intval($uid));
            if($row['0']) $ConfigTemp = $row['0'];
          }

            foreach($ConfigTemp as $key => $value) {
             $config[$key]= $value;
            }
          $row = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'tx_pxanewstofb_app_token', 'uid = ' . intval($config['pageid']));
        //  print_r($row);
          $config['access_token'] = $row['0']['access_token'];
          $config['pageid'] = $row['0']['appid'];
      return $config;  
      }



      /*

      1- init news 
      2- get news
      3- convert news for publishing format

      */
      private  function NewsForPublish($newsSrc,$fbconfig)
      { $publishNews = array();
      	$cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tslib_cObj');
        foreach ($newsSrc as $index => $news) {
          $publishNews[$index] = $news;   
          $parameters = "&tx_ttnews[tt_news]=".$news['uid'];
          $publishNews[$index]['link'] = $cObj->typoLink_URL(array('parameter' => $fbconfig['detailnewspid'],'additionalParams' => $parameters, 'forceAbsoluteUrl' => 1));
          if (!empty($news['picture'])){$publishNews[$index]['picture'] = $fbconfig['weburl'].$news['picture'];}
          else { $publishNews[$index]['picture'] = $fbconfig['weburl'].$fbconfig['defaultimagepath'];}
          $publishNews[$index]['access_token'] = $fbconfig['access_token'];
        }
    
      return $publishNews;
      }

      private  function TXNewsForPublish($newsSrc,$fbconfig)
      { 
      	$publishNews = array();
      	$cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tslib_cObj');
        foreach ($newsSrc as $index => $news) {
          $article = array(
				    'link' => $cObj->typoLink_URL(array('parameter' => $fbconfig['detailnewspid'],'additionalParams' => "&tx_news_pi1[news]=" . $news->getUid(), 'forceAbsoluteUrl' => 1)),
				    'name' => $news->getTitle(),
				    'uid' => $news->getUid(),
			     );

          $article['description'] = strip_tags($news->getTeaser() ? $news->getTeaser() : $news->getBodytext());
          $article['description'] = preg_replace('/\s+/', ' ', $article['description']);
          if ( $fbconfig["desclength"] )	$article['description'] = $cObj->crop($article['description'], intval($fbconfig["desclength"]) . '|...|1');
          $article['uid']= $news->getUid();
	        if ( $news->getFalMedia() ){
            foreach ($news->getFalMedia() as $mediaItem) {
              $article['picture'] = $fbconfig['weburl'].$mediaItem->getOriginalResource()->getPublicUrl();
              break;
            }
          }	else { 
          	$article[$index]['picture'] = $fbconfig['weburl'].$fbconfig['defaultimagepath'];
          }
          $article['access_token'] = $fbconfig['access_token'];
		      $publishNews[] = $article; 
        }
        return $publishNews;
      }
      
      private function Publish($facebook,$publishSrc,$pageId,$publishLog = array(), $type = 0)
      {    
        try {
          foreach ($publishSrc as $data) {
            $uid = $data['uid'];
            unset($data['uid']);
            $facebook->api('/' . $pageId . '/feed/', 'post', $data);
            array_push($publishLog, 'Record news uid - ' . $uid. ' was published.');
            if($type == 1) {
              $this->publishTxNewsStatus($uid,PUBLISH);
            } else {
              $this->publishStatus($uid,PUBLISH);
            }
          }
        } catch (Exception $e) {
          array_push($publishLog, $e);
        }
        return $publishLog;
      }

      private function writePublishLog($publishLog,$logFilePath){
        if ($logFilePath) {
          $logFilePath = t3lib_div::getFileAbsFileName($logFilePath);
        }
        $logFile = fopen($logFilePath, 'a');
        if($logFile) {
          $time = date('d-M-Y H:i:s');
          foreach( $publishLog as $key => $val ) {
            fprintf($logFile, "%s %s \n", $time, $val);
          }
          fclose($logFile);
        }  
      }


     public function publishTxNewsStatus($newsUid,$status){

        $sqlUpdate = 'UPDATE tx_news_domain_model_news SET tx_pxanewstofb_published = '.$status.' WHERE uid in (' . $newsUid .')';
        $GLOBALS['TYPO3_DB']->sql_query($sqlUpdate);
    

     }

     public function publishStatus($newsUid,$status){

        $sqlUpdate = 'UPDATE tt_news SET tx_pxanewstofb_published = '.$status.' WHERE uid in (' . $newsUid .')';
        $GLOBALS['TYPO3_DB']->sql_query($sqlUpdate);
    

     }     

      protected function initTsfe($id = 0) {
        global $TYPO3_CONF_VARS;

        $rootline = t3lib_BEfunc::BEgetRootLine($id);
        $host = t3lib_BEfunc::firstDomainRecord($rootline);
        $_SERVER['HTTP_HOST'] = $host;

        $GLOBALS['TT'] = t3lib_div::makeInstance('t3lib_timeTrack');
        $GLOBALS['TT']->start();

        $GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe', $TYPO3_CONF_VARS, $id, 0);
        $GLOBALS['TSFE']->connectToDB();
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();
        $GLOBALS['TSFE']->renderCharset = 'utf-8';
      
      }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pxa_newstofb/class.tx_pxanewstofb_publish.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pxa_newstofb/class.tx_pxanewstofb_publish.php']);
}

?>