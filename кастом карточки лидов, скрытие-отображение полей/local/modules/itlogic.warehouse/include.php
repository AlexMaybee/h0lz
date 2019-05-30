<?php

\Bitrix\Main\Loader::registerAutoLoadClasses(
	"itlogic.warehouse",
	array(

		"Itlogic\\Warehouse\\WarehouseDao" => "lib/WarehouseDao.php",
		"Itlogic\\Warehouse\\NewDealTypeDao" => "lib/NewDealTypeDao.php",
		
	)
);


