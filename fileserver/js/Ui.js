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
}