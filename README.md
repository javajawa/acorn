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

If we consider an application called 'blog', the reconneded folder structure is
roughly

 - /non/public/path
  - blog
  - acorn

 - /srv/www
  - blog.php

Where /non/public/path is the path to some location that will never be directly
served, and blog.php would contain the code

{{{
<?php
	define('PROJECT_NAME', 'blog');
	reuiqre('/non/public/path/acorn/bootstrap.php');

}}}

Is This Code Mature?
--------------------

Not Quite

Is That All?
------------

Yes. I'll finish the ReadMe once the code is mature.

