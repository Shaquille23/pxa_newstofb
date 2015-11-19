
<?php
// Pochustutu
class tx_pxanewstofb_additionalFields implements tx_scheduler_AdditionalFieldProvider {
	public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject) {//getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $parentObject) {


		$pxanewstofbSocialPublisherConfig = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'tx_pxanewstofb_config_social_publishing', 'hidden=0
				AND (starttime=0 OR starttime<=' . $GLOBALS['EXEC_TIME'] . ')');

		$fieldCodeCfgRec = '';
		/////////////////////
		$fieldCodeCfgRecPublisher = '';

		if (empty($taskInfo['pxanewstofbconfiguration'])) {
			if($parentObject->CMD == 'edit') {
				$taskInfo['pxanewstofbconfiguration'] = $task->pxanewstofbconfiguration;
			} else {
				$taskInfo['pxanewstofbconfiguration'] = '';
			}
		}

		$cfgValues =  explode(",", $taskInfo['pxanewstofbconfiguration']);

		/////////////////////////////////////
		foreach ($pxanewstofbSocialPublisherConfig as $cfgRec) {
		  $fieldCodeCfgRec .= '<option value="'.$cfgRec['uid'].'" ' . (in_array($cfgRec['uid'], $cfgValues)?'selected':''). '>' . $cfgRec['title'] . '</option>';
		}


		$fieldID = 'task_pxanewstofbconfiguration';
		$fieldCode = '<select name="tx_scheduler[pxanewstofbconfiguration][]" id="' . $fieldID . '" multiple="multiple">' . $fieldCodeCfgRec . '</select>';
		$additionalFields[$fieldID] = array(
			'code'     => $fieldCode,
			'label'    => 'LLL:EXT:pxa_newstofb/locallang_db.xml:scheduler.tx_pxanewstofb_configuration'
		);

		return $additionalFields;
	}

	public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject) { //validateAdditionalFields(array &$submittedData, tx_scheduler_Module $parentObject) {
		$submittedData['pxanewstofbconfiguration'] = trim( implode(",", $submittedData['pxanewstofbconfiguration']) );
		return true;
	}

	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
		$task->pxanewstofbconfiguration = $submittedData['pxanewstofbconfiguration'];
	}
}
?>
