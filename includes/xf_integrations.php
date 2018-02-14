<?php

/*
  * Some forum permission details used later in this document
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

  /**
  * Mechanism for interacting with Xenforo forum and the user's session.
  * Used for authentication, permissions, and identification.
  */
  class Burlesque_Xenforo_Integration
  {

    public $Session;

    /**
    * Open Xenforo Connection
    * @param  string  fileDir     Path to Xenforo installation
    */
    public function __construct($fileDir = '/home/amordev/public_html')
    {
      $startTime = microtime(true);

      require($fileDir . '/library/XenForo/Autoloader.php');
      XenForo_Autoloader::getInstance()->setupAutoloader($fileDir . '/library');
      XenForo_Application::initialize($fileDir . '/library', $fileDir);
      XenForo_Application::set('page_start_time', $startTime);

      $dependencies = new XenForo_Dependencies_Public();
      $dependencies->preLoadData();

      XenForo_Session::startPublicSession();
      $this->Session = XenForo_Application::get('session');
    }

    public function getVisitor()
    {
      return XenForo_Visitor::getInstance();
    }

    public function getUserName()
    {
      return $this->getVisitor()->getUserName();
    }

    public function getUserId()
    {
  		return $this->getVisitor()->getUserId();
  	}

    public function isLoggedIn()
    {
      return ($this->getUserId());
    }

    public function getUser($id=null)
    {
      if($id === null && !$this->isLoggedIn())
        return array();

      if( $id !== null)
      {
        $userId = $id;
      }
      else {
        $userId = $this->getVisitor()->getUserId();
      }
      return Xenforo_Model::create('XenForo_Model_User')->getFullUserById($userId);
    }

    public function getVisitorTimezone()
    {
      return $this->getVisitor()->get('timezone');
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
  }
?>
