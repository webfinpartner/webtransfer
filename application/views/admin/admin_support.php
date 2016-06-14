<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<a id="content_admin_users_downloadEmail" href='/opera/users/downloadEmail' style='float:right'>Загрузить emails</a>

<div id="table">
    <div data-dtable="loading" class="loading">
        Loading...
    </div>
</div>


<!--<script src="/js/jquery.js"></script>-->
<script src="/js/nunjucks.min.js"></script>
<script src="/js/DTable/DTable.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
<style>
        .loading {
            color: #000000;
        }

        .order-by a {
            color: #222222;
        }

        .order-by a:hover, .order-by a:active, .order-by a:focus {
            outline: 0;
            text-decoration: none;
        }

        .order-by .active {
            color: #0099FF;
            text-decoration: none;
        }
        .glyphicon {
            /*display: none;*/
        }
        .active {
            /*display: block;*/
        }
        .order-by {
            display: block;
        }
        #table th {
            text-align: center;
        }
        .row_on_click{
            cursor: pointer;
        }
        .col_id_state {
            text-align: center;
        }
        .row_highlight{
            color: #d30080;
        }
    </style>


<!-- required for number formatter -->
<!--<script src="js/numeral.min.js"></script>
<script src="js/numeral.languages.min.js"></script>-->

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
                    timestamp: true
                }
            },
            order: {
                options: {
                    id: "desc"
                }
            },
            source: {
                options: {
                    url: "<?=$url?>",
                    method: "post",
                    onLoad: function(){rowClick();}
                }
            },
//            logger: {
//                options: {
//                    debug: true
//                }
//            },
            formatter: {
                name: "advanced",
                // this is the default config, we can override it in definition config
                options: {
                    widget: 'string', //row_highlight
                    widget_options: {
                        escape: true
                    }
                }
            }
        });
    });

    function rowClick(){
        $(".row_on_click").click(function(){
            var field = "id";
            var id = $(this).find("["+field+"]").attr(field);
            window.location = "/opera/<?=$controller?>/admin_support_topic/"+id;
        });
    };
</script>