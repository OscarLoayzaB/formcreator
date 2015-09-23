<?php
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFormcreatorForm_Item extends CommonDBRelation {

   //public $rightname = 'entity';
   // From CommonDBRelation
   static public $itemtype_1 = 'PluginFormcreatorForm';
   static public $items_id_1 = 'plugin_formcreator_forms_id';
   static public $take_entity_1 = false;
   
   static public $itemtype_2 = 'itemtype';
   static public $items_id_2 = 'items_id';
   static public $take_entity_2 = true;   




   /**
    * Hook called After an item is uninstall or purge
    */
   public static function cleanForItem(CommonDBTM $item) {

      $temp = new self();
      $temp->deleteByCriteria(
         array('itemtype' => $item->getType(),
               'items_id' => $item->getField('id'))
      );
   }
   
   public static function install(Migration $migration)
   {
      $table = getTableForItemType(__CLASS__);
      if (!TableExists($table)) {
		  $migration->displayMessage("Installing $table");
		 $query = "CREATE TABLE IF NOT EXISTS `$table` (
					  `id` int(11) NOT NULL auto_increment,
					  `plugin_formcreator_forms_id` int(11) NOT NULL default '0',
					  `items_id` int(11) NOT NULL default '0',
					  `itemtype` varchar(100) collate utf8_unicode_ci NOT NULL default '',
					  PRIMARY KEY  (`id`),
					  UNIQUE KEY `unicity` (`plugin_formcreator_forms_id`,`items_id`,`itemtype`),
					  KEY `plugin_formcreator_forms_id` (`plugin_formcreator_forms_id`),
					  KEY `item` (`itemtype`,`items_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

         $GLOBALS['DB']->query($query) or die($GLOBALS['DB']->error());
      } 

      return true;
   }   
 
    public static function uninstall()
   {
      $query = "DROP TABLE IF EXISTS `" . getTableForItemType(__CLASS__) . "`";
      return $GLOBALS['DB']->query($query) or die($GLOBALS['DB']->error());
   }
   
}
