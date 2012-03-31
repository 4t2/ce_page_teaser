window.addEvent('domready', function() {

	$$('.ce_page_teaser').each(setTeaserLink);
	$$('.ce_teaser').each(setTeaserLink);

	function setTeaserLink(element, index)
	{
		element.setStyles({'cursor' : 'pointer'});

		element.addEvent('click', function(e)
		{
			(new URI(this.getElement('a').get('href'))).go();
		});
	}

});