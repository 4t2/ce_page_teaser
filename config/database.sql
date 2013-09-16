-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- --------------------------------------------------------

-- 
-- Table `tl_content`
-- 

CREATE TABLE `tl_content` (
  `teaser_page_link` char(1) NOT NULL default '',
  `teaser_fragment_identifier` char(1) NOT NULL default '',
  `page_teaser_page` int(10) unsigned NOT NULL default '0',
  `page_teaser_show_more` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
