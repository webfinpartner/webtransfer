<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Card_refact extends CI_Migration {

    public function up()
    {
        $fields = array(
            'tel' => array(
                'name' => 'phone_mobile',
                'type' => 'varchar(20)'
            ),
            'country' => array(
                'name' => 'country',
                'type' => 'char(4)'
            ),
            'city' => array(
                'name' => 'city',
                'type' => 'tinytext'
            ),
            'index' => array(
                'name' => 'zip_code',
                'type' => 'tinytext'
            ),
            'address' => array(
                'name' => 'prop_adress',
                'type' => 'text'
            ),
        );
        $this->dbforge->modify_column('cards', $fields);
        $fields = array(
            'holder_name' => array('type' => 'varchar(50) not null'),
            'surname' => array('type' => 'tinytext'),
            'phone_home' => array('type' => 'varchar(20)'),
            'email' => array('type' => 'varchar(25)'),
            'delivery_address' => array('type' => 'text'),
            'delivery_city' => array('type' => 'tinytext'),
            'delivery_zip_code' => array('type' => 'varchar(25)'),
            'delivery_country' => array('type' => 'char(4)')
        );
        $this->dbforge->add_column('cards', $fields);

    }

    public function down()
    {


    }
}