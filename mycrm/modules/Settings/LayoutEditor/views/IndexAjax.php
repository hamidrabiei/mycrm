<?php

/*+**********************************************************************************
 * The contents of this file are subject to the mycrm CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  mycrm CRM Open Source
 * The Initial Developer of the Original Code is mycrm.
 * Portions created by mycrm are Copyright (C) mycrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_LayoutEditor_IndexAjax_View extends Settings_Mycrm_IndexAjax_View {
	
	function __construct() {
		$this->exposeMethod('getFieldUI');
	}
    
    public function addBlock(Mycrm_Request $request) {
        $moduleName = $request->get('sourceModule');
        $moduleModel = Mycrm_Module_Model::getInstance($moduleName);
        $blockList = $moduleModel->getBlocks();
        $qualifiedModuleName = $request->getModule(false);
        
        $viewer = $this->getViewer($request);
        $viewer->assign('BLOCKS', $blockList);
        $viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
        echo $viewer->view('AddBlock.tpl', $qualifiedModuleName,true);
    } 
    
    public function getFieldUI (Mycrm_Request $request) {
        $fieldsList = $request->get('fieldIdList');
        $module = $request->get('sourceModule');
        $fieldModelList = Settings_LayoutEditor_Field_Model::getInstanceFromFieldId($fieldsList, getTabId($module));
        $viewer = $this->getViewer($request);
        $qualifiedModuleName = $request->getModule(false);
		$viewer->assign('SELECTED_MODULE_NAME', $module);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
        $viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
        $viewer->assign('FIELD_MODELS_LIST', $fieldModelList);
        $viewer->view('FieldUi.tpl',$qualifiedModuleName);
    }
    
}