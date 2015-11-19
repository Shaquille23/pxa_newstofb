<?php
if (!defined ('TYPO3_MODE'))     die ('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_pxanewstofb_publish'] = array (
    'extension'        => $_EXTKEY,
    'title'            => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:scheduler.tx_pxanewstofb_title',
    'description'      => 'LLL:EXT:' . $_EXTKEY . '/locallang_db.xml:scheduler.tx_pxanewstofb_description',
    'additionalFields' => 'tx_pxanewstofb_additionalfields'
);

?>
