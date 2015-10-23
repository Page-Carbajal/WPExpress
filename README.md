# WPExpress
A light weight framework for WordPress Developers

Built to help you DRY. WPExpress is a package that helps you achieve the most common taks on WordPress

##Road Map

* Encapsulate common tasks such as getAll, getByField, getPermalink, getThumbailURL, getAttachments
* Register CPTs and Taxonomies
* Implement dotEnv for configurations
* Implements Mustache to keep your code clean and readable
* Provides a set of Abstract classes to build 
    * Custom Post Types / Fields
    * Custom Taxonomies
    * DashboardWidgets
* Implement Twig to improve the template and coding experience // Mustache will still be supported


###Framework Structure

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
            * TextArea
            * Select
            * Radio
            * Checkbox
            * WYSIWYG
            * GoogleMap
            * Code

###Query an Object Oriented Abstraction Layer for WP_Query

```php

// Usage Examples

use WPExpress\Query;
// Get all posts
$allPosts = Query::Post()->all()->get();

// Get N number of posts. For this case N = 10
// Note: Sticky posts will be prepend to the query as per WordPress standards
// Note: If limit is not set defaults to 10 using ***showposts*** parameter
$tenPosts = Query::Post()->limit(10)->get();

// Query posts by meta value
$somePosts = Query::Post()->meta('fieldName', 'value')->all()->get();
// Value can also be an array
$somePosts = Query::Post()->meta('fieldName', array( 'value', 'value2' ) )->all()->get();
// Exclude using operator not
$somePosts = Query::Post()->meta('fieldName', array( 'value', 'value2' ), 'not' )->all()->get();


```

###Custom Post Types Decalaration

```php

final class Service extends Post    
{

    public function __construct($bean){
    
        
        // Uses WordPress _x function to generate the translations. You can use __ instead
        $translatedPostType = _x( 'service' ,'Post Type Service Declaration', 'text-domain');
        $this->postType = sanitize_title( $translatedPostType );
        $this->postSlug = sanitize_title( _x( 'service' ,'Service Slug Declaration', 'text-domain') ); // Uses postType value by default
        
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

### Settings Page Abstraction
```php

use WPExpress\Abstractions\SettingsPage;
use WPExpress\UI\HTML\Tags;


class PluginPage extends SettingsPage
{

    public function __construct()
    {
        // Invoke the paren constructor
        parent::__construct( 'Performance Enhancements', 'manage_options' );
        $this->registerPage();

        // Add fields
        $this->addOptionField( Tags::textField('test', array( 'id' => 'test', 'class' => 'form-control' ) )  )
            ->addOptionField(  Tags::textField('test', array( 'id' => 'test', 'class' => 'form-control' ) )  );

    }

}

```

##Changelog

###Version 0.3

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

