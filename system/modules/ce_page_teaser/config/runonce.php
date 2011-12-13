<?php

class TeaserRunonceJob extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}

	public function run()
	{
		// nur ab Contao 2.9
		if (version_compare(VERSION, '2.8', '>'))
		{
			if ($this->Database->fieldExists('page_teaser_text', 'tl_content'))
			{
				$this->Database->execute("UPDATE `tl_content` SET `text`=`page_teaser_text` WHERE `type`='page_teaser'");
				$this->Database->execute("ALTER TABLE `tl_content` DROP COLUMN `page_teaser_text`");
			}
		}
	}
}

$objTeaserRunonceJob = new TeaserRunonceJob();
$objTeaserRunonceJob->run();

?>