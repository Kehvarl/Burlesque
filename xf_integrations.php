<?php
   
    /*
     *['forum']
     *viewContent
     *editOwnPost
     *deleteOwnPost
     *editAnyPost
     *deleteAnyPost
     *undelete
     *
     *['general']
     *warn
     *viewWarning
     *viewIps
     *
     *
     * $visitor->hasAdminPermission('user')     // Can manage User
     * $visitor->hasAdminPermission('viewLogs') // Can view Logs
     * $visitor->hasAdminPermission('node')     // Can manage Nodes
     */
    
    function initialize($fileDir = '/home/amordev/public_html')//'/var/www/html')
    {
		$startTime = microtime(true);
		
		require($fileDir . '/library/XenForo/Autoloader.php');
		XenForo_Autoloader::getInstance()->setupAutoloader($fileDir . '/library');

		XenForo_Application::initialize($fileDir . '/library', $fileDir);
		XenForo_Application::set('page_start_time', $startTime);
	 
		$dependencies = new XenForo_Dependencies_Public();
		$dependencies->preLoadData();
		global $session;
		XenForo_Session::startPublicSession();

		return XenForo_Visitor::getInstance();
	}
    
    function can_view($v)
    {
		return $v->hasPermission('forum', 'viewContent');
	}
	
	function can_edit_own($v)
	{
		return $v->hasPermission('forum', 'editOwnPost');
	}
	
	function can_delete_own($v)
	{
		return $v->hasPermission('forum', 'deleteOwnPost');
	}
	
	function can_edit_any($v)
	{
		return $v->hasPermission('forum', 'editAnyPost');
	}
	
	function can_delete_any($v)
	{
		return $v->hasPermission('forum', 'deleteAnyPost');
	}
	
	function can_undelete($v)
	{
		return $v->hasPermission('forum', 'undelete');
	}
	
	function can_warn($v)
	{
		return $v->hasPermission('general', 'warn');
	}
	
	function can_view_warning($v)
	{
		return $v->hasPermission('general', 'viewWarning');
	}
?>
