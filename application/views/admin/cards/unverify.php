<link rel="stylesheet" href="/css/bootstrap.min.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
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
}else{
    ?>

    <div class="widget" style="margin-top:10px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">

        </div>
        <h3 class="text-center text-success">Заявка успешно cброшена</h3>
        <br>
        <div style="margin: 10px auto; width: 300px;">
            <a class="btn btn-sm btn-success" href="/opera/cards/verify/<?=$card->id;?>">Проверить</a>
        </div>

    </div>

<?
}
?>