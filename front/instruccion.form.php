<?php
include ("../../../inc/includes.php");

Session::checkLoginUser();


$Plugin       			= new PluginFormcreatorForm();

$PluginItem       		= new PluginFormcreatorForm_Item();


if (isset($_POST["add"])) {
   

} else if (isset($_POST["actualizarPedido"])) {


    $listForm = $PluginItem->find("items_id = ".$_POST['tickets_id']." and itemtype = '".$_POST['itemtype']."'");

	if (empty($listForm))
	{
		$input11 = array('plugin_formcreator_forms_id' => $_POST['peticion_id'],
				 'items_id'                        => $_POST['tickets_id'],
				 'itemtype'                        => $_POST['itemtype']);

		$PluginItem->add($input11);   

	}
	else
	{
			foreach ($listForm as $form_id => $value) {
		   
		   $input = array('id' => $form_id);
		    $input['plugin_formcreator_forms_id'] = $_POST['peticion_id'];
			$input['items_id'] = $_POST['tickets_id'];
			$input['itemtype'] = $_POST['itemtype'];
		   
		   $PluginItem->update($input);
		   }
 
	}
	
	 if ($_POST['itemtype'] == "Ticket")
	{
		PluginFormcreatorForm::fromcreatorDropUserAndGrouponTicket($_POST['tickets_id']);
		PluginFormcreatorForm::updateTicketFromForm($_POST['peticion_id'],$_POST['tickets_id']);
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