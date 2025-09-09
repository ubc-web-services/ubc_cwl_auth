# CWL Protected Content Permissions Based Upgrade - Test Plan

## Setup

### Domain
Use example.it.ubc.ca master branch when testing with CWL. Use any local project when testing without CWL
### User
Use personal CWL and _**yteststudent**_ (ask Dave for password). For local testing, manually create users are required.
### Method
* Use multiple browsers and/or private windows to keep user sessions separated
* Use admin account to manage your test user roles
### Configuration
A working CWL integration will be setup on example.it.ubc.ca, however all additional configuration to setup CWL protection will be done as part of the test plan.

Any additional modules required will be enabled as a dependency of the custom _**ubc_cwl_auth**_ module.

## Tests for Future Proofing

### Drupal 10

1. Install **ubc_cwl_auth** module, with dependencies, into a Drupal 10 site. Check for:
- all dependent modules have been installed
- the CWL role has been added
- the Visibility taxonomy vocab has been added
- the Visibility taxonomy vocab contains 2 terms called General and CWL
- General has Allowed Roles of Anonymous and Authenticated
- CWL has Allowed Roles of CWL, Content Editor and Administrator

**Dependencies** : Drupal 10 core, samlauth

**Method** : Install with Composer

### Drupal 11

1. Install **ubc_cwl_auth** module, with dependencies, into a Drupal 11 site. Do same checks as for D10.

**Dependencies** : Drupal 11 core, samlauth

**Method** : Install with Composer

## Tests for Expanding CWL Protection
### Single Node

1. Use the Visibility taxonomy term to set View permissions by role on a single node.

**Dependencies** : permission_by_term

**Method** : Add a taxonomy reference field to a content type, which references the Visibility vocab. Set the default value as "General". Anonynous Users should not be able to access this node. After CWL login, you should be able to access it.

### Content type

1. Create a content type with permissions such that the CWL role is required to view published content.

**Dependencies** : entity_bundle_permissions

**Method** : Create a content type and provide **Entity Bundle Permissions** to enable CWL users to access. Anonymous users should not be able to access; instead should see 403 page with message and CWL Login link.


### Views

1. Create a View, and configure Access to be Role: CWL

**Dependencies** : none

**Method** : Create View and test Anonymous and CWL users for access


### Private Files

1. Configure a content type with a Private Files upload field, which requires CWL role.

**Dependencies** : private_files_download_permission

**Method** : Add a File Upload field to a content type. Use the following config options:
* Upload Destination : Private Files
* File Directory : **Set this field, do not leave blank**
* Set Private files download permissions for CWL role for the File Directory you've specified (/admin/config/media/private-files-download-permission)

Create a node with a private file upload, and test Anonymous and CWL role access to the file.

### Feeds

1. Enable JSON API modules and test that you can control access

**Dependencies** : jsonapi, jsonapi_defaults, jsonapi_extras, serialization

**Method** : Enable dependent modules and navigate to /admin/config/services/jsonapi/resource_types to see endpoints. Examine the contents of the node endpoints, and ensure that nodes that have access control requirements as set in previous tests are not available within the feed.

## Tests for Developer Support

1. Verify that values are returned for all expected CWL attributes

**Dependencies** : samlauth

**Method** : As an Admin User:
* go to /admin/config/ubc_cwl_auth/devel
* enter fields from metadata file used for integration
    * For existing integrations, see https://confluence.it.ubc.ca/spaces/IAMS/pages/311746501/UDM+-+Drupal+SAAS
    * For new integrations, locate the metadata file from the integration request ticket
* complete form and verify that values are being returned
    * compare values with those being used in the SimpleSAMLphp Auth Settings (D10): /admin/config/people/simplesamlphp_auth
    * * compare values with those being used in the Samlauth Settings (D10): /admin/config/people/saml

