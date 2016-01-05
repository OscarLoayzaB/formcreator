<?php
/**
 * Define the plugin's version and informations
 *
 * @return Array [name, version, author, homepage, license, minGlpiVersion]
 */
function plugin_version_formcreator ()
{
   return array('name'       => _n('Form', 'Forms', 2, 'formcreator'),
            'version'        => '1.2.5+1.0',
            'author'         => '<a href="mailto:jmoreau@teclib.com">Jérémy MOREAU</a>
                                  - <a href="http://www.teclib.com">Teclib\' </a> - Oscar Loayza B. <a href="http://www.carm.es">CARM</a>',
            'homepage'       => 'https://github.com/TECLIB/formcreator',
            'license'        => '<a href="../plugins/formcreator/LICENSE" target="_blank">GPLv2</a>',
            'minGlpiVersion' => "0.85");
}

/**
 * Check plugin's prerequisites before installation
 *
 * @return boolean
 */
function plugin_formcreator_check_prerequisites ()
{
   if (version_compare(GLPI_VERSION,'0.85','lt') || version_compare(GLPI_VERSION,'0.91','ge')) {
      echo 'This plugin requires GLPI >= 0.85 and GLPI < 0.91';
   } else {
      return true;
   }
   return false;
}

/**
 * Check plugin's config before activation (if needed)
 *
 * @param string $verbose Set true to show all messages (false by default)
 * @return boolean
 */
function plugin_formcreator_check_config($verbose=false)
{
   return true;
}

/**
 * Initialize all classes and generic variables of the plugin
 */
function plugin_init_formcreator ()
{
   global $PLUGIN_HOOKS;

   // Set the plugin CSRF compliance (required since GLPI 0.84)
   $PLUGIN_HOOKS['csrf_compliant']['formcreator'] = true;

   $plugin = new Plugin();
   if (isset($_SESSION['glpiID'])
      && $plugin->isInstalled('formcreator') && $plugin->isActivated('formcreator')) {

      // Massive Action definition
      $PLUGIN_HOOKS['use_massive_action']['formcreator'] = 1;

      $PLUGIN_HOOKS['menu_toadd']['formcreator'] = array(
         'admin'    => 'PluginFormcreatorForm',
         'helpdesk' => 'PluginFormcreatorFormlist',
      );


      if (strpos($_SERVER['REQUEST_URI'], "plugins/formcreator") !== false
          || strpos($_SERVER['REQUEST_URI'], "central.php") !== false
          || isset($_SESSION['glpiactiveprofile']) &&
             $_SESSION['glpiactiveprofile']['interface'] == 'helpdesk') {

          // Add specific CSS
         $PLUGIN_HOOKS['add_css']['formcreator'][] = "css/styles.css";

         $PLUGIN_HOOKS['add_css']['formcreator'][]        = 'lib/pqselect/pqselect.min.css';
         $PLUGIN_HOOKS['add_javascript']['formcreator'][] = 'lib/pqselect/pqselect.min.js';

         // Add specific JavaScript
         $PLUGIN_HOOKS['add_javascript']['formcreator'][] = 'scripts/forms-validation.js.php';
         //$PLUGIN_HOOKS['add_javascript']['formcreator'][] = 'scripts/scripts.js.php';
      }

   // [CRI] : Add plugin_formcreator_postinit
   $PLUGIN_HOOKS['post_init']['formcreator'] = 'plugin_formcreator_postinit';
      // Add a link in the main menu plugins for technician and admin panel
      $PLUGIN_HOOKS['menu_entry']['formcreator'] = 'front/formlist.php';

      // Config page
      $plugin = new Plugin();
      $links  = array();
      if (Session::haveRight('entity', UPDATE) && $plugin->isActivated("formcreator")) {
         $PLUGIN_HOOKS['config_page']['formcreator'] = 'front/form.php';
         $links['config'] = '/plugins/formcreator/front/form.php';
         $links['add']    = '/plugins/formcreator/front/form.form.php';
      }
      $img = '<img  src="' . $GLOBALS['CFG_GLPI']['root_doc'] . '/plugins/formcreator/pics/check.png"
                  title="' . __('Forms waiting for validation', 'formcreator') . '" alt="Waiting forms list" />';

      $links[$img] = '/plugins/formcreator/front/formanswer.php';

      // Set options for pages (title, links, buttons...)
      $links['search'] = '/plugins/formcreator/front/formlist.php';
      $PLUGIN_HOOKS['submenu_entry']['formcreator']['options'] = array(
         'config'       => array('title'  => __('Setup'),
                                 'page'   => '/plugins/formcreator/front/form.php',
                                 'links'  => $links),
         'options'      => array('title'  => _n('Form', 'Forms', 2, 'formcreator'),
                                 'links'  => $links),
      );

      // Load field class and all its method to manage fields
      Plugin::registerClass('PluginFormcreatorFields');

      // Notification
      Plugin::registerClass('PluginFormcreatorFormanswer', array(
         'notificationtemplates_types' => true
      ));

      if ($_SESSION['glpi_use_mode'] == Session::DEBUG_MODE && isset($_SESSION['glpimenu'])) {
         unset($_SESSION['glpimenu']);
      }
   }
}

/**
 * Encode special chars
 *
 * @param  String    $string  The string to encode
 * @return String             The encoded string
 */
function plugin_formcreator_encode($string)
{
   $string = stripcslashes($string);
   $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
   $string = str_replace('&apos;', "'", $string);
   $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
   return $string;
}

/**
 * Encode special chars
 *
 * @param  String    $string  The string to encode
 * @return String             The encoded string
 */
function plugin_formcreator_decode($string)
{
   $string = stripcslashes($string);
   $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
   $string = str_replace('&apos;', "'", $string);
   return $string;
}
