<?php
require_once ('glpiselect-field.class.php');
class usersField extends glpiselectField
{

   public function displayField($canEdit = true)
   {
      $this->fields['values'] = 'User';
      return parent::displayField($canEdit);
   }

   public function getValue()
   {
      if ($this->fields['default_values'] == 'CURRENT_USER') {
         return $_SESSION['glpiID'];
      } else {
         return parent::getValue();
      }
   }

   public static function getName()
   {
      return _n('User', 'Users', 2);
   }

   public static function getJSFields()
   {
      return ['required', 'show_empty', 'users', 'show_type'];
   }
}
