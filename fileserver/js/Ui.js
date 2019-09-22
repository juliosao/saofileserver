class UI
{
    static clear(element)
    {
        while(element.firstChild)
        {
            element.removeChild(element.firstChild);
        }
    }
}