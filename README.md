# UBC CWL AUTH

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
