<style>
    div#bank_card_real_payment .widget .title {
        height: 37px;
        background: none !important;
        border-bottom: 0px solid #cdcdcd;
        margin-bottom: 21px !important;
    }
    .vk-repost-button {
    margin-left: 68px;
    }
    .social-repost-success button {
        background: #D8D8D8 url(../images/sus-icon.png) no-repeat scroll right center / contain !important;
    }
    .vk-repost-button button {

        display: block;
        border: medium none;
        width: 125px;
        height: 34px;
        font: 500 12px/24px 'Open Sans';
        color: #FFF;
        text-align: left;
        padding-left: 8px;
        background: none repeat scroll 0% 0% #6180A4;
        background-image: url(../images/wt-vk-repost.png);
        background-repeat: no-repeat;
        background-position: center right;
        cursor: pointer;
        border-radius: 4px;
        outline: none;
        float: left;
        margin: 20px 0;
        margin-right: 12px;
        margin-bottom: 30px !important;
    }

    .fb-repost-button button {
        display: block;
        border: medium none;
        width: 125px;
        height: 34px;
        font: 500 12px/24px 'Open Sans';
        color: #FFF;
        text-align: left;
        padding-left: 8px;
        background: none repeat scroll 0% 0% #3D5599;
        cursor: pointer;
        background-image: url(../images/wt-fb-repost.png);
        background-repeat: no-repeat;
        background-position: center right;
        border-radius: 4px;
        outline: none;
        float: left;
        margin-right: 12px !important;
        margin: 20px 0;
        margin-bottom: 30px !important;
    }

    .tw-repost-button button {
        display: block;
        border: medium none;
        width: 125px;
        height: 34px;
        font: 500 12px/24px 'Open Sans';
        color: #FFF;
        text-align: left;
        padding-left: 8px;
        background: none repeat scroll 0% 0% #3AAAE3;
        background-image: url(../images/wt-tw-repost.png);
        background-repeat: no-repeat;
        background-position: center right;
        cursor: pointer;
        border-radius: 4px;
        outline: none;
        float: left;
        margin: 20px 0;
        margin-right: 0;
        margin-bottom: 30px !important;
    }

    div#submit-modal-button button {
        text-align: center;
        display: block;
        border: medium none;
        width: 237px;
        height: 44px;
        font: 500 18px/24px 'Open Sans';
        color: #FFF;
        background: none repeat scroll 0% 0% #3390EE;
        cursor: pointer;
        border-radius: 4px;
        outline: none;
        padding: 0;
        margin: 14px auto;
        margin-bottom: 37px !important;
        clear: both;
        overflow: hidden;
        margin-top: 95px;
    }

    .vk-repost-button button:hover, .fb-repost-button button:hover, .tw-repost-button button:hover{
      opacity: 0.8;

    }

    .text-repost-modal {
            text-align: center;
        padding: 4px 0 12px;
        word-wrap: break-word;
        line-height: 23px;
    }


    div#submit-modal-button button:hover {
        background: none repeat scroll 0% 0% #F75A1F;
        color: #fff;
    }

    .lava_payment .close, .vivod13 {
        position: absolute;
        background: url("../../img/close.png") no-repeat 0 0;
        width: 24px;
        height: 20px;
        background-size: contain;
        right: 10px;
        top: 12px;
        cursor: pointer;
    }
    div#social-repost-withdrawal {
        height: 150px;
        float: none;
    }
    .i_dont_wanna_social {
        float: none;
        text-align: center;
    }
    .i_dont_wanna_social a{
            text-decoration:underline;
    }
    .social-widget-success{
            height: 220px !important;
    }
    
    
</style>

<div class="popup lava_payment" id="wall_post_dialog" style="display: none;">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget" id="social-repost-withdrawal">

        <div class="text-repost-modal"><?=_e('Поделись в соц сетях и получи за каждый пост бонус <b>$20</b>')?><br><span style="font-size:12px"><?=_e('(Вы не должны удалять пост пока не получите прибыль от бонуса)')?></span></div>
        <div class="repost-button">
            <div class="vk-repost-button"><button data-social="vk" onclick="WT_Social_WallPost.shareToWall(this);return false;"><?=_e('Поделиться VK') ?></button></div>
            <div class="fb-repost-button"><button data-social="fb" onclick="WT_Social_WallPost.shareToWall(this);return false;"><?=_e('Поделиться FB') ?></button></div>
        </div>
    </div>
	<div class="i_dont_wanna_social"><a href="javascript:WT_Social_WallPost.succesCallback(false,0);"><?=_e('Я не хочу делиться') ?></a></div>
</div>



