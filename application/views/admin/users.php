<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>


<div>
	<a id="content_admin_payment_all_annul" style="margin: 5px 0px;float:right; height: 25px;" class="button redB" title="" href="/opera/users/users_block">
        <span>Заблокирвать по IP</span>
    </a>

	</div>

<div id="table" style="clear: both;">
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
                    timestamp: true,
                    search: false
                }
            },
            order: {
                options: {
                    date: "desc"
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
                    widget: 'string',
                    widget_options: {
                        escape: true
                    }
                }
            }
        });
    });

    function rowClick(){
        $(".row_on_click").click(function(){
            var field = "id_user";
            var id = $(this).find("["+field+"]").attr(field);
            window.location = "/opera/<?=$controller?>/"+id;
        });
        $(".col_id_id_user").click(function(){
            var field = "id_user";
            var id = $(this).attr(field);
            window.open("/opera/<?=$controller?>/"+id,'_blank');
            return false;
        });
        $(".col_id_state").click(function(){
            var field = "id_user";
            var id = $(this).parent().find("["+field+"]").attr(field);
            field = "state";
            var status = $(this).parent().find("["+field+"]").attr(field);
            if(confirm("Вы действительно хатите поменять статус пользователя "+ id + "?")){
                $(this).addClass("changeStatus");
                $(this).data("id",id);
                $.get( "/opera/<?=$controller?>/switchUserStatus/"+id,
                       {},
                       function(d){
                           if(1 == d.status) $(".changeStatus").html("<img src='images/icons/160.png' />");
                            else if (2 == d.status) {
                                $(".changeStatus").html("<img src='images/icons/152.png' />");
                                $("#txt_change_status").data("id",$(".changeStatus").data("id"));
                                 $("#txt_change_status").data("status", 2);
                                $("#change_status").dialog('open');
                            }
                            else {
                                $(".changeStatus").html("<img src='images/icons/151.png' />")
                                $("#txt_change_status").data("id",$(".changeStatus").data("id"));
                                 $("#txt_change_status").data("status", 3);
                                $("#change_status").dialog('open');
                            };

                            $(".changeStatus").removeClass("changeStatus");
                       },
                       "json"
                );
            }

            return false;
        });
    };
    function ajax_send(id){
        $("#change_status").dialog('close')
        var cause = $("#txt_change_status").val();
        var id = $("#txt_change_status").data("id");
        $.post( "/opera/<?=$controller?>/wrightCause/"+id,
            {cause:cause},
            function(){},
            "json"
        );
        $("#txt_change_status").val('');
        return false;
    }
</script>
<? $this->load->view('admin/blocks/user_block_window.php'); ?>