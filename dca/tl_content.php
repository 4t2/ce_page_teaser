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
 * @copyright  Lingo4you
 * @author     Mario Müller <http://www.lingolia.com/>
 * @package    PageTeaser
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


$GLOBALS['TL_DCA']['tl_content']['palettes']['teaser'] = str_replace('{include_legend},article', '{include_legend},article,teaser_page_link,teaser_fragment_identifier', $GLOBALS['TL_DCA']['tl_content']['palettes']['teaser']);

$GLOBALS['TL_DCA']['tl_content']['palettes']['page_teaser'] = '{type_legend},type,headline;{page_teaser_text_legend},text;{page_teaser_page_legend},page_teaser_page,page_teaser_show_more;{image_legend},addImage;{expert_legend:hide},cssID,space;{invisible_legend:hide},invisible,start,stop';

#$GLOBALS['TL_DCA']['tl_content']['config']['oncopy_callback'][] = array('page_teaser', 'copyTeaser');
$GLOBALS['TL_DCA']['tl_page']['config']['oncopy_callback'][] = array('page_teaser', 'copyPage');


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


/**
 * Class page_teaser
 */
class page_teaser extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Change the target link if copy a site with subsites
	 */
	public function copyPage($insertID, $dc)
	{
		$arrPages = array(
			array_merge(array($dc->id), $this->getChildPages($dc->id)),
			array_merge(array($insertID), $this->getChildPages($insertID))
		);

		if (count($arrPages[0]) == count($arrPages[1]))
		{
			$arrPageMap = array();
			
			for ($i=0; $i<count($arrPages[0]); $i++)
			{
				$arrPageMap[$arrPages[0][$i]] = $arrPages[1][$i];
			}

			for ($i=0; $i<count($arrPages[0]); $i++)
			{
				$arrArticles = array(
					$this->Database->prepare("SELECT `id` FROM `tl_article` WHERE `pid`=? ORDER BY `sorting`")->execute($arrPages[0][$i])->fetchAllAssoc(),
					$this->Database->prepare("SELECT `id` FROM `tl_article` WHERE `pid`=? ORDER BY `sorting`")->execute($arrPages[1][$i])->fetchAllAssoc()
				);
				
				if (count($arrArticles[0]) && count($arrArticles[0]) == count($arrArticles[1]))
				{
					for ($t=0; $t<count($arrArticles[0]); $t++)
					{
						$arrPageTeaser = array(
							$this->Database->prepare("SELECT `id`, `page_teaser_page` FROM `tl_content` WHERE `type`='page_teaser' AND `pid`=? ORDER BY `sorting`")->execute($arrArticles[0][$t])->fetchAllAssoc(),
							$this->Database->prepare("SELECT `id` FROM `tl_content` WHERE `type`='page_teaser' AND `pid`=? ORDER BY `sorting`")->execute($arrArticles[1][$t])->fetchAllAssoc()
						);
						
						if (count($arrPageTeaser[0]) && count($arrPageTeaser[0]) == count($arrPageTeaser[1]))
						{
							for ($p=0; $p<count($arrPageTeaser[0]); $p++)
							{
								if (isset($arrPageMap[$arrPageTeaser[0][$p]['page_teaser_page']]))
								{
									$this->Database->prepare("UPDATE `tl_content` SET `page_teaser_page`=? WHERE `id`=?")->execute
									(
										$arrPageMap[$arrPageTeaser[0][$p]['page_teaser_page']],
										$arrPageTeaser[1][$p]['id']
									);
									$this->log('Map page '.$arrPageTeaser[0][$p]['page_teaser_page'].' to '.$arrPageMap[$arrPageTeaser[0][$p]['page_teaser_page']], 'PageTeaser', TL_GENERAL);
								}
							}
						}
					}
				}
			}
		}
		else
		{
			$this->log('Copy error '.count($arrPages[0]).' :: '.count($arrPages[1]), 'PageTeaser', TL_GENERAL);
		}
	}
	
	protected function getChildPages($pageId)
	{
		$pageArray = array();

		$objPages = $this->Database->prepare("SELECT `id` FROM `tl_page` WHERE `pid`=? ORDER BY `sorting`")->execute($pageId);

		while ($objPages->next())
		{
			$pageArray[] = $objPages->id;			
			$pageArray = array_merge($pageArray, $this->getChildPages($objPages->id));
		}

		return $pageArray;
	}

}