/*! DTable - v0.5.1 - 2014-03-19
* https://github.com/kubanka-peter/dtable
* Copyright (c) 2014 Kubi; Licensed MIT */
var DTableInterfaces = (function ($) {

    /* Simple JavaScript Inheritance
     * By John Resig http://ejohn.org/
     * http://ejohn.org/blog/simple-javascript-inheritance/
     * MIT Licensed.
     */
    // Inspired by base2 and Prototype
    var Class = (function () {
        var initializing = false, fnTest = /xyz/.test(function () {
            xyz;
        }) ? /\b_super\b/ : /.*/;

        // The base Class implementation (does nothing)
        var Class = function () {
        };

        // Create a new Class that inherits from this class
        Class.extend = function (prop) {
            var _super = this.prototype;

            // Instantiate a base class (but only create the instance,
            // don't run the init constructor)
            initializing = true;
            var prototype = new this();
            initializing = false;

            // Copy the properties over onto the new prototype
            for (var name in prop) {
                // Check if we're overwriting an existing function
                prototype[name] = typeof prop[name] == "function" &&
                    typeof _super[name] == "function" && fnTest.test(prop[name]) ?
                    (function (name, fn) {
                        return function () {
                            var tmp = this._super;

                            // Add a new ._super() method that is the same method
                            // but on the super-class
                            this._super = _super[name];

                            // The method only need to be bound temporarily, so we
                            // remove it when we're done executing
                            var ret = fn.apply(this, arguments);
                            this._super = tmp;

                            return ret;
                        };
                    })(name, prop[name]) :
                    prop[name];
            }

            // The dummy class constructor
            function Class() {
                // All construction is actually done in the init method
                if (!initializing && this.init) {
                    this.init.apply(this, arguments);
                }
            }

            // Populate our constructed prototype object
            Class.prototype = prototype;

            // Enforce the constructor to be what we expect
            Class.prototype.constructor = Class;

            // And make this class extendable
            Class.extend = arguments.callee;

            return Class;
        };

        return Class;
    })();

    var interfaces = {};

    var IFace = Class.extend({
        getIFaceNames: function()
        {
            var result = [];

            for (var name in interfaces)
            {
                result.push(name);
            }

            return result;
        },
        isExist: function(name)
        {
            if (interfaces[name] == undefined)
            {
                return false;
            }
            else
            {
                return true;
            }
        },
        add: function (name, iface)
        {
            if (this.isExist(name))
            {
                throw "Interface " + name + " is exists";
            }

            interfaces[name] = this.extend(iface);
        },
        get: function (name)
        {
            if (!this.isExist(name))
            {
                throw "Interface " + name + " is not exists";
            }

            return interfaces[name];
        },
        extend: function(props)
        {
            return Class.extend(props);
        }
    });

    return new IFace();

}(jQuery));
;var DTableModule = (function (IFace, $) {

    var _modules = false;

    var DTableModule = IFace.extend({
        init: function () {
            this.MODULE_TEMPLATE = 'template';
            this.MODULE_DEFINITION = 'definition';
            this.MODULE_LOGGER = 'logger';
            this.MODULE_SOURCE = 'source';
            this.MODULE_SEARCH = 'search';
            this.MODULE_PAGINATION = 'pagination';
            this.MODULE_LOADING = 'loading';
            this.MODULE_ORDER = 'order';
            this.MODULE_FORMATTER = 'formatter';
            this.MODULE_CORE = 'core';
            this.MODULE_FORMATTER_WIDGET = 'formatter_widget';
        },
        initModules: function(){
            if (_modules == false)
            {
                _modules = {};

                $.each(IFace.getIFaceNames(), function(key, name){
                    _modules[name] = {};
                });
            }
        },
        check: function(type)
        {
            if (!IFace.isExist(type)) {
                throw "Invalid DTableModule type";
            }
        },
        isExist: function(type, name)
        {
            this.initModules();

            if (_modules[type] == undefined || _modules[type][name] == undefined)
            {
                return false;
            }
            else
            {
                return true;
            }
        },
        getModule: function (type, name, options, dtable) {

            this.initModules();

            this.check(type);

            if (!this.isExist(type, name))
            {
                throw "DTableModule '" + name + "' doesn't exist.";
            }

            return new _modules[type][name](options, dtable);
        },
        newModule: function (type, name, props) {

            this.initModules();

            this.check(type);

            if (this.isExist(type, name))
            {
                throw "DTableModule " + name + " already exist.";
            }

            _modules[type][name] = IFace.get(type).extend(props);
        },
        extendModule: function (type, extend, newName, props) {

            this.initModules();

            this.check(type);

            if (!this.isExist(type, extend))
            {
                throw "DTableModule '" + extend + "' doesn't exist.";
            }

            if (this.isExist(type, newName)) {
                throw "DTableModule " + newName + " already exist.";
            }

            _modules[type][newName] = _modules[type][extend].extend(props);
        }
    });

    return new DTableModule();

}(DTableInterfaces, jQuery));
;(function(IFace){

    IFace.add('core', {
        init: function(options, dtable) {
            this.table = dtable;
            var defaults = {
                definition: {
                    name: "json_url",
                    options: {}
                },
                template: {
                    name: "nunjucks",
                    options: {}
                },
                logger: {
                    name: "default",
                    options: {}
                },
                source: {
                    name: "json_url",
                    options: {}
                },
                search: {
                    name: "default",
                    options: {}
                },
                pagination: {
                    name: "default",
                    options: {}
                },
                loading: {
                    name: "default",
                    options: {}
                },
                order: {
                    name: "default",
                    options: {}
                },
                formatter: {
                    name: "advanced",
                    options: {
                        widget: "string"
                    }
                }
            };

            this.options = $.extend(true, {}, defaults, options);

            this.configure();
        },
        /**
         * Update table imediately, use search module update to queue
         */
        update: function () {
        }
    });


}(DTableInterfaces));
;(function(IFace){

    IFace.add('definition', {
        isLoaded: false,
        loading: function (callback) {
        },
        /**
         * get the table title
         *
         * @returns {string}
         */
        getTitle: function () {
        },
        /**
         * get the columns definition,
         * must return the following format
         *
         * {
         *   <column_id> : {
         *     title: <title||flase>,                                       # column title, if false no column title will shown and order and html attr will not work
         *     filter: <false||{placeholder: <placeholder_text>}>,          # enable filter for column, placeholder shown in the input field
         *     order:  <false||true||"desc"||"asc">,                        # enable order for column, if "desc" or "asc" the column will be ordered
         *     html_tag_attr: <false || {                                             # html attributes, can be false
         *       <attr_name_1>: <attr_value_1},                             # style: "color: #f00" => <td style="color: #f00">{{ column_title }}</td>
         *       <attr_name_2>: <attr_value_3},
         *       ...
         *     }>
         *   }
         * }
         *
         *
         *
         * @returns {{}}
         */
        getColumns: function () {
        },
        /**
         *  get the pagination definition
         *  must return the following format or false:
         *
         *  {
         *      show_first_last: <true|false>,      # show first and last page
         *      pages: <int>,                       # how many page shown in the pager, odd number
         *      rows_per_page: <int>                # number of rows in a page
         *  }
         *
         *  if false returned, no pagination used
         *
         * @returns {{}}||false
         */
        getPagination: function () {
        },
        /**
         * get the global search definition
         * must return the following format or false:
         *
         * {
         *      placeholder: <string>               # search input field placeholder text
         *      submit: <string>                    # submit button text
         * }
         *
         * @returns {{}}
         */
        getSearch: function () {
        },
        /**
         * return true if one of the column deff has filter enabled
         */
        hasColumnFilter: function () {
        },
        /**
         * return true if one of the column has title
         */
        hasColumnTitle: function () {
        }

    });


}(DTableInterfaces));
;(function(IFace){

    IFace.add('formatter', {
        format: function (columnId, value, values) {
            return value;
        }
    });


}(DTableInterfaces));
;(function (IFace, $) {

    IFace.add('formatter_widget', {
        init: function (options, dtable) {
            this.dtable = dtable;
            this.options = $.extend(true, {}, this.getDefaults(), options);
        },
        getDefaults: function () {
            return {};
        },
        format: function (columnId, value, values) {
            return value;
        }
    });


}(DTableInterfaces, jQuery));
;(function(IFace){

    IFace.add('loading', {
        startLoading: function () {
        },
        stopLoading: function () {
        }
    });


}(DTableInterfaces));;(function(IFace){

    IFace.add('logger', {
        /**
         * Log error also throw exception and stop loading
         *
         * @param msg
         */
        error: function (msg) {
        },
        /**
         * Log info
         * @param msg
         */
        info: function (msg) {
        }
    });


}(DTableInterfaces));;(function(IFace){

    IFace.add('order', {
        getOrderBy: function () {
        }
    });


}(DTableInterfaces));;(function(IFace){

    IFace.add('pagination', {
        /**
         * current page
         * @return int
         */
        getPage: function () {
        },
        /**
         * set current page
         * @param page
         */
        setPage: function (page) {
        },
        /**
         * have to show first and last page?
         */
        getShowFirstLast: function () {
        },
        /**
         * number of pages to show in pagination, odd number!
         */
        getPageNum: function () {
        },
        /**
         * get rows per page
         */
        getRowsPerPage: function () {
        },
        /**
         * set rows per page
         * @param page
         */
        setRowsPerPage: function (page) {
        },
        /**
         * max page num
         */
        getMaxPage: function () {
        },
        /**
         * array contains pages to show in pagination
         */
        getPagesArr: function () {
        },
        /**
         * offset to post in query
         */
        getOffset: function () {
        },
        /**
         * rows per page select, return array with options
         */
        getRowsPerPageSelect: function () {
        }
    });


}(DTableInterfaces));;(function(IFace){

    IFace.add('search', {
        /**
         * call it when a search parameter changed, it will call dtable.update
         */
        update: function () {
        },
        /**
         * get the query params to post
         */
        getParams: function () {
        }
    });


}(DTableInterfaces));;(function(IFace){

    IFace.add('source', {
        isLoaded: false,
        loading: function (callback) {
        },
        /**
         * must return the following format:
         *
         * [{
         *   <column_id> : <data>,
         *   ...
         * }, ... ]
         */
        getRows: function () {
        },
        getCount: function () {
        }
    });


}(DTableInterfaces));
;(function (IFace) {

    IFace.add('template', {
        isLoaded: false,
        /**
         * Add a template
         *
         * @param templateName
         * @param templateFile
         */
        addTemplate: function (templateName, templateFile) {
        },
        /**
         * return false if the template is not loaded
         *
         * @param templateName
         */
        getTemplate: function (templateName) {
        },
        /**
         * Load unloaded templates
         * @param callback
         */
        loading: function (callback) {
        },
        /**
         * Return the rendered table html
         * @param params
         */
        getTableHtml: function (params) {
        },
        /**
         * Retrun the rendered rows html
         * @param params
         */
        getRowsHtml: function (params) {
        },
        /**
         * Return the rendered pagination html
         * @param params
         */
        getPaginationHtml: function (params) {
        }
    });


}(DTableInterfaces));
;(function (DTableModule, $) {

    DTableModule.newModule(DTableModule.MODULE_CORE, "firefly", {
        configure: function () {

            var obj = this;

            // init modules
            this.definition = DTableModule.getModule(DTableModule.MODULE_DEFINITION, this.options.definition.name, this.options.definition.options, this);
            this.pagination = DTableModule.getModule(DTableModule.MODULE_PAGINATION, this.options.pagination.name, this.options.pagination.options, this);
            this.template = DTableModule.getModule(DTableModule.MODULE_TEMPLATE, this.options.template.name, this.options.template.options, this);
            this.logger = DTableModule.getModule(DTableModule.MODULE_LOGGER, this.options.logger.name, this.options.logger.options, this);
            this.source = DTableModule.getModule(DTableModule.MODULE_SOURCE, this.options.source.name, this.options.source.options, this);
            this.search = DTableModule.getModule(DTableModule.MODULE_SEARCH, this.options.search.name, this.options.search.name, this);
            this.loading = DTableModule.getModule(DTableModule.MODULE_LOADING, this.options.loading.name, this.options.loading.options, this);
            this.order = DTableModule.getModule(DTableModule.MODULE_ORDER, this.options.order.name, this.options.order.options, this);
            this.formatter = false;

            this.loading.startLoading();

            this.definition.loading(function(){

                // formatters need access to definition module
                // and we need to load it before templates
                obj.formatter = DTableModule.getModule(DTableModule.MODULE_FORMATTER, obj.options.formatter.name, obj.options.formatter.options, obj);

                obj.template.loading(obj.loaded);
            });
        },
        /**
         * Everything is loaded? Then start rendering.
         */
        loaded: function () {
            if (this.definition.isLoaded && this.template.isLoaded) {
                this.renderTable();
                this.update();
            }
        },
        /**
         * Update table rows
         */
        update: function () {
            this.loading.startLoading();
            this.source.loading(this.sourceUpdated);
        },
        sourceUpdated: function () {
            this.loading.stopLoading();
            this.renderRows();
            this.renderPagination();

            this.table.trigger("dtable.updated");
        },
        /**
         * Render table
         */
        renderTable: function () {
            var html = this.template.getTableHtml({
                "title": this.definition.getTitle(),
                "pagination": this.definition.getPagination(),
                "search": this.definition.getSearch(),
                "columns": this.definition.getColumns(),
                "has_column_filter": this.definition.hasColumnFilter(),
                "has_column_title": this.definition.hasColumnTitle()
            });

            this.renderTableHtml(html);
        },
        /**
         * Render table html
         *
         * @param html
         */
        renderTableHtml: function (html) {
            this.table.html(html);
        },
        /**
         * Render rows
         */
        renderRows: function () {

            var columns = this.definition.getColumns();
            var rows = this.source.getRows();
            var formatter = this.formatter;

            for (var rowIndex in rows)
                for (var colId in columns)
                    rows[rowIndex][colId] = formatter.format(colId, rows[rowIndex][colId], rows[rowIndex]);


            var html = this.template.getRowsHtml({
                "rows": rows,
                "columns": this.definition.getColumns(),
                "count_cols": Object.keys(this.definition.getColumns()).length
            });

            this.renderRowsHtml(html);
        },
        /**
         * Render rows html
         */
        renderRowsHtml: function (html) {
            var table = this.table.find('[data-dtable="table"]');

            if (table.length) {
                table.html(html)
            }
            else {
                this.logger.error('Can\'t find rows root element [data-dtable="table"]');
            }
        },
        /**
         * Render pagination
         */
        renderPagination: function () {
            var html = "";

            var pages = this.pagination.getPagesArr();

            if (pages) {
                html = this.template.getPaginationHtml({
                    first: 1,
                    last: this.pagination.getMaxPage(),
                    pages: pages,
                    active: this.pagination.getPage(),
                    first_last: this.definition.getPagination().show_first_last,
                    rows_per_page: this.pagination.getRowsPerPage(),
                    rows_per_page_select: this.pagination.getRowsPerPageSelect()
                });
            }

            this.renderPaginationHtml(html);

        },
        /**
         * Render pagination html
         */
        renderPaginationHtml: function (html) {
            var pagination = this.table.find('[data-dtable="pagination"]');

            if (pagination.length) {
                pagination.html(html);
            }
        }
    });

}(DTableModule, jQuery));
;(function (DTableModule, $) {

    DTableModule.newModule(DTableModule.MODULE_DEFINITION, "json_url", {
        init:            function (options, dtable) {
            this.definition = {};
            this.isLoaded = false;

            var defaults = {
                method:    "get",
                url:       "",
                data:      {},
                timestamp: false,
                search: true
            };

            this.dtable = dtable;
            this.options = $.extend({}, defaults, options);
        },
        getTitle:        function () {
            return this.definition.title;
        },
        getColumns:      function () {
            return this.definition.columns;
        },
        getPagination:   function () {
            return {
                show_first_last: this.dtable.pagination.getShowFirstLast(),
                pages:           this.dtable.pagination.getPageNum(),
                rows_per_page:   this.dtable.pagination.getRowsPerPage()
            };
        },
        getSearch:       function () {
            if(this.options.search){
                return {
                    placeholder: this.dtable.search.options.placeholder
                };
            }
        },
        hasColumnFilter: function () {
            return this.definition.has_column_filter;
        },
        hasColumnTitle: function() {
            return this.definition.has_column_title;
        },
        loading:         function (callback) {
            var url = this.options.url;
            var obj = this;

            function success(data) {
                obj.definition = data;
                obj.isLoaded = true;
                obj.dtable.logger.info("json_url.definition: resource is loaded");

                obj.definition.has_column_filter = false;
                obj.definition.has_column_title = false;

                $.each(obj.getColumns(), function (key, value) {
                    if (value.filter) {
                        obj.definition.has_column_filter = true;
                    }

                    if (value.title)
                    {
                        obj.definition.has_column_title = true;
                    }
                });

                callback.call(obj.dtable);

                if (obj.options.onLoad) obj.options.onLoad();
            }

            var type = "POST";
            if (this.options.method == "get") {
                type = "GET";
            }

            $.ajax(url, {
                url:      url,
                type:     type,
                async:    true,
                cache:    this.options.timestamp,
                data:     this.options.data,
                dataType: "json",
                error:    function () {
                    obj.dtable.logger.error("Can't load definition resource from " + url);
                },
                success:  success
            });
        }
    });

}(DTableModule, jQuery));
;(function ($, DTableModule) {
var dtable = {};
    var methods = {
        init : function (options, core) {
            if (!this.data("dtable")) {
                core = core || "firefly";
                dtable[$(this).selector] = DTableModule.getModule(DTableModule.MODULE_CORE, core, options, this);
            } else
                dtable[$(this).selector] = this.data("dtable");

            return dtable[$(this).selector];
        },
        get : function( content ) {
          return dtable[$(this).selector];
        }
    };

    $.fn.dtable = function( method ) {
        // логика вызова метода
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object') {
            return methods.init.apply( this, arguments );
        } else if ( ! method ) {
            return methods.get.apply( this, arguments );
        } else {
            $.error( 'Метод с именем ' +  method + ' не существует для jQuery.dtable' );
        }
    };

}(jQuery, DTableModule));
;(function (DTableModule, $) {

    DTableModule.newModule(DTableModule.MODULE_FORMATTER, "advanced", {
        init: function (options, dtable) {

            var defaults = {
                widget: "string",
                widget_options: {
                    escape: true
                }
            };

            this.dtable = dtable;
            this.options = $.extend(true, {}, defaults, options);

            this.widgets = false;

            this.buildWidgetSchema();
        },
        buildWidgetSchema: function () {

            if (this.widgets == false) {

                this.widgets = {};

                var obj = this;
                var columns = this.dtable.definition.getColumns();

                $.each(columns, function (columnId, options) {

                    var formatterOpt = obj.options;

                    if (options.hasOwnProperty("formatter") && options.formatter) {
                        formatterOpt = $.extend(true, {widget_options: {column_id: columnId}}, obj.options, options.formatter);
                    }

                    obj.setWidget(columnId, formatterOpt);
                });
            }
        },
        setWidget: function (columnId, options) {
            this.widgets[columnId] = DTableModule.getModule(DTableModule.MODULE_FORMATTER_WIDGET, options.widget, options.widget_options, this.dtable);
        },
        getWidget: function (columnId) {
            if (this.widgets[columnId] == undefined) {
                throw "widget does not exist for " + columnId;
            }

            return this.widgets[columnId];
        },
        format: function (columnId, value, values) {

            var widget = this.getWidget(columnId);

            return widget.format(columnId, value, values);
        }

    });

}(DTableModule, jQuery));
;(function (DTableModule, $) {

    DTableModule.newModule(DTableModule.MODULE_FORMATTER, "simple", {
        init: function(options, dtable){

            var defaults = {
                widget: 'string',
                widget_options: {
                    escape: true
                }
            };

            this.options = $.extend(true, {}, defaults, options);
            this.dtable = dtable;

            this.widget = false;
        },
        initWidget: function(){
            if (this.widget == false)
            {
                this.widget = DTableModule.getModule(DTableModule.MODULE_FORMATTER_WIDGET, this.options.widget, this.options.widget_options, this.dtable);
            }
        },
        format: function (columnId, value, values) {

            this.initWidget();

            return this.widget
                .format(columnId, value, values);
        }
    });

}(DTableModule, jQuery));
;(function (DTableModule, $) {

    DTableModule.newModule(DTableModule.MODULE_FORMATTER_WIDGET, "row_highlight", {
        getDefaults: function(){
            return {
                class: "row_highlight",
                col_name: '',
                col_val: ''
            };
        },
        format: function (columnId, value, values) {

            if(this.options.col_val == values[this.options.col_name])
                value = "<span class='"+this.options.class+"'>"+value+"</span>"

            return value;
        }
    });

}(DTableModule, jQuery));
;(function (DTableModule, $) {

    DTableModule.newModule(DTableModule.MODULE_FORMATTER_WIDGET, "number", {
        getDefaults: function(){
            return {
                number_format: '0,0.0',
                language: 'en',
                force_number: false
            };
        },
        format: function (columnId, value, values) {

            if (!isNaN(parseInt(value)) || this.options.force_number)
            {
                numeral.language(this.options.language);
                value = numeral(value).format(this.options.number_format);
            }

            return value;
        }
    });

}(DTableModule, jQuery));
(function (DTableModule, $) {

    DTableModule.newModule(DTableModule.MODULE_FORMATTER_WIDGET, "currency", {
        getDefaults: function(){
            return {
                cur_format: ' 0,0[.]00',
                cur_symbol: '$',
                language: 'en',
                force_number: false
            };
        },
        format: function (columnId, value, values) {

            if (!isNaN(parseInt(value)) || this.options.force_number)
            {
                numeral.language(this.options.language);
                value = numeral(value).format(this.options.cur_symbol+this.options.cur_format);
            }

            return value;
        }
    });

}(DTableModule, jQuery));
(function (DTableModule, $) {

    DTableModule.newModule(DTableModule.MODULE_FORMATTER_WIDGET, "date", {
        getDefaults: function(){
            return {
                formatte: 'D/M/Y'
            };
        },
        format: function (columnId, value, values) {

            var date = new Date(Date.parse(value.replace(' ','T')));
            var Y = date.getFullYear();
            var M = date.getMonth()+1;
            var D = date.getDate();
            var h = date.getHours();
            var m = date.getMinutes();
            var s = date.getSeconds();

            value = this.options.formatte.replace('Y', Y).replace('M', M).replace('D', D).replace('h', h).replace('m', m).replace('s', s);

            return value;
        }
    });

}(DTableModule, jQuery));
(function (DTableModule, $) {

    DTableModule.newModule(DTableModule.MODULE_FORMATTER_WIDGET, "date_unix_time", {
        getDefaults: function(){
            return {
                formatte: 'D/M/Y'
            };
        },
        format: function (columnId, value, values) {

            var date = new Date(Date(value));
            var Y = date.getFullYear();
            var M = date.getMonth()+1;
            var D = date.getDate();
            var h = date.getHours();
            var m = date.getMinutes();
            var s = date.getSeconds();

            value = this.options.formatte.replace('Y', Y).replace('M', M).replace('D', D).replace('h', h).replace('m', m).replace('s', s);

            return value;
        }
    });

}(DTableModule, jQuery));
(function (DTableModule, $) {

    DTableModule.newModule(DTableModule.MODULE_FORMATTER_WIDGET, "list", {
        getDefaults: function(){
            return {
                list: [],
                def: " "
            };
        },
        format: function (columnId, value, values) {

            var list = this.options.list;
            var found = false;

            for (var itemId in list){
                if (itemId == value && !found){
                    value = list[itemId];
                    found = true;
                }
            }

            if (!found) value = this.options.def;

            return value;
        }
    });

}(DTableModule, jQuery));
;(function (DTableModule, $) {

    DTableModule.newModule(DTableModule.MODULE_FORMATTER_WIDGET, "partial", {
        init: function (options, dtable) {
            this._super(options, dtable);

            if (this.options.template == undefined || this.options.template == false)
            {
                throw "partial widget requires template option";
            }

            this.templateName = "partial_" + this.options.column_id;
            this.dtable.template.addTemplate(this.templateName, this.options.template);

            this.template = false;
        },
        getDefaults: function () {
            return {
                template: false
            };
        },
        format: function (columnId, value, values) {

            if (this.template === false)
            {
                this.template = this.dtable.template.getTemplate(this.templateName);
            }

            return this.template.render({
                value: value,
                column_id: columnId,
                values: values,
                xy: function(){ return "a"}
            });
        }
    });

}(DTableModule, jQuery));
;(function (DTableModule, $) {

    DTableModule.newModule(DTableModule.MODULE_FORMATTER_WIDGET, "string", {
        entityMap: {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': '&quot;',
            "'": '&#39;',
            "/": '&#x2F;'
        },
        escapeHTML: function(string)
        {
            var obj = this;

            return String(string).replace(/[&<>"'\/]/g, function (s) {
                return obj.entityMap[s];
            });
        },
        getDefaults: function(){
            return {
                escape: true
            };
        },
        format: function (columnId, value, values) {

            if (this.options.escape)
            {
                return this.escapeHTML(value);
            }
            else
            {
                return value;
            }
        }
    });

}(DTableModule, jQuery));
;(function(DTableModule, $){

    DTableModule.newModule(DTableModule.MODULE_LOADING, "default", {
        init: function(options, dtable){

            this.dtable = dtable;

            this.div = false;
            this.enabled = false;
            this.is_loading = false;

            var div = dtable.table.find('[data-dtable="loading"]');

            if (div.length)
            {
                this.enabled = true;

                this.div = div;
            }

        },
        startLoading: function(){

            var obj = this;
            this.dtable.table.trigger("dtable.start_loading");
            if (!this.is_loading && this.enabled)
            {
                this.is_loading = true;

                setTimeout(function(){

                    if (obj.is_loading)
                    {
                        obj.dtable.table.find('[data-dtable="loading-container"]').html(obj.div);
                    }

                }, 300)
            }
        },
        stopLoading: function(){

            this.dtable.table.trigger("dtable.stop_loading");

            if (this.enabled)
            {
                this.is_loading = false;
                var div = this.dtable.table.find('[data-dtable="loading"]');

                if (div.length)
                {
                    div.remove();
                }
            }
        }
    });

}(DTableModule, jQuery));;(function (DTableModule) {

    DTableModule.newModule(DTableModule.MODULE_LOGGER, "default", {
        init:  function (options, dtable) {
            var defaults = {
                debug: false
            };

            this.dtable = dtable;
            this.options = $.extend({}, defaults, options);
        },
        error: function (msg) {
            this.dtable.loading.stopLoading();

            this.dtable.table.html("Error.");

            throw msg;
        },
        info:  function (msg) {
            if (this.options.debug) {
                console.log(msg);
            }
        }
    });

}(DTableModule));
;(function(DTableModule, $){

    DTableModule.newModule(DTableModule.MODULE_ORDER, 'default', {
        init: function(options, dtable) {

            var defaults = {};

            this.options = $.extend({}, defaults, options);
            this.dtable = dtable;

            this.columns = options;

            var obj = this;

            this.dtable.table.on("click", '[data-dtable="order.asc"]', function(){
                obj.setOrder($(this), 'asc');

                return false;
            })
            this.dtable.table.on("click", '[data-dtable="order.desc"]', function(){
                obj.setOrder($(this), 'desc');

                return false;
            })
        },

        updateOrder: function(){
            var id;
            var option = this.columns
            for (id in option);

            this.dtable.table.find('[data-dtable-column="'+id+'"][data-dtable="order.'+option[id]+'"]').addClass("active");
        },
        setOrder: function(link, order){
            this.dtable.table.find('[data-dtable="order.asc"]').removeClass("active");
            this.dtable.table.find('[data-dtable="order.desc"]').removeClass("active");

            link.addClass("active");
            this.columns = {};
            this.columns[link.attr("data-dtable-column")] = order;

            this.dtable.update();
        },
        getOrderBy: function() {
            return this.columns;
        }
    });

}(DTableModule, jQuery));;(function(DTableModule, $){

    DTableModule.newModule(DTableModule.MODULE_PAGINATION, "default", {
        init: function(options, dtable){
            var defaults = {
                show_first_and_last: true,
                pages: 5,
                rows_per_page: 20,
                rows_per_page_select: [20, 50, 100]
            };

            this.page = 1;
            this.dtable = dtable;
            this.options = $.extend({}, defaults, options);

            var obj = this;

            dtable.table.on("click", '[data-dtable="page"]', function(){
                var link = $(this);
                var page = link.attr("data-page");

                obj.setPage(page);

                obj.dtable.update();

                return false;
            });

            dtable.table.on("change", '[data-dtable="rows-per-page-select"]', function(){
                var rowsPerPage = $(this).val();

                obj.setRowsPerPage(rowsPerPage);

                obj.dtable.update();

                return false;
            });

        },
        // current page
        getPage: function(){
            return this.page;
        },
        setPage: function(page){
            this.page = parseInt(page);
        },
        // pagination first and last page show?
        getShowFirstLast: function(){
            return this.options.show_first_and_last;
        },
        // pagination, shown pages
        getPageNum: function(){
            return this.options.pages;
        },
        // number of results per page
        getRowsPerPage: function(){
            return this.options.rows_per_page;
        },
        setRowsPerPage: function(rows){
            this.options.rows_per_page = rows;
        },
        getMaxPage: function(){
            return Math.ceil(this.dtable.source.getCount() / this.dtable.definition.getPagination().rows_per_page);
        },
        getOffset: function(){
            return (this.page * this.options.rows_per_page) - this.options.rows_per_page;
        },
        getPagesArr: function(){
            var maxPage = this.getMaxPage() || 1;
            var minPage = 1;

            var offset = Math.round((this.dtable.definition.getPagination().pages - 1) / 2);
            var start = this.getPage() - offset;
            var end = this.getPage() + offset;

            start = Math.max(minPage, start);
            end = Math.min(maxPage, end);

            if (end < this.dtable.definition.getPagination().pages)
            {
                end = Math.min(this.dtable.definition.getPagination().pages, maxPage);
            }

            if ((end - start) < this.dtable.definition.getPagination().pages)
            {
                start = end - this.dtable.definition.getPagination().pages + 1;
                if (start < minPage)
                {
                    start = minPage;
                }
            }

            var pages = false;

            if (start != end)
            {
                pages = [];

                for (var i = start; i <= end; i++)
                {
                    pages.push(i);
                }
            }

            return pages;
        },
        setRowsPerPageSelect: function(s)
        {
            this.options.rows_per_page_select = s;
        },
        getRowsPerPageSelect: function()
        {
            return this.options.rows_per_page_select;
        }
    });

}(DTableModule, jQuery));;(function(DTableModule, $){

    DTableModule.newModule(DTableModule.MODULE_SEARCH, "default", {
        init: function(options, dtable){

            var defaults = {
                placeholder: "search ...",
                waiting: 600
            };

            this.search = "";

            this.filter = "";

            this.in_progress = false;
            this.update_after = false;

            this.options = $.extend({}, defaults, options);
            this.dtable = dtable;

            var obj = this;

            this.dtable.table.on("keyup", '[data-dtable="search"]', function(){
                obj.search = $(this).val();
                obj.dtable.pagination.setPage(1);
                obj.update();
            });

            this.dtable.table.on("keyup", '[data-dtable="filter"]', function(){
                var elem = $(this);

                if (obj.filter === "")
                {
                    obj.filter = {};
                }
                obj.filter[elem.attr('data-column')] = elem.val();
                obj.dtable.pagination.setPage(1);
                obj.update();
            });
        },
        update: function()
        {
            var waiting = parseInt(this.options.waiting);
            this.update_after = new Date().getTime() + waiting;

            if (!this.in_progress)
            {
                this.in_progress = true;

                var obj = this;

                var wait = function(){
                    if (new Date().getTime() >= obj.update_after)
                    {
                        obj.in_progress = false;
                        obj.dtable.update();
                    }
                    else
                    {
                        setTimeout(wait, waiting / 2);
                    }
                }

                setTimeout(wait, waiting / 2);
            }
        },
        getParams: function(){
            var params = {
                search: this.search,
                filter: this.filter,
                per_page: this.dtable.definition.getPagination().rows_per_page,
                offset: this.dtable.pagination.getOffset(),
                order: this.dtable.order.getOrderBy()
            };

            return params;
        }
    });

}(DTableModule, jQuery));;(function(DTableModule, $){

    DTableModule.newModule(DTableModule.MODULE_SOURCE, "json_url", {
        init: function(options, dtable){
            var defaults = {
                url: "",
                method: "post"
            };

            this.data = null;
            this.isLoaded = false;
            this.options = $.extend({}, defaults, options);
            this.dtable = dtable;
        },
        loading:  function (callback) {

            var url = this.options.url;
            var obj = this;

            function success(data) {

                if (!('count' in data ) || !('rows' in data))
                {
                    obj.dtable.logger.error("Invalid source response");
                }

                obj.data = data;
                obj.isLoaded = true;
                callback.call(obj.dtable);

                if (obj.options.onLoad) obj.options.onLoad();
//                this.dtable.order.updateOrder();

//                if(typeof(this.options.onLoad)) this.options.onLoad();
            }

            function error() {
                obj.dtable.logger.error("Can't load source resource from " + url);
            }

			if (obj.options.preLoad) obj.options.preLoad(obj);
            if (this.options.method == "get") {
                $.get(url, obj.dtable.search.getParams(), success, "json").error(error);
            } else {
                $.post(url, obj.dtable.search.getParams(), success, "json").error(error);
            }
        },
        getRows: function(){
            return this.data.rows;
        },
        getCount: function(){
            return this.data.count;
        }
    });

}(DTableModule, jQuery));;(function (DTableModule, nunjucks) {

    DTableModule.newModule(DTableModule.MODULE_TEMPLATE, "nunjucks", {
        init:    function (options, dtable) {
            this.isLoaded = false;

            var defaults = {
                view_dir:            "/views",
                table_template:      "table.html",
                rows_template:       "rows.html",
                pagination_template: "pagination.html"
            };

            this.dtable = dtable;
            this.options = $.extend({}, defaults, options);

            this.templates = {};

            this.addTemplate('table', this.options.table_template);
            this.addTemplate('rows', this.options.rows_template);
            this.addTemplate('pagination', this.options.pagination_template);

            this.env = new nunjucks.Environment(new nunjucks.WebLoader(this.options.view_dir));
        },
        addTemplate: function(templateName, templateFile)
        {
            if (this.templates[templateName] != undefined)
            {
                throw "template " + templateName + " is sexist";
            }

            this.templates[templateName] = {
                file: templateFile,
                template: false
            };
        },
        /**
         * return false if the template is not loaded
         *
         * @param templateName
         */
        getTemplate: function(templateName) {

            if (this.templates[templateName] == undefined)
            {
                throw "template " + templateName + " is'nt exist.";
            }

            return this.templates[templateName].template;
        },
        loading: function (callback) {

            var obj = this;

            var checkLoaded = function(){
                var ok = true;
                $.each(obj.templates, function(templateName, options){
                    if (!options.template)
                    {
                        ok = false;
                    }
                });

                if (ok)
                {
                    obj.isLoaded = true;
                    obj.dtable.logger.info("nunjucks.template.js: all loaded");
                    callback.call(obj.dtable);
                }
            };

            $.each(this.templates, function(templateName, options){
                if (!options.template)
                {
                    obj.env.getTemplate(options.file, function(err, tmpl){
                        if (err)
                        {
                            obj.dtable.logger.error(err);
                        }
                        else
                        {
                            obj.templates[templateName].template = tmpl;
                            obj.dtable.logger.info("nunjucks.template.js: " + templateName + " is loaded");
                            checkLoaded();
                        }
                    });
                }

            });
        },
        getTableHtml: function(params)
        {
            return this.getTemplate("table").render(params);
        },
        getRowsHtml: function(params)
        {
            return this.getTemplate("rows").render(params);
        },
        getPaginationHtml: function(params)
        {
            return this.getTemplate("pagination").render(params);
        }
    });

}(DTableModule, nunjucks));
