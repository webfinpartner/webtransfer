<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>Admin Login</title>
<base href="<?=base_url();?>"  />
<link href="<?=base_url('/')?>css/admin/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/spinner/ui.spinner.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/spinner/jquery.mousewheel.js"></script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/charts/excanvas.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/charts/jquery.sparkline.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/uniform.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/jquery.cleditor.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/jquery.validationEngine.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/autogrowtextarea.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/jquery.dualListBox.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/jquery.inputlimiter.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/chosen.jquery.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/wizard/jquery.form.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/wizard/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/wizard/jquery.form.wizard.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/uploader/plupload.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/uploader/plupload.html5.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/uploader/plupload.html4.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/uploader/jquery.plupload.queue.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/tables/datatable.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/tables/tablesort.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/tables/resizable.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.tipsy.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.collapsible.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.progress.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.timeentry.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.colorpicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.jgrowl.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.breadcrumbs.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.sourcerer.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/calendar.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/elfinder.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/custom.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/myscripts.js"></script>
</head>

<body class="nobg loginPage">


<?php
if(!empty($error) and $error==1){?>
<div class="nNote nFailure hideit" style="margin:0 30%; margin-top:50px; width:540px; position:absolute">
            <p><strong>Сообщение: </strong>Ошибка при авторизации. Логин  или пароль  введены не верно</p>
        </div>
<?}?>

<!-- Main content wrapper -->
<div class="loginWrapper">
    <div class="loginLogo"><img src="images/loginLogo.png" alt="" /></div>
    <div class="widget">
        <div class="title"><img src="images/icons/dark/files.png" alt="" class="titleIcon" /><h6>Панель Администратора</h6></div>
        <form action="opera/auth/login" id="validate" method="post" class="form">
            <fieldset>
                <div class="formRow">
                    <label for="login">Логин:</label>
                    <div class="loginInput"><input type="text" name="login" class="validate[required]" id="login" /></div>
                    <div class="clear"></div>
                </div>

                <div class="formRow">
                    <label for="pass">Пароль:</label>
                    <div class="loginInput"><input type="password" name="password" class="validate[required]" id="pass" /></div>
                    <div class="clear"></div>
                </div>

                <div class="loginControl">
                    <div class="rememberMe"><input type="checkbox" id="remMe" name="remMe" /><label for="remMe">Запомнить меня</label></div>
                    <input type="submit" value="Войти в систему" class="dredB logMeIn" />
                    <div class="clear"></div>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<!-- Footer line -->
<div id="footer">
    </div>
</body>
</html>
