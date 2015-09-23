<?php
include ("../../../inc/includes.php");

Session::checkLoginUser();


$Plugin       			= new PluginFormcreatorForm();
$PluginItem       		= new PluginFormcreatorForm_Item();


if (isset($_POST["add"])) {
   

} else if (isset($_POST["agregarGrupo"])) {


    $listForm = $PluginItem->find("plugin_formcreator_forms_id = ".$_POST['peticion_id']." and items_id = ".$_POST['groups_id']." and itemtype = '".$_POST['itemtype']."'");

	if (empty($listForm))
	{
		$input11 = array('plugin_formcreator_forms_id' => $_POST['peticion_id'],
				 'items_id'                        => $_POST['groups_id'],
				 'itemtype'                        => $_POST['itemtype']);

		$PluginItem->add($input11);   

	}

   Html::back();

} else {
		   
      Html::header(
         PluginFormcreatorForm::getTypeName(2),
         $_SERVER['PHP_SELF'],
         'plugins',
         'formcreator',
         'form'
      );

      $form->display($_GET);			   

   //$form->showForm($_REQUEST["id"]);

   Html::footer();
}
?>