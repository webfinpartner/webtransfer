<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_balances_model extends CI_Model {
    public $tableName = "user_balances";

    public function __construct(){
        parent::__construct();
    }

    public function calculateBalance($rate) {
        //Собственные средства = (1,1+1,3+1,4) - (2,1+2,4)
        return (
            $rate['payment_account_by_bonus'][6]
            + $rate['investments_garant_by_bonus'][6]
//            + $rate['investments_garant_bonuses']
            + $rate['my_investments_garant_percent_by_bonus'][6]
        ) - (
            $rate['loans_by_bonus'][6]
            + $rate['total_arbitrage_by_bonus'][6]
        );
    }
    public function getBalances($id_user, $start = null, $end = null) {
        $start = (null == $start) ? date("Y-m-")."01" : $start;
        $end = (null == $end) ? date("Y-m-d") : $end;
//        $my_bal_res = array(); // отключаем подсчет своего баланса
        $partner_bal_res = array();
//        $my_bal = $this->db->where('date >=', $start)
//                 ->where('date <=', $end)
//                 ->where("id_user", $id_user)
//                 ->select("*, DAY(date) day")
////                 ->order_by('date desc')
//                 ->get($this->tableName)
//                 ->result();
//        foreach ($my_bal as $row)
//            $my_bal_res[$row->day] = $row->balance;

        $my_partner = $this->db->where('date >=', $start)
                 ->where('date <=', $end)
                 ->where("parent", $id_user)
                 ->select("DAY(date) day, SUM(balance) balance_sum")
//                 ->order_by('date desc')
                 ->group_by("date")
                 ->get($this->tableName)
                 ->result();
        foreach ($my_partner as $row)
            $partner_bal_res[$row->day] = $row->balance_sum;
//        print_r($partner_bal_res);
//        print_r($my_bal_res);
//        print_r($my_bal);
//        print_r($my_partner);

        $bal = array();
        for ($day = 1; $day <= (int) date("d", strtotime($end)); $day++) { //(int) date("d")

//            $my_sum_bal = 0;
//            for ($my_bal_day = 1; $my_bal_day <= $day; $my_bal_day++) {
//                $my_sum_bal += (isset($my_bal_res[$my_bal_day])) ? $my_bal_res[$my_bal_day] : 0;
//            }
//echo "day = $day".PHP_EOL;
//echo "my_sum_bal = $my_sum_bal".PHP_EOL;
            $partner_sum_bal = 0;
            for ($partner_bal_day = 1; $partner_bal_day <= $day; $partner_bal_day++) {
                $partner_sum_bal += (isset($partner_bal_res[$partner_bal_day])) ? $partner_bal_res[$partner_bal_day] : 0;
            }
//echo "partner_sum_bal = $partner_sum_bal".PHP_EOL;
            $bal[$day] = $partner_sum_bal/$day; //$my_sum_bal/$day +
        }
        return $bal;
    }

    public function addBalance($user) {
        try {$this->db->insert($this->tableName, $user);} catch (Exception $exc) {echo "error insert to DB for user: $user->id_user for date:$user->date";}
    }

    public function updateBalance($user) {
        $this->db->update($this->tableName, $user, "id_user = $user->id_user AND DATE(date) = DATE(NOW())", 1);

    }

    public function checkBalance($id_user, $date) {
        return $this->db->get_where($this->tableName, "id_user = $id_user AND DATE(date) = DATE('$date')")->row();
    }

    public function countLavel($user) {
        $start = date("Y-m-d", strtotime("first day of previous month"));
        $end = date("Y-m-d", strtotime("last day of previous month"));
        $avg_bal = $this->getBalances($user->id_user, $start, $end);
//        print_r($avg_bal);
        $avg_bal = $avg_bal[(int) date("d", strtotime($end))];
//        var_dump($avg_bal);
        // 1 - 50% >= 0, 2 - 65% > 50 000, 3 - 70% > 200 000, 4 - 75% > 1 000 000
        $partnerPlansSum = config_item('partner-plan-sum');
        $lvl = 1;
        foreach ($partnerPlansSum as $plan => $value) {
            if($avg_bal > $value)
                $lvl = $plan;
        }

        echo "$user->id_user уровень = $lvl (средний баланс = $avg_bal)</br>".PHP_EOL;
        return $lvl;
    }

    public function getBalances2Days($id_user, $start = null, $end = null) {
        $start = (null == $start) ? date("Y-m-")."01" : $start;
        $end = (null == $end) ? date("Y-m-d") : $end;
//        $my_bal_res = array();
        $partner_bal_res = array();
//        $my_bal = $this->db->where('date >=', $start)
//                 ->where('date <=', $end)
//                 ->where("id_user", $id_user)
//                 ->select("*, DAY(date) day")
////                 ->order_by('date desc')
//                 ->get($this->tableName)
//                 ->result();
//        foreach ($my_bal as $row)
//            $my_bal_res[$row->day] = $row->balance;

        $my_partner = $this->db->where('date >=', $start)
                 ->where('date <=', $end)
                 ->where("parent", $id_user)
                 ->select("DAY(date) day, SUM(balance) balance_sum")
//                 ->order_by('date desc')
                 ->group_by("date")
                 ->get($this->tableName)
                 ->result();
        foreach ($my_partner as $row)
            $partner_bal_res[$row->day] = $row->balance_sum;
//        print_r($partner_bal_res);
//        print_r($my_bal_res);
//        print_r($my_bal);
//        print_r($my_partner);

        $bal = array();
        $day = 31;
//        for ($day = 30; $day <= (int) date("d", strtotime($end)); $day++) { //(int) date("d")

//            $my_sum_bal = 0;
//            for ($my_bal_day = 1; $my_bal_day <= $day; $my_bal_day++) {
//                $my_sum_bal += (isset($my_bal_res[$my_bal_day])) ? $my_bal_res[$my_bal_day] : 0;
//            }
//echo "day = $day".PHP_EOL;
//echo "my_sum_bal = $my_sum_bal".PHP_EOL;
            $partner_sum_bal = 0;
            for ($partner_bal_day = 1; $partner_bal_day <= $day; $partner_bal_day++) {
                $partner_sum_bal += (isset($partner_bal_res[$partner_bal_day])) ? $partner_bal_res[$partner_bal_day] : 0;
            }
//echo "partner_sum_bal = $partner_sum_bal".PHP_EOL;
            $bal[$day] = $partner_sum_bal/2; //$my_sum_bal/2 +
//        }
        return $bal;
    }
}