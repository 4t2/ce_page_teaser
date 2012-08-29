<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Ce_page_teaser
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'PageTeaser'        => 'system/modules/ce_page_teaser/PageTeaser.php',
	'ArticlePageTeaser' => 'system/modules/ce_page_teaser/ArticlePageTeaser.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'ce_page_teaser' => 'system/modules/ce_page_teaser/templates',
));
