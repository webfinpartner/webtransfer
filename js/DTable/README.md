It's a data table for jQuery. I made it to study grunt and it's first part of my Symfony 2 admin boundle.  The plugin is highly customizable,
It's uses html template to generate the table (it's not necessary to be a table, can be anything see "Custom template" example) and the whole plugin module based,
so you can add new features easily.

New features in the last version:
- formatter widgets (string, number, partial)

**If you are using this plugin, pleas send me the webpage address and I will put it on this page. If you want to correct my english, please fell free
to contribute to the project on github :)**

================

***Examples***

See [http://dtable.devdrive.org](http://dtable.devdrive.org)

***Download***

This repo is used for development, you can [download](http://dtable.devdrive.org/publish/DTable.v0.5.0.tar.gz) the latest stable release.

***Dependencies***

- jQuery 1.10+
- [Nunjucks](http://jlongster.github.io/nunjucks/)
- for number formatter widget: [numeraljs](http://numeraljs.com/)

***How to use***

``` javascript
  $("#div").dtable({});
```

Evry module has its own options, you can see it in the modules list.

***Options***

``` text
  {
    definition: {
        name: <module_name>,            # default: "json_url"
        options: <module_options>
    },
    template: {
        name: <module_name>,            # default: "nunjucks"
        options: <module_options>
    },
    logger: {
        name: <module_name>,            # default: "default"
        options: <module_options>
    },
    source: {
        name: <module_name>,            # default: "json_url"
        options: <module_options>
    },
    search: {
        name: <module_name>,            # default: "default"
        options: <module_options>
    },
    pagination: {
        name: <module_name>,            # default: "default"
        options: <module_options>
    },
    loading: {
        name: <module_name>,            # default: "default"
        options: <module_options>
    },
    order: {
        name: <module_name>,            # default: "default"
        options: <module_options>
    },
    formatter: {
        name: <module_name>,            # default: "false"
        options: <module_options>
    }
  }
```
-------------------------

Modules
-------

-------------------------
***Definition modules***

Its used to get the table definition, most of the configs goes from here.

### "json_url"

> Load table definition from url. Request is sent with POST or GET and the response must be in json format.


>  ***options***:

>```
    url: <string>               # url to download the json data, default: ""
    method: <"post"|"get">      # method for request, default: "get"
    data: {}                    # extra data to send, default: {}
    timestamp: <true|false>     # if true, it will add timestamp to prevent caching the page
```

>  ***response json format***

> ``` text
{
    "title": <string||false>,                                       # table title
    "columns": {
        <column_id>: {
            "title":  <false||string>,                              # table title, if false no column title displayed,
                                                                    # its work if all column title is false
            "filter": <false||true||{"placeholder": <string>}>,     # column filter, placeholder: input field placeholder
            "order":  <false||true>,                                # column order enable/disable
            "html_tag_attr":   <false||{                            # html attr for column header
              <attr_name>: <attr_value>
            }>,
            // not required, used by formatter module
            "formatter": <formatter module specified options>       # here you can set column option for formatter
        }
    }
}
```

-------------------------
***Template modules***

Its used to renderer the template. There are 3 different template: table, rows, pagination.

### "nunjucks"

> Requires [Nunjucks](http://jlongster.github.io/nunjucks/) to render the table. Its loading the template from the view_dir.

>***options***
>``` text
    view_dir: <string>                  # url pointing to the view dir, default: "/view"
    table_template: <string>            # table template filename, default: "table.html"
    rows_template: <string>             # rows template filename, default: "rows.html"
    pagination_template: <string>       # pagination template filename, default: "pagination.html"
```

-------------------------
***Logger modules***

It's used to log errors/dev informations.

### "default"

>***options***
>``` text
    debug: <true||false>                # in debug mode debug information logged to the console
```

-------------------------
***Source modules (data source)***

Its used to get the table rows.

### "json_url"

>It will send the query string (built with search module) to the url, its require a json response.

>***options***
>``` text
    url: <string>                       # url to put query paramters, default: ""
    method: <"post"|"get">              # method to use, default: "post"
```

>***query parameters***
>```
    search: <string>,
    filter: "" || {
        <column_id>: <filter_text>,
        ...
    },
    per_page: <int>,
    offset: <int>,
    order: "" || {                      # currently only one order by column supported, but its possible to have more than one
        <column_id>: "asc"||"desc",
        ...
    }
```

>***response json format***
>```
    [
        {
            <col_id> : <value>,
            ....
        },
        ....
    ]
```

-------------------------
***Search modules***

Its used to create query string (see source modules, query parameters), and initiate table refresh. Its handle the search and filter fields.

### "default"

> ``` text
    placeholder:                            # serch input field placeholder, default: "search...",
    waiting: <int>                          # time in ms to wait after last modification in search
                                            # paramters before submitting, default: 600
```

-------------------------
***Pagination modules***

Its used to handle pagination related tasks.

### "default"

>**options**
>``` text
    show_first_and_last: <boolean>          # in pager, first and last page shown? default: true
    pages: <int>                            # how many page in the pager, odd number, default: 5
    rows_per_page: <int>                    # results per page, default: 20
    rows_per_page_select: [<int>, ...]      # rows per page select, default: [20, 50, 100]
```

-------------------------
***Loading modules***

Show loading message when the table refresh.

### "default"

> There is no options. It's uses html tag with data-dtable="loading" attr to show in the table header. If you want to make other
> loading indicator, pleas create one and make a pull request.

-------------------------
***Formatter modules***

This modules used to format cells, you can add formatter options to column definition, available options is depend on formatter.

### "simple"

> use one formatter widget to render the table cells. widget_options can be set within column definition formatter option, too.

>**options**
>``` text
    widget: <string>,               # default: string
    widget_option: {}        # default: {escape: true}
```

### "advanced"

> similar to simple formatter, the only difference is that you can define formatter widget to use in
> column definition (different widget per col)
> column definition formatter option equals with the module options
> this is the default formatter.

>**options**
>``` text
    widget: <string>,               # default: string
    widget_option: {}               # default: {escape: true}
```

-------------------------
***Formatter widgets***

### "string"

> render a string in a cell
>```
    escape: <boolean>
```

### "number"

> render a number, requires [Numeral.js](http://numeraljs.com/)
>```
    {
        number_format: '0,0.0',           # see numeral.js options
        language: 'en',                   # numeral.js language option
        force_number: false               # set it true to show 0 instead of string, for example "asdf" converted to 0
    }
```

### "partial"

> It's use the given template to render a cell
>```
    {
        template: <string>                  # template inside view dir (see default template module)
    }
```


-------------------------
Dev requirements
================

- nodejs
- php5.4+ (for built in server)
- grunt
- npm
- bower

after clone, run update.sh to update npm and bower modules.

***usable grunt commands***

- `grunt build`: build the plugin
- `grunt server`: start server with live reloading on http://127.0.0.1:8080

Plans
-------

- new homepage with wiki
- a new loader module
- better error handling
- editable rows
-- column types (int, string. select, multiselect, boolean)
