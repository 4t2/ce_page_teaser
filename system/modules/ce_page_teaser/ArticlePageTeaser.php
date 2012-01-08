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

class ArticlePageTeaser extends ContentTeaser
{

	/**
	 * Generate content element
	 */
	protected function compile()
	{
		global $objPage;

		if ($this->teaser_page_link)
		{
			$objArticle = $this->Database->prepare("
				SELECT
					p.id AS id,
					p.alias AS alias,
					a.id AS aid,
					a.title AS title,
					a.alias AS aalias,
					a.teaser AS teaser,
					a.inColumn AS inColumn,
					a.cssID AS cssID
				FROM
					tl_article a,
					tl_page p
				WHERE
					a.id=?
					AND a.pid=p.id
			")
				 ->limit(1)
				 ->execute($this->article);

			if ($objArticle->numRows < 1)
			{
				return;
			}
			
			$link = $this->generateFrontendUrl($objArticle->row());

			$this->import('String');

			if (version_compare(VERSION, '2.9', '>'))
			{
				// Clean the RTE output
				if ($objPage->outputFormat == 'xhtml')
				{
					$articleTeaser = $this->String->toXhtml($objArticle->teaser);
				}
				else
				{
					$articleTeaser = $this->String->toHtml5($objArticle->teaser);
				}
			}
			else
			{
				$articleTeaser = $objArticle->teaser;
			}

			if ($this->teaser_fragment_identifier)
			{
				$cssID = deserialize($objArticle->cssID, true);
			
				if (strlen($cssID[0]))
				{
					$link .= '#' . $cssID[0];
				}
				else
				{
					$link .= '#' . $objArticle->aalias;
				}
			}
	
			$this->Template->href = $link;
			$this->Template->headline = $objArticle->title;
			$this->Template->text = $articleTeaser;
			$this->Template->readMore = specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objArticle->title));
			$this->Template->more = $GLOBALS['TL_LANG']['MSC']['more'];
		}
		else
		{
			parent::compile();
		}
	}
}

?>