# WPExpress
A light weight framework for WordPress Developers

Built to help you DRY. WPExpress is a package that helps you achieve the most common taks on WordPress

##Road Map

* Encapsulate common tasks such as getAll, getByField, getPermalink, getThumbailURL, getAttachments

###Framework Structure

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

###Custom Post Types Decalaration

```php

final class Service extends Post    
{

    public function __construct($bean){
    
        $this->postType = sanitize_title( _x( 'service' ,'Post Type Service Declaration', 'text-domain') );
        
        $this->postSlug = sanitize_title( _x( 'service' ,'Service Slug Declaration', 'text-domain') ); // Uses postType if not declared
        
        $this->addField( 
            'name' => __('price', 'textdomain'), 
            'label' => __('Price', 'textdomain'), 
            'type' => 'number',
            'placeHolder' => '0.00');

        $this->addField( 
            'name' => __('schedules', 'textdomain'), 
            'label' => __('Schedules', 'textdomain'), 
            'type' => 'textarea',
            'placeHolder' => '8:00, 9:00, 10:00',
            'transform' => 'listFromCSV' );
            
        parent::__construct($bean);
    }
}

// Usage

add_filter('init', 'myInitHook');

function myInitHook(){
    new Service();
}

// Prebuilt Basic CRUD and Traversing

function someOtherFunction(){

    $allServices = Service::getAll();
    foreach( $allServices as $service){
        // TODO: Do something pretty
        $service->getField('price')->format('currency');
    }
    
    $someServices = Service::getByField('price', 200); // All services that have a price of 200
    $someServices = Service::getByTaxonomy('taxonomy', 'term'); // Get all services of the given Taxonomy

    // Get one service by ID
    $oneService = new Service($id);
    // Default methods
    $oneService->getThumbnail();
    $oneService->getThumbnailURL();
    $oneService->getContent(); 
    $oneService->getTitle();
    // Update a field and Save
    $oneService->set('price', 300)->save();
    // Delete
    $oneService()->delete()
    // Create a new Service
    $newService = new Service();
    $newService->set('price', 300);
    $newService->set('post_title', 'New Service');
    $newService->save();
    
}
```

##Changelog

***Version 0.2(Current)***

* Created Abstract class /Model/Abstractions/BaseModel
* Created the Interface iBaseModel
* Created the class Model/Post implements BaseModel and iBaseModel

***Version 0.1***

* Created empty repository
* Defined basic PSR-4 structure
* Added Road Map and Changelog
* Created the class Query. An abstraction layer for the class WP_Query


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
