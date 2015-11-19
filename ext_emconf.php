<?php

########################################################################
# Extension Manager/Repository config file for ext "pxa_newstofb".
#
# Auto generated 30-05-2013 17:22
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
    'title' => 'News to Facebook Integration',
    'description' => 'Publish news from tt_news/tx_news in TYPO3 to Facebook wall.',
    'category' => 'be',
    'shy' => 0,
    'version' => '2.0.0',
    'dependencies' => 'tt_news, tx_news_domain_model_news',
    'conflicts' => '',
    'priority' => '',
    'loadOrder' => '',
    'module' => '',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearcacheonload' => 0,
    'lockType' => '',
    'author' => 'Web Essentials for Pixelant, Pixelant AB',
    'author_email' => 'ext-pxa_newstofb@web-essentials.asia, maksym@pixelant.se, oleksandr@pixelant.se',
    'author_company' => 'www.web-essentials.asia, www.pixelant.se',
    'CGLcompliance' => '',
    'CGLcompliance_note' => '',
    'constraints' => array(
        'depends' => array(
            'typo3' => '4.4.0-6.2.99',
            'tt_news' => '',
            'tx_news_domain_model_news' => '',
        ),
        'conflicts' => array(
        ),
        'suggests' => array(
        ),
    ),
    '_md5_values_when_last_written' => 'a:25:{s:9:"ChangeLog";s:4:"33f1";s:20:"class.ext_update.php";s:4:"9b19";s:41:"class.tx_pxanewstofb_additionalfields.php";s:4:"dd42";s:32:"class.tx_pxanewstofb_publish.php";s:4:"e77e";s:11:"default.gif";s:4:"694d";s:16:"ext_autoload.php";s:4:"9a7e";s:21:"ext_conf_template.txt";s:4:"d41d";s:12:"ext_icon.gif";s:4:"e146";s:17:"ext_localconf.php";s:4:"2818";s:14:"ext_tables.php";s:4:"5422";s:14:"ext_tables.sql";s:4:"36e7";s:16:"locallang_db.xml";s:4:"83c0";s:10:"README.txt";s:4:"cfe7";s:7:"tca.php";s:4:"c3ce";s:14:"doc/manual.sxw";s:4:"d147";s:21:"lib/base_facebook.php";s:4:"1f1b";s:33:"lib/class.tx_pxanewstifb_news.php";s:4:"4f67";s:16:"lib/facebook.php";s:4:"9ca9";s:26:"lib/fb_ca_chain_bundle.crt";s:4:"c305";s:20:"res/img/facebook.gif";s:4:"6bc7";s:21:"src/base_facebook.php";s:4:"f2a6";s:16:"src/facebook.php";s:4:"dcb2";s:26:"src/fb_ca_chain_bundle.crt";s:4:"c429";s:21:"wizards/appWizard.php";s:4:"44d5";s:18:"wizards/wizard.css";s:4:"d1cc";}',
    'suggests' => array(
    ),
);

?>