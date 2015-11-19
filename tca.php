<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

//  FB App configuration
$TCA['tx_pxanewstofb_config_app'] = array(
    'ctrl' => $TCA['tx_pxanewstofb_config_app']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'hidden,title,appid,weburl'
    ),
    'columns' => array(
        'hidden' => array(
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.disable',
            'config' => array(
                'type' => 'check',
                'default' => '1'
            )
        ),
        'title' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config.title',
            'config' => array(
                'type' => 'input',
                'size' => '30',
                'eval' => 'required'
            )
        ),
        'appid' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config.appid',
            'config' => array(
                'type' => 'input',
                'size' => '100',
                'eval' => 'required,trim'
            )
        ),
        'secret' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config.secret',
            'config' => array(
                'type' => 'input',
                'size' => '100',
                'eval' => 'required,trim'
            )
        ),
        'weburl' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config.weburl',
            'config' => array(
        'type' => 'input',    
          'internal_type' => 'db',    
          'size' => '100',
              'max' => '255',
              'eval' => 'trim',
            'wizards' => array(
          '_PADDING' => 2,
            'link' => array(
            'type' => 'popup',
              'title'=> 'FB connection',
            'icon' => t3lib_extMgm::extRelPath("pxa_newstofb").'res/img/facebook.gif',
              'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1',
              'params' => array(
                                      //  'test' => '###CURRENT_PID###'
            ),
              'script' => t3lib_extMgm::extRelPath("pxa_newstofb").'wizards/appWizard.php', 
            ),
          ),
        ),
        )
    ),
    'types' => array(
        '0' => array('showitem' => 'title;;1;;2-2-2, appid, secret, weburl;;;;3-3-3')
    ),
    'palettes' => array(
        '1' => array('showitem' => 'hidden')
    )
);

//  Publisher to FB configuration 
$TCA['tx_pxanewstofb_config_social_publishing'] = array(
    'ctrl' => $TCA['tx_pxanewstofb_config_social_publishing']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'hidden,title,description,appid,pageid,weburl,detailnewspid,storagepid'
    ),
    'columns' => array(
        'hidden' => array(
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.disable',
            'config' => array(
                'type' => 'check',
                'default' => '1'
            )
        ),
        'starttime' => array(
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.starttime',
            'config' => array(
                'type' => 'input',
                'size' => '8',
                'max' => '20',
                'eval' => 'date',
                'default' => '0',
                'checkbox' => '0'
            )
        ),
        'title' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config_social_publishing.title',
            'config' => array(
                'type' => 'input',
                'size' => '30',
                'eval' => 'required'
            )
        ),
        'description' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config_social_publishing.description',
            'config' => array(
                'type' => 'text',
                'cols' => '30',
                'rows' => '2'
            )
        ),
        'appid' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config_social_publishing.appid',
            'config' => array(
                'type' => 'select',
                'items' => array(
                    array('', 0),
                ),
                'foreign_table' => 'tx_pxanewstofb_config_app',
                'foreign_table_where' => 'AND 
                tx_pxanewstofb_config_app.hidden= 0',
            )
        ),
        'pageid' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config_social_publishing.pageid',
            'config' => array(
                'type' => 'select',
                'items' => array(
                    array('', 0),
                ),
                'foreign_table' => 'tx_pxanewstofb_app_token',
                'foreign_table_where' => 'AND 
                tx_pxanewstofb_app_token.parent=###REC_FIELD_appid###',
            )
        ),
    'weburl' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config.weburl',
            'config' => array(
                'type' => 'input',
                'size' => '100',
                'eval' => 'required,trim'
            )
        ),
        'type' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config.type',
            'config' => array(
                'type' => 'select',
                'items' => array(
                    array('tt_news', 0),
                    array('tx_news', 1),
                ),
                'size' => 1,
                'maxitems' => 1,
            ),
        ),    
        'detailnewspid' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config.detailnewspid',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'eval' => 'required',
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest'
                    )
                )
            )
        ),
        'storagepid' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config.storagepid',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'eval' => 'required',
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest'
                    )
                )
            )
        ),
        'categoryid' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config.categoryid',
            'config' => array(
                'type' => 'input',
                'size' => '100',
                'eval' => 'trim'
            )
        ),
        'desclength' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config.desclength',
            'config' => array(
                'type' => 'input',
                'size' => '100',
                 'default' => '300',
                'eval' => 'required,trim,int'
            )
        ),
        'logfilepath' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config.logfilepath',
            'config' => array(
                'type' => 'input',
                'size' => '255',
                'eval' => 'required,trim'
            )
        ),
        'defaultimagepath' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config.defaultimagepath',
            'config' => array(
                'type' => 'input',
                'size' => '255',
                'eval' => 'required,trim'
            )
        )
    ),    
    'types' => array(
        '0' => array('showitem' => 'title;;1;;2-2-2, description, appid, pageid, weburl, type, detailnewspid, storagepid, categoryid, desclength, logfilepath,defaultimagepath;;;;3-3-3')
    ),
    'palettes' => array(
        '1' => array('showitem' => 'hidden')
    )
);


$TCA['tx_pxanewstofb_app_token'] = array(
    'ctrl' => $TCA['tx_pxanewstofb_app_token']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'hidden,title,category,appid'
    ),
    'columns' => array(
        'title' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_app_token.title',
            'config' => array(
                'type' => 'input',
                'size' => '100',
                'eval' => 'required'
            )
        ),
        'category' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_app_token.category',
            'config' => array(
                'type' => 'input',
                'size' => '100',
                'eval' => 'required'
            )
        ),
        'access_token' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_app_token.access_token',
            'config' => array(
                'type' => 'input',
                'size' => '100',
                'eval' => 'required'
            )
        ),
        'appid' => array(
            'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_app_token.appid',
            'config' => array(
                'type' => 'input',
                'size' => '100',
                'eval' => 'required'
            )
        ),
    ),
    'types' => array(
        '0' => array('showitem' => 'title;;1;;2-2-2, category, access_token, appid;;;;3-3-3')
    ),
    'palettes' => array(
        '1' => array('showitem' => 'hidden')
    )
);
?>