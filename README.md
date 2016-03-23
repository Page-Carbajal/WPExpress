# WPExpress
##WordPress Framework for Software Developers

For all of you my fellow developer which are not familiar with WordPress. I was going to make the mistake of trying to sell you WordPress platform. But I am not anymore. Instead, I'll ask you to do a google search for ***why use wordpress***.

Then do the same for other Content Management Systems, read a little, then decide what's better for you.

I will however tell you why I use WordPress to run my Websites.

**WordPress** powers 25% of the websites on the Internet. This means that 1 of every 4 websites are using it.

* Improved time to market
* Easily create Content Networks
* Thousands of themes free and premium
* Thousands of plugins free and premium
* It is already a great blogging platform
* Great UX
* BuiltIn API
* Runs on apache or nginx
* It runs 1/4 of the Internet's websites
* And it is Open Source


##Why should you use WPExpress?

All parents tend to  brag 'bout their children. To refrain from doing just that I will rather talk to you 'bout why I like using frameworks like Rails, Laravel and Symfony.

They make me love writing code. Very smart people wrote hundreds of thousands of lines of code to implement concepts such as Separation of Concern, Convention over Configuration, DRY and so on.

I love rake, and artisan. I am infatuated with how Symfony is structured as a set of components rather than a Framework. I also have a thing for composer, autoloading and depedency management.

And last but not least. I worship [Mustache](http://mustache.github.io) and [Twig](http://twig.sensiolabs.org)
 
I get to know a world as awesome as this and then I go back to write ASP-like code. Like it is 2001 again. That made me sad –Insert the saddest ever emoticon here :(–.
 

So I wanted to go from this

```php

function renderOutput()
{
<div class="<?php echo $postClass">
<?if( false == $someValue ) : ?>
    <p><?php echo $bigTitle; ?></p>
<?php endif; ?>
</div>
}


```

To this

```php

function renderOutput()
{
    $engine = new RenderEngine('path/to/templates');
    $context = array(
        'postClass' => 'article bit title',
        'bigTitle' => 'This is the big title',
    );
    echo $engine->render( 'mustache-template-file-name', $context );
}

```

I wanted to go from this
 
```php

$args = array();

$query = new WP_Query( $args );

wp_reset_post_data();

```

To this

```php

$posts = Query::Posts->limit(10)->meta('field', 'value')->get();

```

I wanted to use composer, mustache, twig, and every other thing I love in modern PHP within WordPress. But here's the compromise. I do not want to change WordPress. 

I do not want to write my own queries, I do not want to alter the core. I want WordPress to be kept intact. I just want a Framework which will allow me to do more with less.

Less configuration, less plugins, less time to market. Finally I am writing this framework to write meaningful code, and build better websites. 


##Quick Start

This small **WordPress Frmework** is designed to be easy to use. 

Start by reading our [documentation](https://github.com/Page-Carbajal/WPExpress/wiki) and then visit my website for [videos and tutorials on WPExpress](http://pagecarbajal.com/projects/wpexpress/)

##Framework Structure

- Database  [WPExpress\Query](https://github.com/Page-Carbajal/WPExpress-Query)
	- [Query](https://github.com/Page-Carbajal/WPExpress/wiki/Query)
	- Post
	- Taxonomy
	- User
	- UserRole
- Model
	- [BaseModel](https://github.com/Page-Carbajal/WPExpress/wiki/BaseModel)
	- [BaseTaxonomy](https://github.com/Page-Carbajal/WPExpress/wiki/BaseTaxonomy)
	- BaseUser
	- BaseUserRole
	- BaseRelation **May Be**
- Contracts
    - BaseWordPressFilters
- Admin
	- [BaseSettingsPage](https://github.com/Page-Carbajal/WPExpress/wiki/BaseSettingsPage)
	- BaseWidget
	- BaseAPI
- UI [WPExpress\UI](https://github.com/Page-Carbajal/WPExpress-UI)
    - BaseResources
	- [FieldCollection](https://github.com/Page-Carbajal/WPExpress/wiki/FieldCollection)
	- MetaBoxCollection
	- HTMLFieldParser
	- RenderEngine
	- ReactJS


	
###Minerva for WPExpress

I need to build a **Command Line Interface** for this little framework. With some additional effort **[Minerva](https://github.com/Page-Carbajal/Minerva)** will be such **CLI**.

Stay tuned for more information on it.  




