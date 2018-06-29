<?php
/*+***********************************************************************************
 * The contents of this file are subject to the mycrm CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  mycrm CRM Open Source
 * The Initial Developer of the Original Code is mycrm.
 * Portions created by mycrm are Copyright (C) mycrm.
 * All Rights Reserved.
 *************************************************************************************/

	/**
	 * Create and trigger events in mycrm
	 */
	class VTEventsManager{
		function __construct($adb){
			$this->adb = $adb;
		}

		/**
		 * Register an event handler
		 *
		 * @param $forEvent The name of the event to handle
		 * @param $path The path to the php file containing the handler
		 * @param $className The name of the VTEventHandler class.
		 * @param $condition A condition which must evaluate true for the event to be accepted.
		 */
		function registerHandler($forEvent, $path, $className, $condition='', $depedentOn='[]'){
			$adb = $this->adb;
			$result = $adb->pquery("SELECT * FROM mycrm_eventhandlers WHERE event_name=? AND handler_path=? AND handler_class=?",
				array($forEvent, $path, $className));
			if($adb->num_rows($result)===0){
				$handlerId = $adb->getUniqueId('mycrm_eventhandlers');
				$adb->pquery("insert into mycrm_eventhandlers
					(eventhandler_id, event_name, handler_path, handler_class, cond, is_active, dependent_on)
					values (?,?,?,?,?, true, ?)", array($handlerId, $forEvent, $path, $className, $condition, $depedentOn));

				$this->clearTriggerCache($forEvent);
			}
		}

		/**
		 * Initialize Event Trigger Cache for the required event types.
		 *
		 * @param Object $for Optional String or Array of event_names for initializing.
		 * @param Boolean $force Optional Force the initialization of cache?
		 */
		function initTriggerCache($for = false, $force = false) {
			VTEventTrigger::initCache($for, $force);
		}

		/**
		 * Clear the Event Trigger Cache
		 *
		 * @param Object $forEvent
		 */
		function clearTriggerCache($forEvent = false) {
			VTEventTrigger::clearCache($forEvent);
		}

		/**
		 * Set an event handler as inactive
		 * @param The handler class to set as inactive
		 *
		 */
		function setHandlerInActive($handlerClass){
			$adb = $this->adb;
			$adb->pquery("update mycrm_eventhandlers set is_active=false where handler_class=?", array($handlerClass));

			$this->clearTriggerCache();
		}

		/**
		 * Set an event handler as active
		 *
		 * @param The handler class to set as active
		 */
		function setHandlerActive($handlerClass){
			$adb = $this->adb;
			$adb->pquery("update mycrm_eventhandlers set is_active=true where handler_class=?", array($handlerClass));

			$this->clearTriggerCache();
		}


		/**
		 * Unregister a registered handler
		 *
		 * @param $className The name of teh VTEventHandler class to unregister
		 */
		function unregisterHandler($className){
			$adb = $this->adb;
			$adb->pquery("delete from mycrm_eventhandlers where handler_class=?",
				array($className));
			$adb->pquery("delete from mycrm_eventhandler_module where handler_class=?",
				array($className));

			$this->clearTriggerCache();
		}

		/**
		 * Get an event triger instance
		 *
		 * @param $triggerName The name of the event.
		 * @return The trigger object for the event.
		 */
		function getTrigger($triggerName){
			$adb=$this->adb;
			return VTEventTrigger::getInstance($adb, $triggerName);
		}

		/**
		 * Trigger an event
		 *
		 * @param $triggerName The name of the event.
		 * @return The trigger object for the event.
		 */
		function triggerEvent($triggerName, $data){
			$this->getTrigger($triggerName)->trigger($data);
		}

		/**
		 * Set the module the handler belongs to
		 *
		 * @param moduleName - The name of the module
		 * @param handlerClass - The name of the handler class
		 */
		function setModuleForHandler($moduleName, $handlerClass){
			$adb = $this->adb;
			$result = $adb->pquery("SELECT * FROM mycrm_eventhandler_module WHERE handler_class=?",
				array($handlerClass));
			if($adb->num_rows($result)===0){
				$handlerModuleId = $adb->getUniqueId('mycrm_eventhandler_module');
				$adb->pquery("insert into mycrm_eventhandler_module
					(eventhandler_module_id, module_name, handler_class)
					values (?,?,?)", array($handlerModuleId, $moduleName, $handlerClass));
			}
		}

		/**
		 * List handler classes for a module
		 *
		 * @param moduleName - The name of the module
		 */
		function listHandlersForModule($moduleName){
		  $adb = $this->adb;
		  $result = $adb->pquery('SELECT handler_class FROM mycrm_eventhandler_module WHERE module_name=?', array($moduleName));
		  $it = new SqlResultIterator($adb, $result);
		  $arr = array();
		  foreach($it as $row){
			$arr[] = $row->handler_class;
		  }
		  return $arr;
		}

		/**
		 * List active events.
		 *
		 * @return A list of registered events.
		 */
		function listActiveEventHandlers(){
			$adb = $this->adb;
			$result = $adb->pquery("select * from mycrm_eventhandlers where is_active=true",
					array());
			return $this->listEventHandlers($result);
		}

		function listAllEventHandlers(){
			$adb = $this->adb;
			$result = $adb->pquery("select * from mycrm_eventhandlers", array());
			return $this->listEventHandlers($result);

		}

		private function listEventHandlers($result){
			$adb = $this->adb;
			$it = new SQLResultIterator($adb, $result);
			$out = array();
			foreach($it as $row){
				$el = array();
				$el['eventName'] = $row->event_name;
				$el['handlerPath'] = $row->handler_path;
				$el['handlerClass'] = $row->handler_class;
				$el['condition'] = $row->cond;
				$el['isActive'] = $row->is_active;
				$out[] = $el;
			}
			return $out;
		}
	}
?>