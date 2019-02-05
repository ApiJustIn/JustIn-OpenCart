<?php 
class ModelExtensionShippingJustin extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/justin');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('justin_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('justin_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		// Отримуємо налаштування
        $justin_settings = array();
        $justin_settings['justin_fixed_cost_mode'] = $this->config->get('justin_fixed_cost_mode'); // Режим фіксованої вартості
        $justin_settings['justin_cost'] = $this->config->get('justin_cost'); // Фіксована вартість
        $justin_settings['justin_weight_cost'] = $this->config->get('justin_weight_cost'); // Масив вартості відповідно до ваги
        $justin_settings['justin_weight_class_mode'] = $this->config->get('justin_weight_class_mode'); // Режим розрахунку одиниці ваги
        $justin_settings['justin_weight_class_id'] = $this->config->get('justin_weight_class_id'); // Одиниця ваги, яка використовується в магазині
        $justin_settings['justin_weight_relation'] = $this->config->get('justin_weight_relation'); // Співвідношення одиниці ваги до кг
        $justin_settings['justin_cost_free'] = $this->config->get('justin_cost_free'); // Сума безкоштовної доставки

        $products_cost = 0;
        $products_weight = 0;

        $products = $this->cart->getProducts();
        foreach($products as $product){
            $products_cost += $product['total'];
            // Якщо ввімкнено режим розрахунку одиниці ваги
            if($justin_settings['justin_weight_class_mode']){
                if($product['weight_class_id'] == $justin_settings['justin_weight_class_id']){
                    $products_weight += $product['weight'] / $justin_settings['justin_weight_relation'];
                }else{
                    $products_weight += $product['weight'];
                }
            }else{
                $products_weight += $product['weight'];
            }
        }

        // Якщо режим фіксованої вартості ввімкнено - беремо фіксовану вартість
        if($justin_settings['justin_fixed_cost_mode']){
            $justin_order_cost = $justin_settings['justin_cost'];
        }else{
            foreach($justin_settings['justin_weight_cost'] as $key => $justin_weight_cost){
                $justin_weight_cost_from_before = explode('-', $key);
                if($products_weight > $justin_weight_cost_from_before[0] and $products_weight <= $justin_weight_cost_from_before[1]){
                    $justin_order_cost = $justin_weight_cost;
                    break;
                }
            }
        }

        // Якщо сумма вартості товарів >= суми безкоштовної доставки
        if(!empty($justin_settings['justin_cost_free']) and $justin_settings['justin_cost_free'] != 0){
            if($products_cost >= $justin_settings['justin_cost_free']){
                $justin_order_cost = 0;
            }
        }


		$method_data = array();

        if(isset($justin_order_cost)){
            if ($status) {
                $quote_data = array();

                $quote_data['justin'] = array(
                    'code'         => 'justin.justin',
                    'title'        => $this->language->get('text_description'),
                    'cost'         => $justin_order_cost,
                    'tax_class_id' => $this->config->get('justin_tax_class_id'),
                    'text'         => $this->currency->format($this->tax->calculate($justin_order_cost, $this->config->get('justin_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
                );

                $method_data = array(
                    'code'       => 'justin',
                    'title'      => $this->language->get('text_title'),
                    'quote'      => $quote_data,
                    'sort_order' => $this->config->get('justin_sort_order'),
                    'error'      => false
                );
            }
        }

        return $method_data;
	}

	public function searchCity($city, $language = 'ru'){
	    $column_name = 'name_' . $language;
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "justin_cities WHERE `" . $column_name . "` LIKE '" . $this->db->escape($city) . "'");
        return $query->row;
    }

    public function searchDepartmentsLikeCity($city_uuid){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "justin_departments WHERE `city_uuid` = '" . $this->db->escape($city_uuid) . "'");
        return $query->rows;
    }

    public function getAllDepartments(){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "justin_departments");
        return $query->rows;
    }

    public function addOrderDepartment($order_id, $department_data){
        $this->db->query("INSERT INTO `" . DB_PREFIX . "justin_department_to_order` SET `order_id` = '" . (int)$order_id . "', `number_department` = '" . (int)$department_data['number_department'] . "', `uuid_department` = '" . $this->db->escape($department_data['uuid_department']) . "'");
    }

}