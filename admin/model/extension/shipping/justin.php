<?php 
class ModelExtensionShippingJustin extends Model {

    public function createTableDepartments(){

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "justin_departments` (
                     `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                     `branch` TEXT,
                     `number` INT(11),
                     `uuid` VARCHAR(255),
                     `depart_descr` TEXT,
                     `description` TEXT,
                     `region_uuid` VARCHAR(255),
                     `region_name` TEXT,
                     `city_uuid` VARCHAR(255),
                     `city_name` TEXT,
                     `street_uuid` VARCHAR(255),
                     `street_name` TEXT,
                     `street_number` VARCHAR(255),
                     `weight_limit` INT(5),
                     `address` TEXT,
                     `lat` VARCHAR(255),
                     `lng` VARCHAR(255),
                     `type_value` INT(2),
                     `type_descr` VARCHAR(255)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

    }

    public function createTableCities(){

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "justin_cities` (
                     `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                      `uuid` VARCHAR(255),
                      `name_ua` VARCHAR(255),
                      `name_ru` VARCHAR(255)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

    }

    public function createTableOrderDepartment(){

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "justin_department_to_order` (
                  `order_id` INT(11),
                  `number_department` INT(11),
                  `uuid_department` VARCHAR(255)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

    }

    public function createTableRequestDelivery(){

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "justin_request_delivery_info` (
                  `number` int(11) NOT NULL,
                  `date` date NOT NULL,
                  `sender_city_id` varchar(255) NOT NULL,
                  `sender_company` varchar(255) NOT NULL,
                  `sender_contact` varchar(255) NOT NULL,
                  `sender_phone` varchar(100) NOT NULL,
                  `sender_pick_up_address` text NOT NULL,
                  `pick_up_is_required` int(1) NOT NULL,
                  `sender_branch` varchar(255) NOT NULL,
                  `receiver` varchar(255) NOT NULL,
                  `receiver_contact` varchar(255) NOT NULL,
                  `receiver_phone` varchar(100) NOT NULL,
                  `count_cargo_places` int(11) NOT NULL,
                  `branch` varchar(255) NOT NULL,
                  `weight` varchar(255) NOT NULL,
                  `volume` varchar(255) NOT NULL,
                  `declared_cost` varchar(255) NOT NULL,
                  `delivery_amount` varchar(255) NOT NULL,
                  `redelivery_amount` varchar(255) NOT NULL,
                  `order_amount` varchar(255) NOT NULL,
                  `redelivery_payment_is_required` int(1) NOT NULL,
                  `redelivery_payment_payer` int(1) NOT NULL,
                  `delivery_payment_is_required` int(1) NOT NULL,
                  `delivery_payment_payer` int(1) NOT NULL,
                  `order_payment_is_required` int(1) NOT NULL,
                  `add_description` text NOT NULL,
                  `number_ttn` varchar(255) DEFAULT NULL,
                  `number_pms` varchar(255) DEFAULT NULL
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

    }

    public function getTableRequestDelivery($number){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "justin_request_delivery_info WHERE `number` = '" . (int)$number . "'");
        return $query->row;
    }

    public function upTableRequestDelivery($number, $data){
        $this->db->query("UPDATE " . DB_PREFIX . "justin_request_delivery_info SET `number_ttn` = '" . $this->db->escape($data['ttn']) . "', `number_pms` = '" . $this->db->escape($data['number']) . "' WHERE `number` = '" . (int)$number . "'");
    }

    public function addTableRequestDelivery($data){

        $get_table_row = $this->getTableRequestDelivery((int)$data['number']);
        $command = 'INSERT INTO ';
        $command_end = '';
        if(!empty($get_table_row)){
            $command = 'UPDATE ';
            $command_end = " WHERE `number` = '" . (int)$data['number'] . "'";
        }

        $this->db->query($command . "`" . DB_PREFIX . "justin_request_delivery_info` SET `number` = '" . (int)$data['number'] .
            "', `date` = '" . $this->db->escape($data['date']) .
            "', `sender_city_id` = '" . $this->db->escape($data['sender_city_id']) .
            "', `sender_company` = '" . $this->db->escape($data['sender_company']) .
            "', `sender_contact` = '" . $this->db->escape($data['sender_contact']) .
            "', `sender_phone` = '" . $this->db->escape($data['sender_phone']) .
            "', `sender_pick_up_address` = '" . $this->db->escape($data['sender_pick_up_address']) .
            "', `pick_up_is_required` = '" . (int)$data['pick_up_is_required'] .
            "', `sender_branch` = '" . $this->db->escape($data['sender_branch']) .
            "', `receiver` = '" . $this->db->escape($data['receiver']) .
            "', `receiver_contact` = '" . $this->db->escape($data['receiver_contact']) .
            "', `receiver_phone` = '" . $this->db->escape($data['receiver_phone']) .
            "', `count_cargo_places` = '" . (int)$data['count_cargo_places'] .
            "', `branch` = '" . $this->db->escape($data['branch']) .
            "', `weight` = '" . $this->db->escape($data['weight']) .
            "', `volume` = '" . $this->db->escape($data['volume']) .
            "', `declared_cost` = '" . $this->db->escape($data['declared_cost']) .
            "', `delivery_amount` = '" . $this->db->escape(0) .
            "', `redelivery_amount` = '" . $this->db->escape(0) .
            "', `order_amount` = '" . $this->db->escape($data['order_amount']) .
            "', `redelivery_payment_is_required` = '" . (int)1 .
            "', `redelivery_payment_payer` = '" . (int)$data['redelivery_payment_payer'] .
            "', `delivery_payment_is_required` = '" . (int)1 .
            "', `delivery_payment_payer` = '" . (int)$data['delivery_payment_payer'] .
            "', `order_payment_is_required` = '" . (int)$data['order_payment_is_required'] .
            "', `add_description` = '" . $this->db->escape($data['add_description']) .
            "'" . $command_end);
    }

    public function addTableDepartments($data){
        $this->db->query("INSERT INTO `" . DB_PREFIX . "justin_departments` SET `branch` = '" . $this->db->escape($data['branch']) .
            "', `number` = '" . (int)$data['departNumber'] .
            "', `uuid` = '" . $this->db->escape($data['Depart']['uuid']) .
            "', `depart_descr` = '" . $this->db->escape($data['Depart']['descr']) .
            "', `description` = '" . $this->db->escape($data['descr']) .
            "', `region_uuid` = '" . $this->db->escape($data['region']['uuid']) .
            "', `region_name` = '" . $this->db->escape($data['region']['descr']) .
            "', `city_uuid` = '" . $this->db->escape($data['city']['uuid']) .
            "', `city_name` = '" . $this->db->escape($data['city']['descr']) .
            "', `street_uuid` = '" . $this->db->escape($data['street']['uuid']) .
            "', `street_name` = '" . $this->db->escape($data['street']['descr']) .
            "', `street_number` = '" . $this->db->escape($data['houseNumber']) .
            "', `weight_limit` = '" . (int)$data['weight_limit'] .
            "', `address` = '" . $this->db->escape($data['address']) .
            "', `lat` = '" . $this->db->escape($data['lat']) .
            "', `lng` = '" . $this->db->escape($data['lng']) .
            "', `type_value` = '" . (int)$data['TypeDepart']['value'] .
            "', `type_descr` = '" . $this->db->escape($data['TypeDepart']['enum']) .
            "'");
    }

    public function getTableAllDepartments(){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "justin_departments");

        $return = array();
        foreach($query->rows as $row){
            foreach($row as $k=>$b){
                $return[$row['uuid']][$k] = $b;
            }
        }

        return $return;
    }

