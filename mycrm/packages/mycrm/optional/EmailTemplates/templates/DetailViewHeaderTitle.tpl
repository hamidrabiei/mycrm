{*<!--
/*********************************************************************************
** The contents of this file are subject to the mycrm CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  mycrm CRM Open Source
 * The Initial Developer of the Original Code is mycrm.
 * Portions created by mycrm are Copyright (C) mycrm.
 * All Rights Reserved.
*
 ********************************************************************************/
-->*}
{strip}
	<span class="span10 margin0px">
		<span class="row-fluid">
			<span class="recordLabel font-x-x-large textOverflowEllipsis span pushDown" title="{$RECORD->getName()}">
				<span class="templatename">{decode_html($RECORD->get('templatename'))}</span>&nbsp;
			</span>
		</span>
	</span>
{/strip}