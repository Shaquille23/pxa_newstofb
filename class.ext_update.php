<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Georg Ringer <typo3@ringerge.org>
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

/**
 * Update class for extmgr.
 *
 * @package TYPO3
 * @subpackage pxa_newstofb
 */
class ext_update {

	/**
	 * Main update function called by the extension manager.
	 *
	 * @return string
	 */
	public function main() {
		return '';
	}
	
	/**
	 * Called by the extension manager to determine if the update menu entry
	 * should by showed.
	 *
	 * @return bool
	 */
	public function access() {
			
			// Check the tt_news table if the data should be updated
		$this->checkNewsTable();
		
		return TRUE;
	}
	
	/**
	 * Check tt_news table fields if the new extension should update existing data or not
	 * 
	 * @return boolean - FALSE if having s.th should update, otherwise TRUE
	 */
	public function checkNewsTable() {
			// If having old field name from previous version
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('column_name', 'information_schema.columns', "column_name = 'tx_pxanewstofb_publish' OR column_name = 'tx_pxanewstofb_ignor_publish'");
		if($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0) {
		
				// Check if the old fields have value set by user
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid', 'tt_news', 'deleted = 0 AND ((tx_pxanewstofb_publish = 1 AND tx_pxanewstofb_published = 0) OR (tx_pxanewstofb_ignor_publish = 1 && tx_pxanewstofb_dont_publish = 0))');
			$foundRows = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
			if($foundRows > 0) {
					
					// Update new fields data from old data
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_news', 'deleted = 0 AND tx_pxanewstofb_publish = 1 AND tx_pxanewstofb_published = 0', 
						array(
							'tx_pxanewstofb_published' => 1,
							'tx_pxanewstofb_publish' => 0,
							'tx_pxanewstofb_dont_publish' => 0
							)
						);
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_news', 'deleted = 0 AND tx_pxanewstofb_ignor_publish = 1 AND tx_pxanewstofb_dont_publish = 0', 
						array(
							'tx_pxanewstofb_dont_publish' => 1,
							'tx_pxanewstofb_ignor_publish' => 0
							)
						);
				$updateText = '<br />' . $GLOBALS['LANG']->sL('LLL:EXT:pxa_newstofb/locallang_db.xml:data_transfered');
			}
			
				// Display information box when open this extension in Extension Manager
			$box = '<div id="userTS-updateMessage" class="typo3-tstemplate-ceditor-row">
						<div style="position:absolute;top:50px;right:20px; width:360px;z-index:2;">
							<div class="typo3-message message-warning">
								<div class="message-header">
									Important!
								</div>
								<div class="message-body">
									<strong>' . $GLOBALS['LANG']->sL('LLL:EXT:pxa_newstofb/locallang_db.xml:fields_changed') . '</strong><br />
									tx_pxanewstofb_publish => tx_pxanewstofb_published <br />
									tx_pxanewstofb_ignor_publish => tx_pxanewstofb_dont_publish <br />' 
									. '<div style="color: red; padding-top: 5px;">' . $GLOBALS['LANG']->sL('LLL:EXT:pxa_newstofb/locallang_db.xml:instruction') . '</div>' . $updateText . '
								</div>
							</div>
						</div>
					</div>';
			echo $box;
		}
	} 

}
?>