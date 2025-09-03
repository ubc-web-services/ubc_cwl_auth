# UBC CWL AUTH

### Files
Event Subscriber - Subscribes to Exceptions, but only implements logic on 403. Checks for 'CWL' role and redirects accordingly.

AccessDeniedController - Redirects user to the CWL Login page

ubc_cwl_auth.services - Registers event subscriber

ubc_cwl_auth.routing - Defines ubc_cwl_auth.ubc_cwl_redirect

ubc_cwl_auth.install - Creates taxonomy terms and configures them. This needs to happen after the configuration in config/install have been imported
