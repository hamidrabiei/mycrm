<?php

/*+**********************************************************************************
 * The contents of this file are subject to the mycrm CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  mycrm CRM Open Source
 * The Initial Developer of the Original Code is mycrm.
 * Portions created by mycrm are Copyright (C) mycrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Currency_ListView_Model extends Settings_Mycrm_ListView_Model {
    
    public function getBasicListQuery() {
        $query = parent::getBasicListQuery();
        $query .= ' WHERE deleted=0 ';
        return $query;
    }
}