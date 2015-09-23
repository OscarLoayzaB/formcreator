<?php

class PluginFormcreatorFormgroup extends CommonDBRelation {
	
   // From CommonDBRelation
   static $itemtype_1                 = 'PluginFormcreatorForm';
   static $items_id_1                 = 'plugin_formcreator_forms_id';

   static $itemtype_2                 = 'itemtype';
   static $items_id_2                 = 'items_id';

   static public $logs_for_item_1     = false;	

   public static function canCreate()
   {
      return Session::haveRight("entity", UPDATE);
   }

   public static function canView()
   {
      return Session::haveRight("entity", UPDATE);
   }
   
   /**
    * Get the standard massive actions which are forbidden
    *
    * @since version 0.84
    *
    * @return an array of massive actions
    **/
   public function getForbiddenStandardMassiveAction() {

      $forbidden   = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      return $forbidden;
   }

   /**
    * Clean table when item is purged
    *
    * @param $item Object to use
    *
    * @return nothing
    **/
   public static function cleanForItem(CommonDBTM $item) {

      $temp = new self();
      $temp->deleteByCriteria(
               array('itemtype' => $item->getType(),
                        'items_id' => $item->getField('id'))
      );
   }   

   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
         switch($item->getType()) {
            case 'PluginFormcreatorForm':
               //Document_Item::displayTabContentForItem($item);
			   PluginFormcreatorFormgroup::showGrupos($item);
            break;
         }

      return true;
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      return __('Grupos','Grupos');
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
	static function showGrupos(CommonDBTM $item, $withtemplate='') {
	global $DB,$CFG_GLPI;  
	$objeto = $item->getType();
	$appli = new $objeto();
	$ID = $item->fields['id'];
	$appli->getFromDB($item->fields['id']);
	//$canedit = $appli->can($appli->fields['id'],'w');
	$canedit = $appli->canCreate();
	//$canedit = Session::haveRight('update_ticket', 1);
	


      $rand    = mt_rand();

      $groups  = self::getFormGroups($ID);
      $used    = array();
      if (!empty($groups)) {
         foreach ($groups as $data) {
            $used[$data["id"]] = $data["id"];
         }
      }	
	
	
	echo "<div class='center'>";	  
	echo "<form name='form_grupo' id='form_grupo' method='post' action='../front/formgroup.form.php'>";
    echo "<table class='tab_cadre_fixe'>";	
		echo "<th colspan=2>".__('Grupos','Grupos')."</th>";	
		echo "<tr>";
		echo "<th>".__('Grupo','Grupo')."&nbsp;:"."</th>";
			echo "<td>";
			if ($canedit) {
				Group::dropdown(array('name'      => 'groups_id',
                            'entity'    => $_SESSION['glpiactive_entity'],
                            'condition' => '`is_assign`'));
			}
			echo "</td>";
		//-----------------------------	
		echo "</tr>";	
	echo "<tr class='tab_bg_1'>";	
	echo "<td colspan='2' align = 'center'>";
		echo "<input type='submit' name='agregarGrupo' value='Agregar' class='submit'>";	
    echo "</td></tr>";	
	echo "</table>";
	echo "<input type='hidden' name='itemtype' value='Group'>";
	echo "<input type='hidden' name='peticion_id' value='".$item->fields['id']."'>";
	//echo "</form>";
	Html::closeForm();
	echo "</div>";
	
	// Listado de Grupos 
	
      echo "<div class='spaced'>";
	  /*
      if ($canedit && count($used)) {
         $rand = mt_rand();
         Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
         echo "<input type='hidden' name='plugin_formcreator_forms_id' value='".$item->fields['id']."'>";
         $massiveactionparams = array('num_displayed' => count($used),
                           'container'     => 'mass'.__CLASS__.$rand);
         Html::showMassiveActions($massiveactionparams);
      }*/
      if ($canedit && ($withtemplate < 2)) {
         Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
         $massiveactionparams = array('num_displayed'  => count($used));
         Html::showMassiveActions($massiveactionparams);
      }
      echo "<table class='tab_cadre_fixehov'>";
      $header_begin  = "<tr>";
      $header_top    = '';
      $header_bottom = '';
      $header_end    = '';

      if ($canedit && count($used)) {
         $header_begin  .= "<th width='10'>";
         $header_top    .= Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
         $header_bottom .= Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
         $header_end    .= "</th>";
      }
      $header_end .= "<th>".Group::getTypeName(1)."</th>";

      echo $header_begin.$header_top.$header_end;

      $group = new Group();
      if (!empty($groups)) {
         Session::initNavigateListItems('PluginFormcreatorForm',
                              //TRANS : %1$s is the itemtype name,
                              //        %2$s is the name of the item (used for headings of a list)
                                        sprintf(__('%1$s = %2$s'),
                                                $item::getTypeName(1), $item->getName()));

         foreach ($groups as $data) {
            if (!$group->getFromDB($data["id"])) {
               continue;
            }
            Session::addToNavigateListItems('PluginFormcreatorForm', $data["id"]);
            echo "<tr class='tab_bg_1'>";

            if ($canedit && count($used)) {
               echo "<td width='10'>";
			  // echo __CLASS__;
               Html::showMassiveActionCheckBox('PluginFormcreatorForm_Item', $data["linkID"]);
               echo "</td>";
            }
            $link = $data["completename"];
            if ($_SESSION["glpiis_ids_visible"]) {
               $link = sprintf(__('%1$s (%2$s)'), $link, $data["id"]);
            }
            $href = "<a href='".$CFG_GLPI["root_doc"]."/front/group.form.php?id=".$data["id"]."'>".
                      $link."</a>";
            echo "<td>".$group->getLink()."</td>";
            echo "</tr>";
         }
         echo $header_begin.$header_bottom.$header_end;

      } else {
         echo "<tr class='tab_bg_1'>";
         echo "<td colspan='5' class='center'>".__('None')."</td></tr>";
      }
      echo "</table>";

      if ($canedit && count($used)) {
         $massiveactionparams['ontop'] = false;
         Html::showMassiveActions($massiveactionparams);
         Html::closeForm();
      }
      echo "</div>";	

	
	
	
	}

	
	
