(function($)
{
	window.addEvent('domready', function()
	{
		$$('.ce_teaser, .ce_page_teaser')
			.setStyles({'cursor' : 'pointer'})
			.addEvent('click', function(e)
			{
				if (!this.hasClass('fee_editable'))
				{
					(new URI(this.getElement('a').get('href'))).go();
				}
			});
	});
})(document.id);