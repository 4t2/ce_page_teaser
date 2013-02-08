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
 * @copyright  Lingo4you 2013
 * @author     Mario Müller <http://www.lingolia.com/>
 * @package    PageTeaser
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
 
class PageTeaser extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_page_teaser';
	protected $pageCode = 0;
	protected $pageLink = '';


	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE' && !defined('EX_TL_MODE_FE'))
		{
			$strContent = parent::generate();
			
			if ($this->pageCode < 0)
			{
				$strContent = '<div style="color:#a22;font-weight:bold;">→ '.$this->pageLink.'</div>' . $strContent;
			}
			elseif ($this->pageCode > 0)
			{
				$strContent = '<div style="color:#22a">→ '.$this->pageLink.'</div>' . $strContent;
			}
			else
			{
				$strContent = '<div>→ '.$this->pageLink.'</div>' . $strContent;
			}

			return $strContent;
		}
		else
		{
			if (!empty($GLOBALS['TL_CONFIG']['pageTeaserJsLink']) && !defined('PAGE_TEASER_JS_LINK'))
			{
				$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/ce_page_teaser/assets/scripts/moo_page_teaser.js';

				define('PAGE_TEASER_JS_LINK', 1);
			}

			return parent::generate();
		}
	}


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		if (TL_MODE == 'FE' || defined('EX_TL_MODE_FE'))
		{
			global $objPage;
		}
		else
		{
			$objArticle = $this->Database->prepare("SELECT `pid` FROM `tl_article` WHERE `id`=?")->execute($this->pid);

			if ($objArticle->next())
			{
				$objPage = $this->Database->prepare("SELECT * FROM `tl_page` WHERE `id`=?")->execute($objArticle->pid)->next();
			}	
		}

		$rootPage = $this->getRootPage($objPage->id);
		
		if (TL_MODE == 'BE' && !defined('EX_TL_MODE_FE'))
		{
			$objPage->domain = $rootPage['dns'];
			$objPage->rootLanguage = $rootPage['language'];
		}
		

		$objTargetPage = $this->Database->prepare("
			SELECT
				`id`,
				`alias`,
				`title`,
				`type`
			FROM
				tl_page
			WHERE
				`id`=?")
			->limit(1)
			->execute($this->page_teaser_page);

		$link = '';

		if ($objTargetPage->numRows < 1)
		{
			$this->pageCode = -1;
			$this->pageLink = sprintf($GLOBALS['TL_LANG']['page_teaser']['not_found'], $this->page_teaser_page);
		}
		else
		{
			$targetId = $objTargetPage->id;
	
			if ($targetRoot = $this->getRootPage($objTargetPage->id))
			{
				if ($objPage->domain != $targetRoot['dns'])
				{
					$link = ($this->Environment->ssl ? 'https://' : 'http://') . $targetRoot['dns'] . '/';
				}

				if ($rootPage['id'] != $targetRoot['id'])
				{
					$this->pageCode = 1;
				}
			}
			
			if ($objTargetPage->type != 'root')
			{
				if (version_compare(VERSION, '2.10', '>'))
				{
					$link .= $this->generateFrontendUrl($objTargetPage->row(), null, $targetRoot['language']);
				}
				else
				{
					$link .= $this->generateFrontendUrl($objTargetPage->row());
				}
			}	
		}

		$this->pageLink = $link;

		$this->import('String');

		if (version_compare(VERSION, '2.9', '>'))
		{
			// Clean the RTE output
			if ($objPage->outputFormat == 'xhtml')
			{
				$this->text = $this->String->toXhtml($this->text);
			}
			else
			{
				$this->text = $this->String->toHtml5($this->text);
			}
		}

		// Add the static files URL to images
		if (TL_FILES_URL != '')
		{
			$path = $GLOBALS['TL_CONFIG']['uploadPath'] . '/';
			$this->text = str_replace(' src="' . $path, ' src="' . TL_FILES_URL . $path, $this->text);
		}

		$this->Template->href = $link;
		$this->Template->headline = $this->headline;
		$this->Template->text = $this->text;
		$this->Template->showMore = $this->page_teaser_show_more;		
		$this->Template->readMore = specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objTargetPage->title));
		$this->Template->more = $GLOBALS['TL_LANG']['MSC']['more'];

		$this->Template->addImage = false;

		if (version_compare(VERSION, '3', '>='))
		{
			// Add an image
			if ($this->addImage && $this->singleSRC != '')
			{
				if (!is_numeric($this->singleSRC))
				{
					$this->Template->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
				else
				{
					$objModel = \FilesModel::findByPk($this->singleSRC);
	
					if ($objModel !== null && is_file(TL_ROOT . '/' . $objModel->path))
					{
						$this->singleSRC = $objModel->path;
						$this->addImageToTemplate($this->Template, $this->arrData);
					}
				}
			}
		}
		else
		{
			// Add image
			if ($this->addImage && strlen($this->singleSRC) && is_file(TL_ROOT . '/' . $this->singleSRC))
			{
				$this->addImageToTemplate($this->Template, $this->arrData);
			}
		}
	}


	protected function getRootPage($pageId)
	{
		$type = null;

		// Get all pages up to the root page
		do
		{
			$objPages = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
									   ->limit(1)
									   ->execute($pageId);

			$type = $objPages->type;
			$pageId = $objPages->pid;
		}
		while ($objPages->numRows && $pageId > 0 && $type != 'root');

		if ($type == 'root')
		{
			return $objPages->row();
		}
		else
		{
			return false;
		}
	}

}
