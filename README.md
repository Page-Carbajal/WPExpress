# WPExpress
A light weight framework for WordPress Developers


Built to help you DRY. WPExpress is a package that helps you achieve the most common taks on WordPress

##Road Map



* Encapsulate common tasks such as getAll, getByField, getPermalink, getThumbailURL, getAttachments




##Nice to Have

* Register CPTs and Taxonomies
* Implement dotEnv for configurations
* Implements Mustache to keep your code clean and readable
* Provides a set of Abstract classes to build 
    * Admin Pages
    * Custom Post Types / Fields
    * Custom Taxonomies
    * DashboardWidgets
* Implement Twig to further improve templating and coding experience // Mustache will still be supported

***Basic PSR-4 Structure***

* WPExpress
    * Query
    * Abstractions
        * Model
            * BaseModel
            * BaseTaxonomy
        * Admin Page
        * Dashboard Widget
    * Model
        * Post
        * Taxonomy
    * UI
        * Templates


##Changelog##

***Version 0.2(Current)***

* Created Abstract class /Model/Abstractions/BaseModel
* Created the Interface iBaseModel
* Created the class Model/Post implements BaseModel and iBaseModel

***Version 0.1***

* Created empty repository
* Defined basic PSR-4 structure
* Added Road Map and Changelog
* Created the class Query. An abstraction layer for the class WP_Query
