<?php
    class ExamplePaymentGateway {
        public $checkout_id,$checkout;
        public $name,$commission=false;
        public $config=[],$lang=[],$page_type = "in-page",$callback_type="server-sided";
        public $payform=false;

        function __construct(){
            $this->config     = Modules::Config("Payment",__CLASS__);
            $this->lang       = Modules::Lang("Payment",__CLASS__);
            $this->name       = __CLASS__;
            $this->payform   = __DIR__.DS."pages".DS."payform";
        }

        public function get_auth_token(){
            $syskey = Config::get("crypt/system");
            $token  = md5(Crypt::encode("ExamplePaymentGateway-Auth-Token=".$syskey,$syskey));
            return $token;
        }

        public function set_checkout($checkout){
            $this->checkout_id = $checkout["id"];
            $this->checkout    = $checkout;
        }

        public function commission_fee_calculator($amount){
            $rate = $this->get_commission_rate();
            if(!$rate) return 0;
            $calculate = Money::get_discount_amount($amount,$rate);
            return $calculate;
        }


        public function get_commission_rate(){
            //return $this->config["settings"]["commission_rate"];
            return false;
        }

        public function cid_convert_code($id=0){
            Helper::Load("Money");
            $currency   = Money::Currency($id);
            if($currency) return $currency["code"];
            return false;
        }

        public function get_ip(){
            return UserManager::GetIP();
        }


        public function payment_result(){

            $checkout_id    = 12345678;
            $error          = false;
            $txn_id         = 0;
            

            if($error)
                return [
                    'status' => "ERROR",
                    'status_msg' => "",
                ];


            $checkout           = Basket::get_checkout($checkout_id);

            if(!$checkout)
                return [
                    'status' => "ERROR",
                    'status_msg' => Bootstrap::$lang->get("errors/error6",Config::get("general/local")),
                ];


            $this->set_checkout($checkout);

            Basket::set_checkout($this->checkout_id,['status' => "paid"]);

            return [
                'status' => "SUCCESS",
                'checkout'    => $checkout,
                'status_msg' => '',
            ];

        }

    }