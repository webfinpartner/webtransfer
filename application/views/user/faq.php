<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<script language="javascript">
$(function(){
	$('.topic_id a').click(function(){
	id=$(this).attr('name');
	topic=$('#'+id);
	if(topic.css('display')=='none')
		topic.slideDown(function(){topic.css('display','inline-block')});
		else topic.slideUp();

	})
})
</script>
<?php if(!empty($faqs)){
$i=0;
foreach($faqs as $faq){
$i++;
?>
<div class="topic_id" > <a name='topic_content_<?=$i?>'><?=$faq->question?></a> </div><div class="topic_content" id="topic_content_<?=$i?>" ><?=$faq->answer?></div>
<?
}}?>
