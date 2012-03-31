<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Lingo4you 2011
 * @author     Mario Müller <http://www.lingo4u.de/>
 * @package    PageTeaser
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


$GLOBALS['TL_DCA']['tl_content']['palettes']['teaser'] = str_replace('{include_legend},article', '{include_legend},article,teaser_page_link,teaser_fragment_identifier', $GLOBALS['TL_DCA']['tl_content']['palettes']['teaser']);

$GLOBALS['TL_DCA']['tl_content']['palettes']['page_teaser'] = '{type_legend},type,headline;{page_teaser_text_legend},text;{page_teaser_page_legend},page_teaser_page,page_teaser_show_more;{image_legend},addImage;{expert_legend:hide},cssID,space';



#$GLOBALS['TL_DCA']['tl_content']['fields']['article']['eval']['tl_class'] = 'w50';

$GLOBALS['TL_DCA']['tl_content']['fields']['teaser_page_link'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_content']['teaser_page_link'],
	'exclude'		=> true,
	'inputType'		=> 'checkbox',
	'eval'			=> array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['teaser_fragment_identifier'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_content']['teaser_fragment_identifier'],
	'exclude'		=> true,
	'inputType'		=> 'checkbox',
	'eval'			=> array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['page_teaser_page'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['page_teaser_page'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'eval'                    => array(
		'mandatory' => true,
		'fieldType'=>'radio'
	)
);

$GLOBALS['TL_DCA']['tl_content']['fields']['page_teaser_show_more'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_content']['page_teaser_show_more'],
	'exclude'		=> true,
	'inputType'		=> 'checkbox',
	'eval'			=> array('tl_class'=>'w50')
);
