# WPExpress
A light weight framework for WordPress Developers

Built to help you DRY. WPExpress is a package that helps you achieve the most common taks on WordPress

##Quick Start

WPExpress is designed to be easy to use. Start by reading our [documentation](https://github.com/Page-Carbajal/WPExpress/wiki)

##Framework Structure

* WPExpress
    * Query
    * Abstractions
        * Model
            * BaseModel
            * BaseTaxonomy
        * Settings Page
        * Dashboard Widget
    * Model
        * Post
        * Taxonomy
    * UI
        * RenderEngine
        * HTML/ Tags
            * Textbox
            * Radio
            * Checkbox
            * Select
            * TextArea
            * Code
            * WYSIWYG
            * GoogleMap

##Road Map

* Encapsulate common tasks such as getAll, getByField, getPermalink, getThumbailURL, getAttachments
* Register CPTs and Taxonomies
* Implement dotEnv for configurations
* Provides a set of Abstract classes to build 
    * Custom Post Types / Fields
    * Custom Taxonomies
    * DashboardWidgets



##Changelog

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

