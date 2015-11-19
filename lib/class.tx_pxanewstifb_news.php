<?php

/**
* News Storage
*/
class News 
{
    
public $newsStorage = array();
public $inCat;
public $table;
public $where;
public $newsUid;
public $newsImagePath;
public $host;



    function __construct($detailNewsPid,$storagePid,$categoryId,$descriptionLength)
    {
           
        // Making an instance of frontend classes, tsfe
        $this->cObj = t3lib_div::makeInstance('tslib_cObj');
     
        $temp_TTclassName = t3lib_div::makeInstance('t3lib_timeTrack');
        $GLOBALS['TT'] = new $temp_TTclassName();
        $GLOBALS['TT']->start();
      
        $TSFE = new tslib_fe($TYPO3_CONF_VARS,$detailNewsPid, 0, 0);
        $TSFE->connectToDB();
        $TSFE->initFEuser();
        $TSFE->fetch_the_id();
        $TSFE->getPageAndRootline();
        $TSFE->initTemplate();
        $TSFE->forceTemplateParsing = 1;
        $TSFE->getConfigArray();
        $TSFE->initUserGroups();
        $TSFE->initTemplate();
        $TSFE->determineId();
        
        $GLOBALS['TSFE'] = $TSFE;
        $this->cObj->start(array(),'');
        
        $this->newsUid = '';
/////////////////////////////////folder path //////////////////////////////////////////////////////////////////////
        $this->newsImagePath = $GLOBALS['TCA']['tt_news']['columns']['image']['config']['uploadfolder'];
        if(! $this->newsImagePath) {
            $this->newsImagePath = 'uploads/pics';
        }      

        $this->inCat = '';
        $this->table = 'tt_news';
        $this->where = 'tx_pxanewstofb_published = 0  AND tx_pxanewstofb_dont_publish = 0 ' . $this->enableFields('tt_news').  ($storagePid ?' AND pid = ' .intval($storagePid) :'');
            // Get and validate the list of selected news categories
        $selectedNewsCategories = array();
        $selectedNewsCategories = t3lib_div::intExplode(',', $categoryId, TRUE);
        foreach($selectedNewsCategories as $key => $value) {
            if($value < 1) {
                unset($selectedNewsCategories[$key]);
            }
        }
        if(count($selectedNewsCategories) > 0) {
              $this->table = 'tt_news LEFT JOIN tt_news_cat_mm ON tt_news.uid = tt_news_cat_mm.uid_local';    
            $this->inCat = ' AND uid_foreign IN (' . implode(',', $selectedNewsCategories) .') ';
            $this->where = 'tx_pxanewstofb_published = 0  AND tx_pxanewstofb_dont_publish = 0 ' . $this->inCat . $this->enableFields('tt_news') . ($fbAppSettings['storagePid']?' AND pid = ' .intval($fbAppSettings['storagePid']) :'');
           
        }
        
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('tt_news.*', $this->table, $this->where, 'tt_news.uid');
       
        while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)){
            $curNews= $this->setNews($row, $detailNewsPid,$descriptionLength);
            array_push($this->newsStorage,$curNews);
        }
    ///////////////////////////////////////////
    }


    private function setNews($curRow,$detailNewsPid,$description){

                    /////// Make link for each post in FB to the news detail page of the website
                $typolink_conf = array(
                    'no_cache' => FALSE,
                    'parameter' => $detailNewsPid,
                    'additionalParams' => '&tx_ttnews[tt_news]=' . $curRow['uid'],
                    'useCacheHash' => TRUE
                );
                #$newslink =  $this->cObj->typolink_URL($typolink_conf);
                $newslink = tx_pagepath_api::getPagePath($detailNewsPid, array('tx_ttnews[tt_news]' => $curRow['uid']));
           
                // Create attachment to post to FB by Graph API
                $article = array(
                        'link' => $newslink,
                        'name' => $curRow['title'],
                );
            
                    // If the description length is set , it will show the description text in post
                if(intval($description) > 0) {
                    $desc = strip_tags($curRow['short'] ? $curRow['short'] : $curRow['bodytext']);
                    $desc = preg_replace('/\s+/', ' ', $desc);
                    $desc = $this->cObj->crop($desc, intval($description) . '|...|1');
                    $article['description'] = $desc;
                }

                    // Get the image path
                if($curRow['image']) {
                    if(strpos($curRow['image'], ',') > 0) {
                            // Several Pictures in News, only the first will be taken
                        $imagePath =  $this->newsImagePath . '/' . substr($curRow['image'], 0, strpos($curRow['image'], ','));
                    } else {
                            // Only one Picture in News, this will be taken
                        $imagePath =  $this->newsImagePath . '/' . $curRow['image'];
                    }
                    $article['picture'] = $imagePath;
                }
                
                $article['uid']= $curRow['uid'];

        return $article;
    }


     public function getNews()
     {
        return $this->newsStorage;
     }


    /**
     * Implements enableFields call that can be used from regular FE and eID
     *
     * @param string $tableName    Table name
     *
     * @return string
     */
    public function enableFields($tableName) {

        if ($GLOBALS['TSFE']) {
            return $this->cObj->enableFields($tableName);
        }
        $sys_page = t3lib_div::makeInstance('t3lib_pageSelect');

        /* @var $sys_page t3lib_pageSelect */
        return $sys_page->enableFields($tableName);
    }


}











































?>