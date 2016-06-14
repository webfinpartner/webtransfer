<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" href="/css/bootstrap.min.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<style>
    tbody > tr {
        cursor: pointer;
    }
    tbody > tr > td {
        background: white;
    }
</style>

<div class="widget">
    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Список заявок</h6>
    </div>
    <?
    if(!empty($errors)){
        if($errors['page_errors']){
            foreach($errors['page_errors'] as $error){
                print '<h3 class="text-warning">'.$error.'</h3>';
            }
        }
        if($errors['card_errors']){
            foreach($errors['card_errors'] as $error){
                print '<h3 class="text-danger">'.$error.'</h3>';
            }
        }



    }

    if($card->declined && $card->decline_error != ""){
        print '<div class="bg-danger">';
        print "Карта уже была отправлена на утверждение " . $card->declined . ", но сервис вернул ошибки<br>";
        if($errored = json_decode($card->decline_error, true)){
            foreach($errored as $erorring){
                print "Код ошибки: " . $erorring['errorCode'] . "<br>";
                print "Текст: " . $erorring['errorDescription'] . "<br>";
            }
        }
        print "</div>";
    }
    if(empty($errors['page_errors'])){ ?>
    <div class="formRow">
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-md-5 col-xs-5 col-lg-5 col-sm-5">
            <label style="font-size: 18px; text-decoration: underline;">Данные карты</label>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="<? print in_array("holder_name", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Держатель карты</label>
                    <input type="text" name="holder_name" class="form-control" placeholder="" value="<?=$card->holder_name;?>">
                </div>
                <div class="form-group">
                    <label class="<? print in_array("name", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Имя</label>
                    <input type="text" name="name" class="form-control" placeholder="" value="<?=$card->name;?>">
                </div>
                <div class="form-group">
                    <label class="<? print in_array("surname", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Фамилия</label>
                    <input type="text" name="surname" class="form-control" placeholder="" value="<?=$card->surname;?>">
                </div>
                <div class="form-group">
                    <label class="<? print in_array("birthday", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Дата рождения</label>
                    <input type="text" name="birthday" class="form-control" placeholder="" value="<?=$card->birthday;?>">
                </div>
                <div class="form-group">
                    <label class="<? print in_array("prop_adress", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Адрес прописки</label>
                    <input type="text" name="prop_adress" class="form-control" placeholder="" value="<?=$card->prop_adress;?>">
                </div>
                <div class="form-group">
                    <label class="<? print in_array("city", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Город</label>
                    <input type="text" name="city" class="form-control" placeholder="" value="<?=$card->city;?>">
                </div>
                <div class="form-group">
                    <label class="<? print in_array("zip_code", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Индекс</label>
                    <input type="text" name="zip_code" class="form-control" placeholder="" value="<?=$card->zip_code;?>">
                </div>
                <div class="form-group">
                    <label class="<? print in_array("country", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Страна</label>
                    <input type="text" name="country" class="form-control" placeholder="" value="<?=$card->country;?>">
                </div>
                <div class="form-group">
                    <label class="<? print in_array("phone_mobile", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Мобильный телефон</label>
                    <input type="text" name="phone_mobile" class="form-control" placeholder="" value="<?=$card->phone_mobile;?>">
                </div>
                <div class="form-group">
                    <label class="<? print in_array("phone_home", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Домашний телефон</label>
                    <input type="text" name="phone_home" class="form-control" placeholder="" value="<?=$card->phone_home;?>">
                </div>
                <div class="form-group">
                    <label class="<? print in_array("email", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Email</label>
                    <input type="text" name="email" class="form-control" placeholder="" value="<?=$card->email;?>">
                </div>
                <div class="form-group">
                    <label class="<? print in_array("delivery_address", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Адрес доставки</label>
                    <input type="text" name="delivery_address" class="form-control" placeholder="" value="<?=$card->delivery_address;?>">
                </div>
                <div class="form-group">
                    <label class="<? print in_array("delivery_city", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Город доставки</label>
                    <input type="text" name="delivery_city" class="form-control" placeholder="" value="<?=$card->delivery_city;?>">
                </div>
                <div class="form-group">
                    <label class="<? print in_array("delivery_zip_code", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Индекс доставки</label>
                    <input type="text" name="delivery_zip_code" class="form-control" placeholder="" value="<?=$card->delivery_zip_code;?>">
                </div>
                <div class="form-group">
                    <label class="<? print in_array("delivery_country", $errors['card_error']) ? 'text-warning' : 'text-success' ?>">Страна доставки</label>
                    <input type="text" name="delivery_country" class="form-control" placeholder="" value="<?=$card->delivery_country;?>">
                </div>
                <input type="submit"  value="Перезаписать">
            </form>
        </div>
        <div class="col-md-2 col-xs-2 col-lg-2 col-sm-2"></div>
        <div class="col-md-5 col-xs-5 col-lg-5 col-sm-5">
            <label style="font-size: 18px; text-decoration: underline;">Данные пользователя</label>
            <form class="form-horizontal">
                <div class="form-group" style="visibility: hidden;">
                    <label>Имя</label>
                    <input type="test" name="" value="" class="form-control">
                </div>
                <div class="form-group">
                    <label>Имя</label>
                    <input type="test" name="" value="<?=$user->user->name;?>" class="form-control">
                </div>

                <div class="form-group">
                    <label>Фамилия</label>
                    <input type="test" name="" value="<?=$user->user->sername;?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Дата рождения</label>
                    <input type="test" name="" value="<?=$user->user->pasport_born;?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Индекс прописки</label>
                    <input type="test" name="" value="<?=$user->adr_r->index;?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Город прописки</label>
                    <input type="test" name="" value="<?=$user->adr_r->town;?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Улица прописки</label>
                    <input type="test" name="" value="<?=$user->adr_r->street;?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Дом прописки</label>
                    <input type="test" name="" value="<?=$user->adr_r->house;?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Квартира прописки</label>
                    <input type="test" name="" value="<?=$user->adr_r->flat;?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="test" name="" value="<?=$user->user->phone;?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="test" name="" value="<?=$user->user->email;?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Индекс фактически</label>
                    <input type="test" name="" value="<?=$user->adr_f->index;?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Город фактически</label>
                    <input type="test" name="" value="<?=$user->adr_f->town;?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Улица фактически</label>
                    <input type="test" name="" value="<?=$user->adr_f->street;?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Дом фактически</label>
                    <input type="test" name="" value="<?=$user->adr_f->house;?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Квартира фактически</label>
                    <input type="test" name="" value="<?=$user->adr_f->flat;?>" class="form-control">
                </div>
            </form>
        </div>
            </div>
        <div style="margin: 10px auto; width: 300px;">
            <a class="btn btn-sm btn-danger" href="/opera/cards/unapprove/<?=$card->id;?>">Отклонить</a>
            <a class="btn btn-sm btn-primary" href="/opera/cards/approve/<?=$card->id;?>">Принять</a>
            <a class="btn btn-sm btn-success" href="/opera/cards/create_card/<?=$card->id;?>">Принять и отправить</a>
        </div>

    </div>
    <? } ?>
</div>

<script>
    $("tr[data-user_id]").on('click', function(){
        window.location = "/opera/cards/verify/" + $(this).data("user_id");
    })

</script>