Acorn PHP Framework
==================

A light PHP template, drawing ideas from MVC systems like Kohana and CakePHP.

However, it makes use of a fourth kind of component - the Renderer - to break
up sections of view code, and to encourage the separation of logic, data, and
output.

Using Acorn
-----------

Acorn is designed to be deployed outside of the document root of your webserver,
with the intent of reducing the possibly script vulnerabilities.

If we consider an application called 'example', the reconneded folder structure is
roughly
```
	- /non/public/path
		- example/
			- site.php
			- ...
		- acorn/
	- /srv/www
		- blog.php
```
Where /non/public/path is the path to some location that will never be directly
served, and blog.php would contain the code

```php
<?php
        define('PROJECT_PATH', '/var/acorn-projects/mangler/');

	require('/var/acorn/bootstrap.php');

```

Is This Code Mature?
--------------------

Not Quite

Is That All?
------------

Yes. I'll finish the ReadMe once the code is mature.

