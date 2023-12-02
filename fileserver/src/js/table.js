export class table
{
    /**
     * @param {*} config: A object with the configurator for the table:
     * - classList (Optional): An array with the classList for the table
     * - fields: An asociative array with the configurations of the columns where:
     *  - label (Optional): Label of the column
     *  - src (Optional): An object with the contents of the field or a function returning that object.
     *              if src is a function, a parameter with the original data will be passed as first parameter.
     */
    constructor(config)
    {
        this.config=config;

        this.table = document.createElement('table');
        this.thead = document.createElement('thead');
        this.table.appendChild(this.thead);

        let tr = document.createElement('tr');
        for(const f in this.config.fields)
        {
            let cell = document.createElement('th');
            cell.appendChild('label' in config.fields[f] ? config.fields[f]['label'] : f)
        }

        this.thead.appendChild(tr);
        this.tbody = document.createElement('tbody');
        this.table.appendChild(this.tbody);

        if('classList' in this.config)
            this.table.classList.add(this.config.classList)
    }

    setData(data)
    {
        while(this.tbody.firstChild)
            this.tbody.firstChild.remove();
        
        for(const r of data)
        {
            for(const f in this.config.fields)
            {
                let cell = document.createElement('td');

                let tmp = r[f];
                switch(typeof config.fields[f]['source'])
                {
                    case 'function':
                        tmp = config.fields[f]['source'](r);
                        break;
                    case 'object':
                        tmp = config.fields[f]['source'];
                        break;                    
                }

                switch(typeof tmp)
                {
                    case 'string':
                        cell.appendChild(document.createTextNode(tmp));
                        break;
                    case 'object':
                        cell.appendChild(tmp);
                        break;
                    default:
                        cell.appendChild(''+document.createTextNode(tmp));
                }
            }
        }
    }

    /**
     * Writes the table to an element
     * @param {*} where 
     */
    put(where)
    {
        const w = typeof where == 'string' ? document.getElementById('where') : where;
        w.appendChild(this.table);
    }
}