# WPExpress - A WordPress Framework for Software Developers

For all of you my fellow developers who are not familiar with WordPress. 

Instead of trying to sell you into WordPress I'll ask you to do a google search for ***[why wordpress](https://www.google.com.mx/?gws_rd=ssl#q=why+wordpress)***.

Then do the same for other Content Management Systems, read a little, then decide what's better for you.

I will however tell you why I use WordPress to run my Websites.


**WordPress** powers +25% of the websites on the Internet. This means that 1 of every 4 websites are using it.

Some of the bennefits it gives me:

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


## What is WPExpress

I like using frameworks like Rails, Laravel and Symfony.

They make me love writing code. 

Very smart people wrote hundreds of thousands of lines of code to implement concepts such as Separation of Concern, Convention over Configuration and so on.

I love rake, and artisan. I am infatuated with how [Symfony](https://github.com/symfony/symfony) is structured as a set of components rather than a full fledged Framework. 

And I also have a thing for composer, autoloading and depedency management.

Last but not least. I worship [Mustache](http://mustache.github.io) and [Twig](http://twig.sensiolabs.org)
 
When you get to know and work in environments as cool as those I have talked about, going back to monolite-style code writing is, HARD.

I found myself having to write similar code across different projects and I decided I wanted to focus on writing meaningful code and abstract the repetitive stuff as much as possible. 
 

**I wanted to go from this**
 
```php

$args = array();

$query = new WP_Query( $args );

wp_reset_post_data();

```

**To this**

```php

$posts = Query::Posts->limit(10)->meta('field', 'value')->get();

```

I wanted to use composer, abstraction and chainable methods, and every other thing I love in modern PHP within WordPress. But here's the compromise. I do not want to change WordPress. 

I do not want to write my own queries, I do not want to alter the core. I want WordPress to be kept intact. I just want a Framework which will allow me to do more with less.

Less configuration, less plugins, less time to market. 

Finally I am writing this framework to allo my self to focus on writing **meaningful code**. 



## Quick Start

This small **WordPress Frmework** is designed to be easy to use. 

Start by reading our [documentation](https://github.com/Page-Carbajal/WPExpress/wiki).

## Framework Structure

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
    - BaseWordPressFilters (Maybe TBD)
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
	- ReactJS (TBD)



