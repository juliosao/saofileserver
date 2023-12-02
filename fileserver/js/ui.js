
export function clear(element)
{
	if(typeof element === 'string')
	{
		element = document.getElementById(element);
		if(element==null)
			throw "Elment not found";                
	}
	while(element.firstChild)
	{
		element.removeChild(element.firstChild);
	}
	return element;
}

export function option(label,value)
{
	if(typeof value == "undefined")
		value = label;

	let res = document.createElement('option');
	res.value = value;
	res.appendChild(document.createTextNode(label));
	return res;
}

export function image(src)
{
	let res = document.createElement('img');
	res.src = src;
	return res;
}

export function cell(content,th=false)
{
	let res = document.createElement( th ? 'th' : 'td' );

	if(typeof content == 'object')
		res.appendChild(content);
	else if (typeof content != 'undefined')
		res.appendChild(document.createTextNode(""+content));
	
	return res;
}

export function button(label,onclick=null)
{
	let res = document.createElement('button');

	if(typeof label == 'object')
	{
		res.appendChild(label);
	}
	else if (typeof label != 'undefined')
	{
		res.appendChild(document.createTextNode(""+label));
	}

	if(onclick !== null)
		res.onclick = onclick;

	return res;
}