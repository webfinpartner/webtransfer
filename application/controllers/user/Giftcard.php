<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Accaunt_base')){ require APPPATH.'libraries/Accaunt.php';}

class Giftcard extends Accaunt_base {
    public function __construct() {
        $login = parent::__construct();
        $user_id = $this->accaunt->get_user_id();

        viewData()->banner_menu = "profil";

        viewData()->secondary_menu = "profile";
        //viewData()->secondary_menu = "giftcard";
        //viewData()->page_name = null;
        $this->content->clientType = 1;

        require_once APPPATH.'controllers/user/Security.php';
        viewData()->security = Security::getProtectType($user_id);
        $this->base_model->generate_page_hash();
        
        $this->load->model('Giftcard_model', 'giftcard');
    }
    
    
    public function create(){
        
        
        
        $data = new stdClass();
        $data->giftcards =   $this->giftcard->get_list();

        
        
        $user_id = get_current_user_id();
        //var_dump( $_POST );
        if ( !empty( $_POST['payment_account'] )){

            /*if (  (int)$this->input->post('agree') !== 1 ){
                accaunt_message($data, _e('Необходимо согласиться на условия'), 'error');
                redirect('giftcard/create');
            } */           
            
            $amount = (int)$this->input->post('amount');
            $card_id = $this->input->post('payment_account');
            $summ = 50;
            
            if ( $amount == 0){
                accaunt_message($data, _e('Не выбрано количество'), 'error');
                redirect('account/giftcard');
            }
            
            
            $this->load->model('card_model', 'card');
            $card = $this->card->getUserCard($card_id);
            if ( empty($card) ){
                accaunt_message($data, _e('Карта не найдена'), 'error');
                redirect('account/giftcard');
            }
           
            $balance = $this->card->getCardBalance($card);
            if ( $amount * $summ > $balance ){
                accaunt_message($data, _e('Недосточно средств для выполнения данной операции'), 'error');
                redirect('account/giftcard');
            }

            if ( $amount > 0 ){
                $id = time().rand(1,10000);
                $response = $this->card->purchaseMoney(
                        ['id' => $id, 
                        'card_id' => $card_id, 
                        'user_id' => $user_id, 
                        'summa' => $summ*$amount, 
                        'desc' => 'Webtransfer Giftcard Purchase'
                 ], Card_transactions_model::CARD_PAY_GIFTCARD, $id);                
                      
                if(false !== $response) {
                    for( $i = 0; $i < $amount; $i++)
                        $this->giftcard->add($user_id, $summ );
                } else {
                    accaunt_message($data, _e('Ошибка списания средств: '.$response), 'error');
                    redirect('account/giftcard');
                }
                accaunt_message($data, _e('Карты успешно добавлены'));
                redirect('account/giftcard');
                
            }

            
        }
        
        viewData()->page_name = __FUNCTION__;
        $this->content->user_view("giftcard_create", $data, _e('Заказать'));        
    }
    
    
    public function terms(){
        viewData()->page_name = __FUNCTION__;
        $this->content->user_view("giftcard_info", $data, _e('Инфо'));
    }
    
    public function lst(){
        $data = new stdClass();
        $data->giftcards =   $this->giftcard->get_list();
        
        viewData()->page_name = __FUNCTION__;
        $this->content->user_view("giftcard_list", $data, _e('Список'));
        
        
    }
    
    
    public function activate($id){
        //$id = (int)$this->input->post('id');
        $user_id = get_current_user_id();
        $giftcard =   $this->giftcard->get( $id, $user_id );
        if ( empty($giftcard)){
            accaunt_message($data, _e('Такой карты не существует'));
            redirect('account/giftcard');        
        }
        
         if ( $giftcard->status != 0){
            accaunt_message($data, _e('Карта уже использована'));
            redirect('account/giftcard');        
         }
        
         $this->load->model('Transactions_model', 'transaction');
         $trid = $this->transactions->addPay($user_id, $giftcard->nominal, 331, $id, 'wtgift', Base_model::TRANSACTION_STATUS_RECEIVED, 6, 'Пополнение GIFT card #'.$id);     
        
         $this->giftcard->set_status($id, 1);
         accaunt_message($data, _e('Карта успешно использована для пополнения'));
         redirect('account/giftcard');
        
    }

    public function present($id, $to_user_id){
        $from_user_id = get_current_user_id();
        $to_user_id = (int)trim($to_user_id);
         if ( empty($to_user_id) ){
            accaunt_message($data, _e('Не указан пользователь'));
            redirect('account/giftcard');        
         }                
        
       $giftcard =   $this->giftcard->get( $id, $from_user_id );
        if ( empty($giftcard)){
            accaunt_message($data, _e('Такой карты не существует'));
            redirect('account/giftcard');        
        }
        
         if ( $giftcard->status != 0){
            accaunt_message($data, _e('Карта уже использована'));
            redirect('account/giftcard');        
         }        
         
         $this->giftcard->present($id, $from_user_id, $to_user_id);
         $this->load->model('Inbox_model','inbox');
         $this->inbox->writeInbox($from_user_id, $to_user_id, _e('Вам подарили Gift Card пользователь ').$from_user_id );
         
        
        accaunt_message($data, _e('Карта успешно подарена'));
          redirect('account/giftcard');
        
    }
    
    
}