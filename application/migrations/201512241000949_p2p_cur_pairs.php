<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Card_refact extends CI_Migration {

    public function up()
    {
            $this->dbforge->add_field(array(
                    'id' => array(
                            'id' => 'INT',                            
                            'unsigned' => TRUE,
                            'auto_increment' => TRUE,
                    ),
                    'date' => array(
                            'type' => 'DATETIME',                            
                    ),
//                    'period' => array(
//                        'type' => 'INT',
//                    ),                    
//                    'payment_system_id' => array(
//                        'type' => 'INT',
//                    ),
//                    'currency_id' => array(
//                        'type' => 'INT',
//                    ),
//                    'data' => array(
//                        'type' => 'TEXT',
//                    ),
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('module_p2p_pairs_statistic');
    }

    public function down()
    {
            $this->dbforge->drop_table('module_p2p_pairs_statistic');
    }
}