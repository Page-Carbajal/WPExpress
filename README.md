# WPExpress
A light weight framework for WordPress Developers


Built to help you DRY. WPExpress is a package that helps you achieve the most common taks on WordPress

##Road Map



* Encapsulate common tasks such as getAll, getByField, getPermalink, getThumbailURL, getAttachments




##Nice to Have

* Register CPTs and Taxonomies
* Provides a set of Abstract classes to build Settings Pages and Metaboxes
* Implements Mustache to keep your code clean and readable
* Implement dotEnv for configurations

***Basic PSR-4 Structure***

  WPExpress
  
  WPExpress/Query
  
  WPExpress/SettingsPage
  
  WPExpress/Model/Post
  
  WPExpress/Model/Page
  
  WPExpress/UI/Page
  
  WPExpress/UI/Metaboxes

Verion 0.2 
Implement Twig


##Changelog##

***Version 0.1***

* Created empty repository
* Defined basic PSR-4 structure
* Added Road Map and Changelog
* Created the class Query. An abstraction layer for the class WP_Query

***Version 0.2(Current)***

* Created Abstract class /Model/Abstractions/BaseModel
* Created the Interface iBaseModel
* Created the class Model/Post implements BaseModel and iBaseModel

***Version 0.3***
