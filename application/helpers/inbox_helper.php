<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function viewMessage($text,$inbox=false)
{
	switch($text)
	{
		case 'ready-give-credit':return !$inbox? _e("Предложение на вашу заявку о кредите"):_e("Отправленное  предложение на выдачу кредита");
		case 'ready-take-credit':return !$inbox? _e("Предложение на вашу заявку о выдаче кредита"):_e("Отправленное  предложение на получение кредита");

	default : return $text;
	}
}

function viewStatusMessage($status,$inbox=false)
{
	switch($status)
	{
		case '1': return !$inbox?_e('Новое сообщение'):_e('Не прочитано');
		case '2': return !$inbox?_e('На рассмотрении'):_e('Прочитано');
		case '3': return !$inbox?_e('Отклонен'):_e('Отклонен');
		case '4': return !$inbox?_e('Принято'):_e('Принято');

		default : return $status;
	}
}

function viewStatusMess($status)
{
	switch($status)
	{
		case '1': return _e('Новое сообщение');
		case '2': return _e('Прочитано');

		default : return $status;
	}
}


function checkNewMessage($item,$inbox=false)
{
	if($item->cause == 'news' and $item->status == 2) return '';
	return (!$inbox)? ($item->recipient_view==1?"new":""):($item->sender_view==1?"new":"");
}
