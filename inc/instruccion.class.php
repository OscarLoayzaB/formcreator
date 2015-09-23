<?php

class PluginFormcreatorInstruccion extends CommonDBTM {

   public static function canCreate()
   {
      return Session::haveRight("entity", UPDATE);
   }

   public static function canView()
   {
      return Session::haveRight("entity", UPDATE);
   }

   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
         switch($item->getType()) {
            case 'PluginFormcreatorForm':
               Document_Item::displayTabContentForItem($item);
            break;
         }

      return true;
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      return __('Documents','Documentos');
   }



   function showForm($params,$options=array()) {
      global $CFG_GLPI;

      if ($params['id'] > 0) {
         //$this->check($ID,'r');
      } else {
         // Create item
         //$this->check(-1,'w');
      }

   }

   function prepareInputForAdd($input) {
      global $CFG_GLPI;

      if (empty($input['name'])) {

         Session::addMessageAfterRedirect(__('Tu Ticket Target no tiene Nombre.'), false, ERROR);
         return false;
      }

      return $input;
   }

	/*
	Función para mostrar los documentos que han sido asociados usando la pestaña Documentos, al pulsar la pestaña Principal.
	*/
	static function showInstrucciontecnica($item, $instID, $itemtype) {
	global $DB,$CFG_GLPI;  

	$objeto = $item->getType();
	$appli = new $objeto();
	$appli->getFromDB($item->fields['id']);
	//$canedit = $appli->can($appli->fields['id'],'w');
	$canedit = $appli->canCreate();
	//$canedit = Session::haveRight('update_ticket', 1);
	

	
	echo "<div class='center'>";	  
	echo "<form name='form_pedido' id='form_pedido' method='post' action='../plugins/formcreator/front/instruccion.form.php'>";
    echo "<table class='tab_cadre_fixe'>";	
		echo "<th colspan=2>".__('Informacion del pedido','Informacion del pedido')."</th>";	
		echo "<tr>";
		echo "<th>".__('Pedido de catalogo','Pedido de catalogo')."&nbsp;:"."</th>";
			echo "<td>";
			if ($canedit) {
					 //Dropdown::show('PluginFormcreatorForm', array('name'  => 'peticion_id',
					//							  'value'  => $instID));
					$table = getTableForItemtype('PluginFormcreatorForm');
					$sections = array();
					$sql = "SELECT `id`, `name`
							FROM $table
							ORDER BY `name`";
					$result = $GLOBALS['DB']->query($sql);
					while ($section = $GLOBALS['DB']->fetch_array($result)) {
					   $sections[$section['id']] = $section['name'];
					}
					$sections=array("0"=>"----") + $sections; 
					Dropdown::showFromArray('peticion_id', $sections, array(
					   'value' => $instID				   
					));
			
			}
			else
			{
					echo Dropdown::getDropdownName("glpi_plugin_formcreator_forms", $instID);
			}
			echo "</td>";
		//-----------------------------	
		echo "</tr>";	
	echo "<tr class='tab_bg_1'>";	
	echo "<td colspan='2' align = 'center'>";
		echo "<input type='submit' name='actualizarPedido' value='Actualizar' class='submit'>";	
    echo "</td></tr>";	
	echo "</table>";
	echo "<input type='hidden' name='itemtype' value='".$item->getType()."'>";
	echo "<input type='hidden' name='tickets_id' value='".$item->fields['id']."'>";
	//echo "</form>";
	Html::closeForm();
	echo "</div>";
	
	
	echo "<div class='center'>";
	echo "<table class='tab_cadre_fixehov'>";
	
	
	$query = "SELECT DISTINCT `itemtype`
					FROM `glpi_documents_items`
					WHERE `items_id` = '$instID' AND `itemtype` = '$itemtype'
					ORDER BY `itemtype`";
		  $result = $DB->query($query);
		  $number = $DB->numrows($result);
		  $i = 0;
		  if (Session::isMultiEntitiesMode()) {
			 $colsup = 1;
		  } else {
			 $colsup = 0;
		  }
		  
      if ($number > 0) {
	  
         echo "<tr><th>".__('Heading')."</th>";
         echo "<th>".__('Name')."</th>";
         echo "<th>".__('Web link')."</th>";
         echo "<th>".__('File')."</th>";
         echo "<th>".__('Entity')."</th>";
         echo "</tr>";
      }		  
		  
		  
			 for ($i=0 ; $i < $number ; $i++) {
			 $type = $DB->result($result, $i, "itemtype");
			 if (!class_exists($type)) {
				continue;
			 }
			 $item = new $type();
			 if ($canedit) {
			 //if ($item->canView()) {
			  $column = "name";
			  $query1 = "SELECT glpi_documents.*, glpi_documents_items.id AS IDD, glpi_entities.id AS entity
				FROM glpi_documents_items, glpi_documents LEFT JOIN glpi_entities ON (glpi_entities.id = glpi_documents.entities_id)
				WHERE glpi_documents.id = glpi_documents_items.documents_id
					AND glpi_documents_items.itemtype = '".$itemtype."'
					AND glpi_documents_items.items_id = ".$instID."
					AND glpi_documents.is_deleted = 0
				ORDER BY glpi_entities.completename, glpi_documents.name";		
			
				if ($result_linked1 = $DB->query($query1)) {
				   if ($DB->numrows($result_linked1)) {

					 $document = new Document();
					 while ($data = $DB->fetch_assoc($result_linked1)) {
						 $item->getFromDB($data["id"]);
						 Session::addToNavigateListItems($type,$data["id"]);
						 $ID = "";
						 $downloadlink = NOT_AVAILABLE;
							if ($document->getFromDB($data["id"])) {
							   $downloadlink = $document->getDownloadLink();
							}						 
						 

						 if($_SESSION["glpiis_ids_visible"] || empty($data["name"])) {
							$ID = " (".$data["id"].")";
						 }
						 $name= __('Informacion de pedido','Informacion de pedido'); //item->getLink();

						 echo "<tr class='tab_bg_1'>";
						  // echo "<td class='center'>".$name."</td>";
							echo "<td class='center'>".Dropdown::getDropdownName("glpi_documentcategories",
																				 $data["documentcategories_id"]);
							echo "</td>";						  
						  

						 $nombre = $data['name'];
						 echo "<td class='center' ".
							   (isset($data['deleted']) && $data['deleted']?"class='tab_bg_2_2'":"").">".
							   $nombre."</td>";
						echo "<td class='center'>";
						if (!empty($data["link"])) {
						   echo "<a target=_blank href='".formatOutputWebLink($data["link"])."'>".$data["link"];
						   echo "</a>";
						} else {;
						   echo "&nbsp;";
						}
						echo "</td>";								   
						 echo "<td class='center'>$downloadlink</td>";						
						if (Session::isMultiEntitiesMode()) {
							echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",
																				 $data['entity']).
								  "</td>";
						 }
						 echo "</tr>";
					  }

				   }
				}
			 }
		  }
		echo "</table>";
		echo "</div>";
	}

}
