<style>
    .currentStripeText
    {
        height: 42px;
        padding-top: 0;
        margin-top: -3px;
        font-size: 22px
    }
    .currentStripeText .stepNamePos{
        font-size: 20px;
        line-height: 18px;
        margin-bottom: 5px;
        height: 42px;
    }
</style>
<div id="paymentStripe" >
    <div class="step_1" >
        <div class="currentStripe">
           <div class="currentStripeRightCircle">&nbsp;</div>
           <div class="currentStripeLeftCircle">&nbsp;</div>
           <div class="currentStripeText">
              <div class="stepNamePos">
                 <?=_e('отдаю');?>
              </div>
           </div>
        </div>
        <div class="futureStripe">
           <div class="futureStripeRightCircle">
              <div class="stepNamePos"><?=_e('получаю');?></div>
           </div>
        </div>
        <div class="futureStripe">
           <div class="futureStripeRightCircle">
              <div class="stepNamePos">
                 <?=_e('подтверждение');?>
              </div>
           </div>
        </div>
    </div>
    
<!--    ################################## -->

    <div class="step_2">
        <div class="pastStripe">
           <div class="pastStripeLeftCircle">
              <div class="stepNamePos">
                 <?=_e('отдаю');?>
              </div>
              <div class="first_step_summ overall_step_summ"></div>
           </div>
        </div>
    
        <div class="currentStripe">
           <div class="currentStripeRightCircle">&nbsp;</div>
           <div class="currentStripeLeftCircle">&nbsp;</div>
           <div class="currentStripeText">
              <div class="stepNamePos">
                 <?=_e('получаю');?>
              </div>
           </div>
        </div>
        
        <div class="futureStripe">
           <div class="futureStripeRightCircle">
              <div class="stepNamePos">
                 <?=_e('подтверждение');?>
              </div>
           </div>
        </div>
    </div>
    
<!--    ################################## -->

    <div class="step_3">
        <div class="pastStripe">
           <div class="pastStripeLeftCircle">
              <div class="stepNamePos">
                 <?=_e('отдаю');?>
                 <div class="first_step_summ overall_step_summ"></div>
              </div>
           </div>
        </div>
        <div class="pastStripe">
           <div class="pastStripeLeftCircle">
              <div class="stepNamePos">
                 <?=_e('получаю');?>
                 <div class="second_step_summ overall_step_summ"></div>
              </div>
           </div>
        </div>
    
        <div class="currentStripe">
           <div class="currentStripeRightCircle">&nbsp;</div>
           <div class="currentStripeLeftCircle">&nbsp;</div>
           <div class="currentStripeText">
              <div class="stepNamePos">
                 <?=_e('подтверждение');?>
              </div>
           </div>
        </div>
    </div>
    <div class="clear"></div>
</div>