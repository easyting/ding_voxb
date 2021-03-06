VoxB module
==========

### Version 1.3:

* New: Added rating and reviews on searchResult and collectionView.

### Version 1.2:

* New: Replaced the SOAP client with [NanoSOAP] [nanosoap].
* New: Integrated the possibility of logging requests ([ding_devel]) [ding_devel].
* New: Implemented the module through [ding_entity] [ding_entity].
* New: New rating images.
* Enhancement: Refactored CSS class names to not use camelCase.
* Enhancement: Refactored the JavaScript to the behavior of drupal.js.
* Enhancement: Major template changes - sending HTML through AJAX.

### Version 1.1:

* Fix: Various minor styling, AJAX, commenting and JS issues.
* Fix: Adjusted the module due to [changes] [ddbcae5eb2c7c7417089] in [ding2/ting] [ting].

### Version 1.0:

* Hotfixes.

### Version 0.3:

* New: Support update of posts (edit rating, add more tags/comments) with [updateMyRequest] [updateMyRequest].
* Enhancement: [JavaScript should be compatible with other libraries than jQuery] [js_compatibility].
* Enhancement: ~~[Use drupal_set_session instead of $_SESSION] [drupal_set_session]~~.

### Version 0.2:

* New: Added unit tests.
* Enhancement: Upgraded AJAX to use Drupal 7 AJAX commands.
* Enhancement: Refactored login class to use [ding_user] [ding_user].
* Enhancement: Updated module to use [ding_popup] [ding_popup] (jquery.dialog from core).

### Version 0.1:

* Init

[ding_user]: https://github.com/ding2/ding_user
[ding_popup]: https://github.com/ding2/ding_popup
[js_compatibility]: http://drupal.org/node/224333#javascript_compatibility
[drupal_set_session]: http://drupal.org/node/224333#drupal_set_session
[updateMyRequest]: https://voxb.addi.dk/1.0/doc/voxb.html#Link51 
[nanosoap]: http://drupal.org/project/nanosoap
[ding_devel]: https://github.com/ding2/ding_devel
[ding_entity]: https://github.com/ding2/ding_entity
