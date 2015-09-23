GLPI Formcreator 0.85 ChangeLog
===============================
Version 0.90-1.2.5 + 1.0
------------------------
* Informaci�n de Pedido (Documentos), activar tab, formulario upload de ficheros y en documentos asociados al ticket generado. 
* Modificaci�n de Pedido en el ticket, cambiar relaci�n con ticket en la tabla form_items. Con acciones masivas cambiar Pedido (Form).
* A�adir nuevo nivel de Acceso GROUP_ACCESS
* Acceso al Grupo de Soporte al que pertenece el usuario de la sesion.
* A�adir tab de Grupos en el plugin.
* Modificaci�n en las funciones de comprobaci�n de acceso al Pedido (Form).
* Incorporar autorizaci�n a Showlist de Form para el perfil (SuperAdmin).
* Incroporar la traducci�n al lenguaje en Espa�ol es_ES.po y es_ES.mo.
* Modificaci�n en la migraci�n a plantillas de ticket, incluir el id de form para generar un ticket template por Form.
* Modificaci�n de metodo de visualizacion de desplegables de Pedidos en acciones masivas y las Categorias en Formulario de Form, Dropdown::Show por Dropdown:ShowArray.
 



Version 0.90-1.2.5
------------------

### Bugfixes:

* Nombre de "Destinations" limitC)es
* Question de type LDAP impossible C  crC)er
* Erreur de suppression d'une section
* Affichage des rC)ponses des "Zone de texte" avec mise en forme dans la liste des rC)ponse/validations de formulaires
* ProblC(me d'affichage des champs "Affichage du champ"
* ProblC(me d'affichage des listes dC)roulantes dans l'C)dition des questions
* ProblC(me mise en forme texte enrichi dans ticket GLPI 0.85.4 et formcreator

### Features:

* P!ategories of forms feature
* Add compatibility with GLPI 0.90.x



Version 0.85-1.2.4
------------------

> 2015-03-26

### Bugfixes:

* Fix due date selected value in form target configuration
* Fix severals issues on encoding, quotes and languages
* Fix multi-select field display for validators
* Fix a bug on ticket creation for form which don't need validation
* Send "Form validation accepted" notification only if form need to be validated


### Features:

* Redirect to login if not logged (from notifaction link)
* Don't chek entity right on answer validation
* Optimize init of plugin and load js/css only when needed


Version 0.85-1.2.3
------------------

> 2015-03-26

### Bugfixes:

* Fix validation of empty and not required number fields

### Features:

* Add migration for special chars
* Add a new notification on form answered
* Add ChangeLog file


Version 0.85-1.2.2
------------------

> 2015-03-20

###Bugfixes:

* Fix display form list in home page with the "simplified interface"
* Fix errors with special chars with PHP 5.3

###Features:

* Change display of validators dropdown in form configuration in order to improve selection on large list of validators.


Version 0.85-1.2
------------------

> 2015-02-27

###Bugfixes:

* VC)rification du champs catC)gorie C  la crC)ation d'un formulaire
* PHP Warning lors de l'ajout d'un formulaire
* Antislashes in answers are broken
* HTML descriptions no longer parsed
* Failed form validation add slashes in fields

###Features:

* Add the possibility to select target ticket actors
* Add the ability to define the Due date
* Add validation comment as first ticket followup
* Add the ability to clone a form
* Add feature to disable email notification to requester enhancement feature


Version 0.85-1.1
------------------

> 2015-02-13

###Bugfixes:

* Cannot add a question
* targetticket, lien vers le formulaire parent
* erreur js en administration d'une question
* fonction updateConditions : log dans php_error.log
* Affichage du champ non fonctionnel (et non sauvegardC))
* crash on glpi object
* Valideur du formulaire devient demandeur du ticket target
* link between questions only now work with radio button
* redirect (from notification) not working
* error missing \_user\_id_requester on ticket creation
* link for create forms (after breadcrumb) is available for non-admins users
* Validation sending (ajax get) : request uri too long
* Show field condition issue
* Forms list not displayed in central view
* List LDAP value --- Valeur liste LDAP
* PHP warnings (related to validation feature ?)
* Change links by buttons in formcreator configuration
* PHP Parse error: syntax error, unexpected T\_CONSTANT\_ENCAPSED_STRING in /var/www/glpi/plugins/formcreator/inc/targetticket.class.php on line 87

###Features:

* administration, emplacement objet glpi
* Formulaire acceptC) : AcceptC) ne s'affiche pas
* item forms in global menu must be added at the end of it
* Add WYSIWYG editor for textarea fields feature


Version 0.85-1.0
------------------

> 2014-12-18

###Features:

* Port Formcreator 0.84-2.1 to GLPI 0.85. See [Formcreator 0.84 ChangeLog](https://github.com/TECLIB/formcreator/blob/0.84-bugfixes/CHANGELOG.md)
