<?php
/*+***********************************************************************************
 * The contents of this file are subject to the mycrm CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  mycrm CRM Open Source
 * The Initial Developer of the Original Code is mycrm.
 * Portions created by mycrm are Copyright (C) mycrm.
 * All Rights Reserved.
 *************************************************************************************/

class Users_SaveCalendarSettings_Action extends Users_Save_Action {


	public function process(Mycrm_Request $request) {
		$recordModel = $this->getRecordModelFromRequest($request);
		
		$recordModel->save();
		$this->saveCalendarSharing($request);
		header("Location: index.php?module=Calendar&view=Calendar");
	}

	/**
	 * Function to update Calendar Sharing information
	 * @params - Mycrm_Request $request
	 */
	public function saveCalendarSharing(Mycrm_Request $request){
		
		$sharedIds = $request->get('sharedIds');
		$sharedType = $request->get('sharedtype');

		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$calendarModuleModel = Mycrm_Module_Model::getInstance('Calendar');
		$accessibleUsers = $currentUserModel->getAccessibleUsersForModule('Calendar');

		if($sharedType == 'private'){
			$calendarModuleModel->deleteSharedUsers($currentUserModel->id);
		}else if($sharedType == 'public'){
            $allUsers = $currentUserModel->getAll(true);
			$accessibleUsers = array();
			foreach ($allUsers as $id => $userModel) {
				$accessibleUsers[$id] = $id;
			}
			$calendarModuleModel->deleteSharedUsers($currentUserModel->id);
			$calendarModuleModel->insertSharedUsers($currentUserModel->id, array_keys($accessibleUsers));
		}else{
			if(!empty($sharedIds)){
				$calendarModuleModel->deleteSharedUsers($currentUserModel->id);
				$calendarModuleModel->insertSharedUsers($currentUserModel->id, $sharedIds);
			}else{
				$calendarModuleModel->deleteSharedUsers($currentUserModel->id);
			}
		}
	}
}
