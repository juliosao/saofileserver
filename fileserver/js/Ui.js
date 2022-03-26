class UI
{
    static clear(element)
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

    static option(label,value)
    {
        if(typeof value == "undefined")
            value = label;

        let res = document.createElement('option');
        res.value = value;
        res.appendChild(document.createTextNode(label));
        return res;
    }

    static image(src)
    {
        let res = document.createElement('img');
        res.src = src;
        return res;
    }

    static cell(content,th=false)
    {
        let res = document.createElement( th ? 'th' : 'td' );

        if(typeof content == 'object')
            res.appendChild(content);
        else if (typeof content != 'undefined')
            res.appendChild(document.createTextNode(""+content));
        
        return res;
    }

    static button(label,onclick=null)
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
}