<script>
    
    
    
    
    var fb = {

        _instance: false,
        name: 'fb',
        wallpost: function(successCallback){

            var obj =
            {
                method: 'feed',
                name: '<?=_e('Взаимное кредитование Webtransfer!') ?>',
                caption: '',
                description: (
                    '<?if(!empty($loan)){ ?><?=_e('Когда мне нужно я занимаю деньги в Cоциальной кредитной сети и получаю их на Webtransfer VISA Card в течении часа. Бонус $50 всем участникам.') ?><? } else { ?><?=_e('Выдаю займы участникам соц сетей на срок до 30 дней под 45% и зарабатываю до $5000 в месяц. Всем моим партнерам бонус $50!') ?><? } ?>'
                ),
                link: '<?=site_url("partner/id-".$this->user->id_user) ?>',
                picture: '<?if($this->lang->lang()=='ru'){?>https://webtransfer.com/images/banner_new_ru.jpg<?} else {?>https://webtransfer.com/images/banner_new_en.jpg<?}?>'
            };

            function callback(response) {
                if(response && response.post_id) {
                    successCallback(fb.name, response.post_id);
                    console.log('Post was published.');
                    console.log(response);
                    console.log(response.post_id);
                } else {
                    console.log('post not published');
                }
            }
            FB.ui(obj, callback);
            console.log('wallpost fb');
        },

        init: function(){
            if(this.instance)
                return;
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : '526472357444368',
                    xfbml      : true,
                    version    : 'v2.5'
                });
            };
            

            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

            this._instance = true;
        }
    };
    


var vk = {

        _instance: false,

        name: 'vk',
        wallpost: function(successCallback){

            VK.Auth.login(
                function(response) {
                    if (response.session) {
                        console.log(1);
                        VK.api('wall.post', {
                            message: '<?if(!empty($loan)){ ?><?=_e('Когда мне нужно я занимаю деньги в Cоциальной кредитной сети и получаю их на Webtransfer VISA Card в течении часа. \n \n Жми на ссылку и получи  бонус $50 >>\n') ?><? } else { ?><?=_e('Выдаю займы участникам соц сетей на срок до 30 дней под 45% и зарабатываю до $5000 в месяц. \n Всем моим партнерам бонус $50! \n \n Жми ссылку >>\n ') ?><? } ?><?=site_url("partner/id-".$this->user->id_user) ?>',
                            attachments : '<?if($this->lang->lang()=='ru'){?>photo-55660968_388457285 <? } else { ?>photo-55660968_388457274<? } ?>, <?=site_url("partner/id-".$this->user->id_user) ?>' // <type><owner_id>_<media_id>
                        }, function (data) {
                                if(data)
                                        if(data.response)
                                                if(data.response.post_id)
                                                        successCallback(vk.name, data.response.post_id);
                                                console.log(data.response.post_id);
                        });
                        /* Пользователь успешно авторизовался */
                        if (response.settings) {
                            console.log(2);
                            /* Выбранные настройки доступа пользователя, если они были запрошены */
                        }
                    } else {
                        console.log(3);
                        /* Пользователь нажал кнопку Отмена в окне авторизации */
                    }
                },
                VK.access.WALL
            );

            console.log('wallpost vk');
        },

        init: function(){
            if(this.instance)
                return;

            VK.init({
                apiId: 4352044 // id созданного вами приложения вконтакте
            });

            this._instance = true;
        }
    };

      vk.init();
      fb.init();
      <? if ( !empty($form_id)) {?>
        $(function(){
          $('#<?=$form_id?>').append('<input type="hidden" id="post_vk" name="post_vk" value="0">');
          $('#<?=$form_id?>').append('<input type="hidden" id="post_fb" name="post_fb" value="0">');
        });
      <? } ?>
      
      

var WT_Social_WallPost = (function () {

  var is_submitted = false;  
  var callback_func = null;
  
  


  return {

    post_vk: 0,
    post_fb: 0,

    // Публичный метод класса.  
    init: function() {

      
    },
    
    reinit: function(){
        WT_Social_WallPost.post_fb = 0;
        WT_Social_WallPost.post_vk = 0;
        is_submitted = false;
        $('.vk-repost-button').removeClass('social-repost-success');
        $('.fb-repost-button').removeClass('social-repost-success');
        $('#post_vk').val(0);
        $('#post_vk').val(0);
        $('#social-repost-withdrawal').removeClass('social-widget-success');
        $('#submit-modal-button').remove();
        $(".i_dont_wanna_social").show();
        $('#wall_post_dialog').hide();
    },
    
     succesCallback: function(social_type, post_id) {
	$('#social-repost-withdrawal').addClass('social-widget-success');
	if (!$("#social-repost-withdrawal").children('div').hasClass('submit-modal-button-class'))
               $( "#social-repost-withdrawal" ).append( "<div id='submit-modal-button' class='submit-modal-button-class'><button id='social_out_submit' type='submit' onclick='WT_Social_WallPost.social_out_submit()'><?= _e('Отправить') ?></button></div>" );
	$(".i_dont_wanna_social").hide();
        $('.'+social_type+'-repost-button').addClass('social-repost-success');
        $('#post_'+social_type).val(post_id);
        if ( social_type == 'vk')
            WT_Social_WallPost.post_vk = post_id;
        else if (social_type == 'fb')
            WT_Social_WallPost.post_fb = post_id;
     },
    
    showDialog: function(callback){
            if ( is_submitted )
                return false;
            $('#wall_post_dialog').show();
            callback_func = callback;
            return true;
    },    
    
    shareToWall: function(data){
        window[$(data).data('social')].wallpost(WT_Social_WallPost.succesCallback)
        //WT_Social_WallPost.succesCallback(vk.name, 555);
    },
            
    social_out_submit: function(){
        
        console.log("social_submit");
        is_submitted = true;
        if ( callback_func !== null)
            callback_func();
}
            
    
  }
})();


</script>


    