   /**
    * @param $users_id
    * @param $condition    (default '')
   **/
   static function getFormGroups($formid, $condition='') {
      global $DB;

      $groups = array();
      $query  = "SELECT `glpi_groups`.*,
                        `glpi_plugin_formcreator_forms_items`.`id` AS IDD,
                        `glpi_plugin_formcreator_forms_items`.`id` AS linkID
                 FROM `glpi_plugin_formcreator_forms_items`
                 LEFT JOIN `glpi_groups` ON (`glpi_groups`.`id` = `glpi_plugin_formcreator_forms_items`.`items_id`)
                 WHERE    `glpi_plugin_formcreator_forms_items`.`itemtype` = 'Group'
							AND `glpi_plugin_formcreator_forms_items`.`plugin_formcreator_forms_id` = '$formid' ";
      if (!empty($condition)) {
         $query .= " AND $condition ";
      }
      $query.=" ORDER BY `glpi_groups`.`name`";

      foreach ($DB->request($query) as $data) {
         $groups[] = $data;
      }
      return $groups;
   }	
	
   public function deleteItemByFormcreatorAndItem($plugin_accounts_accounts_id,$items_id,$itemtype) {

      if ($this->getFromDBbyFormcreatorAndItem($plugin_accounts_accounts_id,$items_id,$itemtype)) {
         $this->delete(array('id'=>$this->fields["id"]));
         return true;
      }
      return false;
   }

	
   public function getFromDBbyFormcreatorAndItem($plugin_formcreators_form_id,$items_id,$itemtype) {
      global $DB;

      $query = "SELECT * FROM `".$this->getTable()."` " .
               "WHERE `plugin_formcreator_forms_id` = '" . $plugin_formcreators_form_id . "'
                        AND `itemtype` = '" . $itemtype . "'
                                 AND `items_id` = '" . $items_id . "'";
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 1) {
            return false;
         }
         $this->fields = $DB->fetch_assoc($result);
         if (is_array($this->fields) && count($this->fields)) {
            return true;
         } else {
            return false;
         }
      }
      return false;
   }	

}
