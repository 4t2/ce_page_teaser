window.onDomReady = initReady;

// Initialize event depending on browser
function initReady(fn)
{
	//W3C-compliant browser
	if (document.addEventListener)
	{
		document.addEventListener('DOMContentLoaded', fn, false);
	}
	else // IE
	{
		document.onreadystatechange = function() {readyState(fn)}
	}
}

// IE execute function
function readyState(func)
{
	// DOM is ready
	if (document.readyState == "interactive" || document.readyState == "complete")
	{
		func();
	}
}

window.onDomReady(function()
{
	if (document.body.addEventListener)
	{
		document.body.addEventListener('click', pageTeaserClickHandler, false);
	}
	else
	{
		document.body.attachEvent('onclick', pageTeaserClickHandler);
	}
});


function pageTeaserClickHandler(e)
{
    e = e || window.event;
    var el = e.target || e.srcElement;

	while (el != null && !el.className.match(/\bce_page_teaser\b/) && !el.className.match(/\bce_teaser\b/))
	{
		el = el.parentElement;
	}

	if (el != null && !el.className.match(/\bfee_editable\b/) && el.children.length)
	{
		if ((link = getPageLink(el)) != '')
		{
			window.location = link;
		}
    }
}

function getPageLink(el)
{
    var children = el.children;
	
    for (var i = 0; i < children.length; i++)
    {
        if (children[i].tagName.toLowerCase() == 'a' && children[i].href != '')
        {
            return children[i].href;
        }
		else if (children[i].children.length)
		{
			if ((link = getPageLink(children[i])) != '')
			{
				return link;
			}
		}
    }
	
	return '';
}
