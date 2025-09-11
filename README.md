# UBC CWL AUTH

### About

This module's purpose is to bring together the functionality required to handle our use cases for permissions based authentication, which __can__ be used in conjunction with CWL authentication. The contrib module to handle a CWL integration in this case is __samlauth__ (https://www.drupal.org/project/samlauth).

This module and it's dependencies should work the same in Drupal 10 and Drupal 11.

Upon installation, the install file will conditionally create the 'CWL' role. Additionally it will create a Taxonomy Vocabulary called 'Visibility', and create 2 terms, 'General' and 'CWL'. Those two taxonomy terms will also have role permissions attached to them, via the **permissions_by_term** module.

The functionality provided by this module is to subscribe to 403 events, and redirect any 403s where the user does not have the CWL role to /saml/login. This redirect path will then kickoff a CWL login handshake via the **samlauth** module, if installed. Or if samlauth is not installed, a custom page or custom functionality can be put at that route.

### Files
**Event Subscriber** - Subscribes to Exceptions, but only implements logic on 403. Checks for 'CWL' role and redirects accordingly.

**AccessDeniedController** - Redirects user to the CWL Login page

**ubc_cwl_auth.services** - Registers event subscriber

**ubc_cwl_auth.routing** - Defines ubc_cwl_auth.ubc_cwl_redirect

**ubc_cwl_auth.install** - Conditionaly creates taxonomy vocab and terms and configures them. This needs to happen after the configuration in config/install have been imported. Conditionally creates CWL role.


### Dependencies
**private_files_download_permission** - Allows you to create subdirectories in the private files folder, and set User role permissions on them. Therefore any private files uploaded to that folder will take on the User permission requirements.

**entity_bundle_permissions** - Allows you to create granular access control permissions on Entities, including Content Types and Media. Note, that if you have UBC Media Entity Configuration installed, you will need to set permissions for both modules. You can open up permissions on UBC Media Entity Configuration to Anonymous, and then restrict them in the Entity Bundle Permissions permissions.

**permissions_by_term** - Allows you to use a taxonomy vocabulary to set User role permissions. This creates a preferable Edit form experience over the private content module.

**jsonapi_extras** - Not actually a dependency, but required to see JSON endpoints, for testing access control of content exposed through endpoints.


### Configuration of Uses Cases


#### Single Node CWL Protection
Add a taxonomy reference field to any content type, referencing the Visibility taxonomy. Make the field mandatory and create a default value of 'General'.

Any node with 'General' visibility is accessible to Anonymous and Authenticated users. Any node with 'CWL' visibility is accessible to 'CWL', 'Content Editor', and 'Administrators'. Those specific role permissions can be customized after installation under the 'Permissions' section of the taxonomy term edit page.

#### Content Type CWL Protection
For any content type, configure Access and CRUD permissions by going to the content type's 'Manage Permissions' operation (which is part of the **entity_bundle_permissions** module).

#### Media CWL Protection
For any Media type, configure Access and CRUD permissions by going to the content type's 'Manage Permissions' operation (which is part of the **entity_bundle_permissions** module).

Note: if the **UBC Media Entity Configuration** module is installed, that module's permissions can potentially conflict with the permissions of the **entity_bundle_permissions** module. You can resolved this by providing full access within the **UBC Media Entity Configuration** permissions, and then restricting permissions with the **entity_bundle_permissions** module.

#### Views CWL Protection
For Views, restrict access permissions by the 'CWL' role.

#### Feeds CWL Protection

If Entities have permissions based access control configured, then JSON feeds accessed without authentication should adhere to the access control you've setup.