    public function getTableDepartments($uuid, $number){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "justin_departments WHERE `uuid` = '" . $this->db->escape($uuid) . "' AND `number` = '" . (int)$number . "'");
        return $query->row;
    }

    public function addTableCities($data, $language){

        if($language == 'RU'){
            $this->db->query("INSERT INTO `" . DB_PREFIX . "justin_cities` SET `uuid` = '" . $this->db->escape($data['uuid']) .
                "', `name_ua` = '', `name_ru` = '" . $this->db->escape($data['descr']) . "'");
        }elseif($language == 'UA'){
            $this->db->query("UPDATE `" . DB_PREFIX . "justin_cities` SET `name_ua` = '" . $this->db->escape($data['descr']) . "' WHERE `uuid` = '" . $this->db->escape($data['uuid']) . "'");
        }


    }

    public function getTableAllCities(){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "justin_cities");

        $return = array();
        foreach($query->rows as $row){
            foreach($row as $k=>$b){
                $return[$row['uuid']][$k] = $b;
            }
        }

        return $return;
    }

    public function getTableOrderDepartment($order_id){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "justin_department_to_order WHERE `order_id` = '" . (int)$order_id . "'");
        return $query->row;
    }

    public function editTableOrderDepartment($order_id, $number, $uuid){
        $this->db->query("UPDATE " . DB_PREFIX . "justin_department_to_order SET `number_department` = '" . (int)$number . "', `uuid_department` = '" . $this->db->escape($uuid) . "' WHERE `order_id` = '" . (int)$order_id . "'");
    }

    public function getAllCity(){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "justin_cities");
        return $query->rows;
    }

}
?>