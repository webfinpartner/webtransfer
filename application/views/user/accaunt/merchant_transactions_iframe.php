<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<html>
    <head>
        <script src="/js/jquery-1.11.0.min.js"></script>
        <script src="/js/nunjucks.min.js"></script>
        <script src="/js/DTable/DTable.jquery.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="/css/cssDTableFont.css"/>

<!--        <link rel="stylesheet" href="/css/user/styles1.css" />
        <link rel="stylesheet" href="/css/user/fancybox.css" />
        <link rel="stylesheet" href="/css/user/select.css" />
        <link rel="stylesheet" href="/css/user/accaunt.css" />-->
    </head>
    <body>
        <div id="table">
            <div data-dtable="loading" class="loading">
                Loading...
            </div>
        </div>
        <script>

            $().ready(function () {

                $("#table").dtable({
                    template: {
                        options: {
                            view_dir: '/js/DTable/views',
                            table_template: 'table.html',
                            rows_template: 'rows.html',
                            pagination_template: 'pagination.html'
                        }
                    },
                    definition: {
                        options: {
                            url: "<?=$url?>",
                            method: "post",
                            data: {def: "1"},
                            timestamp: true,
                            search: false
                        }
                    },
                    source: {
                        options: {
                            url: "<?=$url?>",
                            method: "post",
                            onLoad: function(){}
                        }
                    },
        //            logger: {
        //                options: {
        //                    debug: true
        //                }
        //            },
                    order: {
                        options: {
                            id: "desc"
                        }
                    },
                    formatter: {
                        name: "advanced",
                        // this is the default config, we can override it in definition config
                        options: {
                            widget: 'string',
                            widget_options: {
                                escape: true
                            }
                        }
                    }
                });

            });
        </script>
</body>
</html>