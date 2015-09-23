<?php
// [CRI] : Add Tab para Informaci�n de Pedido
function plugin_formcreator_postinit() {
   global $CFG_GLPI, $PLUGIN_HOOKS;
   foreach (PluginFormcreatorForm::getTypes(true) as $type) {

      CommonGLPI::registerStandardTab($type, 'PluginFormcreatorForm');
   }
}
/**
 * Install all necessary elements for the plugin
 *
 * @return boolean True if success
 */
function plugin_formcreator_install()
{
   $version   = plugin_version_formcreator();
   $migration = new Migration($version['version']);

   // Parse inc directory
   foreach (glob(dirname(__FILE__).'/inc/*') as $filepath) {
      // Load *.class.php files and get the class name
      if (preg_match("/inc.(.+)\.class.php/", $filepath, $matches)) {
         $classname = 'PluginFormcreator' . ucfirst($matches[1]);
         include_once($filepath);
         // If the install method exists, load it
         if (method_exists($classname, 'install')) {
            $classname::install($migration);
         }
      }
   }

   return true ;
}

/**
 * Uninstall previously installed elements of the plugin
 *
 * @return boolean True if success
 */
function plugin_formcreator_uninstall()
{
   // Parse inc directory
   foreach (glob(dirname(__FILE__).'/inc/*') as $filepath) {
      // Load *.class.php files and get the class name
      if (preg_match("/inc.(.+)\.class.php/", $filepath, $matches)) {
         $classname = 'PluginFormcreator' . ucfirst($matches[1]);
         include_once($filepath);
         // If the install method exists, load it
         if (method_exists($classname, 'uninstall')) {
            $classname::uninstall();
         }
      }
   }
   return true ;
}

/**
 * Define Dropdown tables to be manage in GLPI :
 */
function plugin_formcreator_getDropdown()
{
   return array(
       'PluginFormcreatorHeader'   => _n('Header', 'Headers', 2, 'formcreator'),
       'PluginFormcreatorCategory' => _n('Form category', 'Form categories', 2, 'formcreator'),
   );
}

/**
 * Define specific search request
 *
 * @param  String $type    Itemtype for the search engine
 * @return String          Specific search request
 */
function plugin_formcreator_addDefaultWhere($type)
{
   switch ($type) {
      case "PluginFormcreatorFormanswer" :
         if (Session::haveRight('config', UPDATE)) {
            return " 1=1 ";
         } else if (Session::haveRight('ticketvalidation', TicketValidation::VALIDATEINCIDENT)
            || Session::haveRight('ticketvalidation', TicketValidation::VALIDATEREQUEST)) {
         return " `glpi_plugin_formcreator_formanswers`.`validator_id` = " . $_SESSION['glpiID'].
                " OR `glpi_plugin_formcreator_formanswers`.`requester_id` = " . $_SESSION['glpiID'];

         } else {
            return " `glpi_plugin_formcreator_formanswers`.`requester_id` = " . $_SESSION['glpiID'];
         }
         break;
      default:
         return '';
   }
}


// function plugin_formcreator_MassiveActions($type) {

// //option afficher dans la lsite des masive actions
// }

// function plugin_formcreator_MassiveActionsFieldDisplay($options=array()) {

// //formulaire pour l'action choisi par l'iutiliqsateur
// }

// function plugin_formcreator_MassiveActionsProcess($data) {

// //traitement effectuer a la validation du formulaire
// }
// Define actions :
function plugin_formcreator_MassiveActions($type) {

   switch ($type) {
      // New action for core and other plugin types : name = plugin_PLUGINNAME_actionname
      case 'PluginFormcreatorForm' :
         return array('PluginFormcreatorForm'.MassiveAction::CLASS_ACTION_SEPARATOR.'Duplicate' =>
                                                              _x('button', 'Duplicate'));
		break; //  [CRI] : break

      //  [CRI] : Actions for types provided by the plugin are included inside the classes
      case 'Ticket' :
         return array("PluginFormcreatorForm".MassiveAction::CLASS_ACTION_SEPARATOR.'Pedido'    => __('Pedido de catalogo','Pedido de catalogo'));
		break;
      default :
		break;

   }
   return array();
}

//  [CRI] : Add getAddSearchOptions
function plugin_formcreator_getAddSearchOptions($itemtype) {
   global $CFG_GLPI;

   $sopt = array();
   
    if ($itemtype == 'Ticket') {	
		$sopt['informacionpedido'] = __('Informacion del pedido', 'Informacion del pedido');
		
		// ID de la petici�n del cat�logo.
		$sopt[1500]['table']      = 'glpi_plugin_formcreator_forms';
		$sopt[1500]['name']      =  __('Pedido de Catalogo', 'Pedido de catalogo');
		$sopt[1500]['field']      = "name";
        $sopt[1500]['datatype']       = 'itemlink';
		$sopt[1500]['massiveaction'] = true;
        $sopt[1500]['itemlink_type']  = 'PluginFormcreatorForm';
        $sopt[1500]['joinparams']     = array('beforejoin'
                                                => array('table'      => 'glpi_plugin_formcreator_forms_items',
                                                         'joinparams' => array('jointype' => 'itemtype_item')));




		
	}
	return $sopt;

 }


/**
 * [CRI] : Add getAddSearchOptionsDefine dropdown relations
**/
function plugin_formcreator_getDatabaseRelations() {

   $plugin = new Plugin();
   if ($plugin->isActivated("formcreator")) {
	  return array('glpi_plugin_formcreator_form'
									 => array('glpi_plugin_formcreator_form_items'
											  => 'plugin_formcreator_forms_id'));
   }
   return array();
}
// // How to display specific update fields ?
// // options must contain at least itemtype and options array
// function plugin_formcreator_MassiveActionsFieldsDisplay($options=array()) {
//    //$type,$table,$field,$linkfield

//    $table     = $options['options']['table'];
//    $field     = $options['options']['field'];
//    $linkfield = $options['options']['linkfield'];

//    if ($table == getTableForItemType($options['itemtype'])) {
//       // Table fields
//       switch ($table.".".$field) {
//          case 'glpi_plugin_example_examples.serial' :
//             _e("Not really specific - Just for example", 'example');
//             //Html::autocompletionTextField($linkfield,$table,$field);
//             // Dropdown::showYesNo($linkfield);
//             // Need to return true if specific display
//             return true;
//       }

//    } else {
//       // Linked Fields
//       switch ($table.".".$field) {
//          case "glpi_plugin_example_dropdowns.name" :
//             _e("Not really specific - Just for example", 'example');
//             // Need to return true if specific display
//             return true;
//       }
//    }
//    // Need to return false on non display item
//    return false;
// }
