<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of payout
 *
 * @author esb
 */

class Globalcheck {
    public function verify() {
        if(isset($_POST['offset'])){
            if(0 > $_POST['offset']) show_404();
        }
        if(isset($_POST['per_page'])){
            if(0 > $_POST['per_page']) show_404();
        }
    }
}
