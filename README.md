# WPExpress - A WordPress Framework for Developers

A light weight framework for WordPress Developers

Built to help you DRY. WPExpress is being developed to expedite the repetitive work, giving you the chance to work in code that matters in your WordPress projects.

##Quick Start

WPExpress is designed to be easy to use. Start by reading our [documentation](https://github.com/Page-Carbajal/WPExpress/wiki)

##Framework Structure

- Database  [WPExpress/Query](https://github.com/Page-Carbajal/WPExpress-Query)
	- Query
	- Post
	- Taxonomy
	- MetaField
	- User **TBD**
	- UserRole **TBD**
	- Transient **TBD**
- Model
	- BaseModel
	- BaseTaxonomy
	- BaseUser **TBD**
	- BaseUserRole **TBD**
	- BaseRelation **TBD**
- Admin
	- BaseSettingsPage
	- Metabox **TBD**
	- Widget **TBD**
- API **TBD**
	- BaseAPI
- UI [WPExpress/UI](https://github.com/Page-Carbajal/WPExpress-UI)
    - BaseResources
	- RenderEngine
	- FieldCollection
	- HTMLFieldParser
	- ReactJS **TBD**
	
##RoadMap

###Anouncing Minerva for WPExpress

To be serious about this framework, I need to build a **Command Line Interface**. **[Minerva](https://github.com/Page-Carbajal/Minerva)** will be such **CLI**

###WPExpress 1.3

The stable release of the Framework with al of its members finished and working 100%


##Changelog

###TODO

- Add icons support for BaseModel
- BaseSettingsPage/AutoSetValues for Fields
    - Create the public method  BaseSettingsPage/setFieldValue to use the field key to set the field value
    - Add apply_filters method to FieldCollection/addNewField to allow setting values with a filter

##Current Track



###Version 1.2.5 - BaseSettingsPage Beautification

- Changed the methods update_site_option and get_site_option to update_option and get_option
- Allow empty values on BaseSettings/save
- Documentation update
- Removed the property BaseSettingsPage/settingsPageHeading
- Simplified the BaseSettingsPage constructor
- Renamed the property BaseSettingsPage/capabilities to BaseSettingsPage/userCapabilities 


###Version 1.2.4 - WPExpress/UI 1.0.2

- Re-tagged
- Upgrade composer dependency to WPExpress/UI version 1.0.2
- Changed composer to require wpexpress-ui 1.0.1 
- Fixed errors with HTMLParser and added a toArray method to FieldCollection 

###Version 1.2.3 - WPExpress/UI 1.0.0

- Upgraded to WPExpress/UI 1.0.0

###Version 1.2.2 - Fixed BaseSettingsPage Template Path Error

- Fixed the template path verification error


###Version 1.2.1 - BaseModel simplification

- Made the parameter $bean optional on BaseModel class


###Version 1.2.0 - Dropped BaseModelInterface

- Added the method getPostType to BaseModel
- Deleted the BaseModelInterface class in favor of Convention over Configuration


###Version 1.1.0

- Simplified the classes Page and Post
- Added constructor function to make it easier for folks to get started with the project
- Removed the datatype from function declaration on BaseModel/setPublic


###Version 1.0.0

- Made Post class final
- Made Post class extend BaseModel 
- Added empty abstract class Admin/MetaBox class
- Added empty abstract class API/BaseApi 
- Added empty abstract classes BaseUser and BaseUserRole 
- Added BaseModel, BaseModelInterface and BaseTaxonomy abstract classes
- Finished the first iteration on the project structure
- Removed Abstractions, Model and Interfaces folder from project structure


##Version Zero-Six

This version is maintained for compatibility purposes only. 


###Version 0.6.1 - Updated Query version

- Updated WPExpress Query version

###Version 0.6.0 - New models

- Implementing BaseTaxonomy
- Updated WPExpress/Query dependency 
- Implemented basic methods for BaseTaxonomy
- Added empty Sample classes Post and Taxonomy
- Added empty BaseTaxonomy class
- Added empty BasePostModel class


###Version 0.5.4 - Updated WPExpress/UI version

- Updated WPExpress to 0.5.2

###Version 0.5.3 - Fixes SettingsPage Render Error

- Throw exception if template file not found
- Implemented fail-safe to render dedault settings-page template on error
- Updated WPExpress/UI to 0.5.1
- Added vendor directory to .gitignore 


###Version 0.5.2

- Applied code styling to BaseModel class
- Added property BaseModel/menuPosition accessible with magic methods
- Fixed the default menu position to 20


###Version 0.5.1

- Moved to require PHP 5.6 or higher
- Updated the version of WPExpress UI
- Removed the vendor folder from the repository
- Fixed the BaseModel/getSupportedFeatures error
- Adopted [Semantic Versioning](http://semver.org)

###Version 0.5 - Separation of Packages UI and Query

- Both packages are included through composer
- Created the package [WPExpress/UI](https://github.com/Page-Carbajal/WPExpress-UI)
- Created the package [WPExpress/Query](https://github.com/Page-Carbajal/WPExpress-Query)

###Version 0.4 - Twig and RenderEngine enhancements

- Implemented RenderEngine changes to SettingsPage class
- Modified the constructor for RenderEngine 
- Added RenderEngine getTemplatePath public method
- Added RenderEngine createDirectoryStructure private method
- Finished the renderTwigTemplate method
- Render Engine can now use Twig or Mustache
- Required Twig with Composer


###Version 0.3.2 - BaseModel Enhancements

- Tagged to 0.3.2
- Updated documentation
- Moved methods getThumbnail and getThumbnailURL to BaseModel
    - Both methods check for thumbnailSupport, else return false
- Added empty BaseModel setPostTypeLabels
- Implemented BaseModel getPostTypeLabels
- Implemented BaseModel setSupportedFeatures and getSupportedFeatures (Title, Editor, Thumbnail)
- Implemented BaseModel getByTerm
- Implemented BaseModel getByField
- Implemented BaseModel getAll
- Implemented magic methods __set and __get in the base model
- Implemented BaseModel registerCustomPostType method

###Version 0.3.1

- Implemented the term() method to search by taxonomy/value
- Array values are transformed to a csv string in the meta method
- Added the selectField method to Tags class
- Edited the settings page template
- Added the getValue method to SettingsPage class

###Version 0.3

* Refactoring of field management // Needs more work
* Added save method to the SettingsPage class
* Added Support for Array Fields
* Added support for custom template path in SettingsPage
* Added filter to support custom context
* Refactoring of the template properties
* Added checkboxField and radioButtonField methods
* Finished Functionality to render settings page
* Added empty UI/HTML/Tags class 
* Rendering empty settings page
* Added RenderEngine
* Added empty settings page
* Added resources directory
* Added SettingPage abstract class
* Added Mustache with composer
* Added UI Class

###Version 0.2.2

* Added the empty methods registerCustomPostType and addCustomField to BaseModel
* Added the getField method to BaseModel
* Added the property postTypeSlug to BaseModel
* Added the basic Constructor for class Post
* Added empty sort and term methods to Query
* Added methods getThumbnail and getThumbnailURL to Post class
* Implemented static methods getAll, getByField, getByTaxonomy on BaseMode

###Version 0.2.1

* Implements basic methods for class Query

###Version 0.2

* Created Abstract class /Model/Abstractions/BaseModel
* Created the Interface iBaseModel
* Created the class Model/Post implements BaseModel and iBaseModel

###Version 0.1

* Created empty repository
* Defined basic PSR-4 structure
* Added Road Map and Changelog
* Created the class Query. An abstraction layer for the class WP_Query

