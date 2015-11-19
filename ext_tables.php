<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$tempColumns = array (
	'tx_pxanewstofb_dont_publish' => array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_dont_publish',		
		'config' => array (
			'type' => 'check',
		)
	),
	'tx_pxanewstofb_published' => array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_published',		
		'config' => array (
			'type' => 'check',
		)
	),
);

t3lib_div::loadTCA('news');
t3lib_extMgm::addTCAcolumns('tx_news_domain_model_news', $tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tx_news_domain_model_news', '--div--;Pxa News to Facebook,tx_pxanewstofb_dont_publish,tx_pxanewstofb_published;;;;1-1-1');


t3lib_extMgm::allowTableOnStandardPages('tx_pxanewstofb_config_app');
$TCA['tx_pxanewstofb_config_app'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config_app',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' => 'type',
		'default_sortby' => 'ORDER BY crdate',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
    'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY).'res/img/facebook.gif',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php'
	)
);

$TCA['tx_pxanewstofb_app_token'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_app_token',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' => 'type',
		'default_sortby' => 'ORDER BY crdate',
    'readOnly' => true,
    'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY).'res/img/facebook.gif',
    'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php'
	)
);

t3lib_extMgm::allowTableOnStandardPages('tx_pxanewstofb_config_social_publishing');
$TCA['tx_pxanewstofb_config_social_publishing'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:pxa_newstofb/locallang_db.xml:tx_pxanewstofb_config_social_publishing',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' => 'type',
		'default_sortby' => 'ORDER BY crdate',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime'
		),
		'requestUpdate' => 'appid',
    'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY).'res/img/facebook.gif',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php'
	)
);
?>