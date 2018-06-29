<?php
/*+***********************************************************************************
 * The contents of this file are subject to the mycrm CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  mycrm CRM Open Source
 * The Initial Developer of the Original Code is mycrm.
 * Portions created by mycrm are Copyright (C) mycrm.
 * All Rights Reserved.
 *************************************************************************************/

Class Users_Edit_View extends Users_PreferenceEdit_View {

	public function preProcess(Mycrm_Request $request) {
		parent::preProcess($request, false);
		$this->preProcessSettings($request);
	}

	public function preProcessSettings(Mycrm_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
		$selectedMenuId = $request->get('block');
		$fieldId = $request->get('fieldid');

		$settingsModel = Settings_Mycrm_Module_Model::getInstance();
		$menuModels = $settingsModel->getMenus();

		if(!empty($selectedMenuId)) {
			$selectedMenu = Settings_Mycrm_Menu_Model::getInstanceById($selectedMenuId);
		} elseif(!empty($moduleName) && $moduleName != 'Mycrm') {
			$fieldItem = Settings_Mycrm_Index_View::getSelectedFieldFromModule($menuModels,$moduleName);
			if($fieldItem){
				$selectedMenu = Settings_Mycrm_Menu_Model::getInstanceById($fieldItem->get('blockid'));
				$fieldId = $fieldItem->get('fieldid');
			} else {
				reset($menuModels);
				$firstKey = key($menuModels);
				$selectedMenu = $menuModels[$firstKey];
			}
		} else {
			reset($menuModels);
			$firstKey = key($menuModels);
			$selectedMenu = $menuModels[$firstKey];
		}

		$viewer->assign('SELECTED_FIELDID',$fieldId);
		$viewer->assign('SELECTED_MENU', $selectedMenu);
		$viewer->assign('SETTINGS_MENUS', $menuModels);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
        $viewer->assign('LOAD_OLD', Settings_Mycrm_Index_View::$loadOlderSettingUi);
		$viewer->assign('IS_PREFERENCE', false);

		$viewer->view('SettingsMenuStart.tpl', $qualifiedModuleName);
	}

	public function postProcessSettings(Mycrm_Request $request) {
		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		$viewer->view('SettingsMenuEnd.tpl', $qualifiedModuleName);
	}

	public function postProcess(Mycrm_Request $request) {
		$this->postProcessSettings($request);
		parent::postProcess($request);
	}
	
	public function getHeaderScripts(Mycrm_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'modules.Settings.Mycrm.resources.Index'
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
	
	public function process(Mycrm_Request $request) {
		parent::process($request);
	}
}