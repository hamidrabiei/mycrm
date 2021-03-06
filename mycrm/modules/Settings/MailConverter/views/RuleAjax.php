<?php
/*+***********************************************************************************
 * The contents of this file are subject to the mycrm CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  mycrm CRM Open Source
 * The Initial Developer of the Original Code is mycrm.
 * Portions created by mycrm are Copyright (C) mycrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_MailConverter_RuleAjax_View extends Settings_Mycrm_IndexAjax_View {
	
	public function process(Mycrm_Request $request) {
		$scannerId = $request->get('scannerId');
		$ruleId = $request->get('record');
		$qualifiedModuleName = $request->getModule(false);
		$moduleName = $request->getModule();

		$viewer = $this->getViewer($request);

		$viewer->assign('SCANNER_ID', $scannerId);
		$viewer->assign('SCANNER_MODEL', Settings_MailConverter_Record_Model::getInstanceById($scannerId));
		$viewer->assign('RULE_MODEL', Settings_MailConverter_RuleRecord_Model::getRule($scannerId,$ruleId));

		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);

		$viewer->view('Rule.tpl', $qualifiedModuleName);
	}
}
?>
