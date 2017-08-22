function extractPageName(hrefString)
{
	var arr = hrefString.split('/');
	return  (arr.length<2) ? hrefString : arr[arr.length-2].toLowerCase() + arr[arr.length-1].toLowerCase();               
}

function setActiveMenu(arr, crtPage)
{
	for (var i=0; i<arr.length; i++)
	{
		if(extractPageName(arr[i].href) == crtPage)
		{
			if (arr[i].parentNode.tagName != "DIV")
			{
				arr[i].className = "current";
				arr[i].parentNode.className = "current";
			}
		}
	}
}

function setPage()
{
	hrefString = document.location.href ? document.location.href : document.location;

	if (document.getElementById("navigation")!=null)
		setActiveMenu(document.getElementById("navigation").getElementsByTagName("a"), extractPageName(hrefString));
}
function setPageFeatures()
{
	hrefString = document.location.href ? document.location.href : document.location;

	if (document.getElementById("features-nav")!=null)
		setActiveMenu(document.getElementById("features-nav").getElementsByTagName("a"), extractPageName(hrefString));
}