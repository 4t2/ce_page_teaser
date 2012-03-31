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
* @copyright  Lingo4you 2012
* @author     Mario Müller <http://www.lingo4u.de/>
* @package    PageTeaser
* @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/**
 * System configuration
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['pageTeaserJsLink'] = array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['pageTeaserJsLink'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		);

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = preg_replace('|(\{frontend_legend\}[^;]+)|im', '$1,pageTeaserJsLink', $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']);
