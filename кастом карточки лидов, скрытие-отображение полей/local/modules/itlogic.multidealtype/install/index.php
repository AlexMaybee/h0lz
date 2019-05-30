<?
IncludeModuleLangFile(__FILE__);

if (class_exists("itlogic.multidealtype"))
	return;

class itlogic_multidealtype extends CModule
{
	var $MODULE_ID = "itlogic.multidealtype";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;

	function __construct()
	{
		$arModuleVersion = array();

		include(dirname(__FILE__)."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("MULTIDEALTYPE_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("MULTIDEALTYPE_INSTALL_DESCRIPTION");
	}


	function InstallDB()
	{
		global $DB;

		$DB->RunSQLBatch(dirname(__FILE__)."/sql/install.sql");

		RegisterModule("itlogic.multidealtype");

		return true;
	}

	function UnInstallDB()
	{
		global $DB;

		$DB->RunSQLBatch(dirname(__FILE__)."/sql/uninstall.sql");


		UnRegisterModule("itlogic.multidealtype");

		return true;
	}

	function InstallFiles()
	{

		CopyDirFiles(dirname(__FILE__)."/components", $_SERVER["DOCUMENT_ROOT"]."/local/components", true, true);
		return true;

	}

	function UnInstallFiles()
	{
		return true;
	}

	function DoInstall()
	{
		$this->InstallFiles();
		$this->InstallDB();
	}

	function DoUninstall()
	{
		$this->UnInstallDB();
		$this->UnInstallFiles();
	}
}