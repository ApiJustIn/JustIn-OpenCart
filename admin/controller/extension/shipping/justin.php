<?php 

require_once('justin_api_class/JustIn_Class.php');

class ControllerExtensionShippingJustin extends Controller {
	private $error = array();

	public function install(){
        $this->load->model('extension/shipping/justin');

        $this->model_extension_shipping_justin->createTableDepartments();
        $this->model_extension_shipping_justin->createTableCities();
        $this->model_extension_shipping_justin->createTableOrderDepartment();
        $this->model_extension_shipping_justin->createTableRequestDelivery();
    }

	public function index() {
		$this->load->language('extension/shipping/justin');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		
		$this->load->model('extension/shipping/justin');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('justin', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
		}

		// Коди мов
        $language_codes = [
            'RU',
            'UA',
            'EN'
        ];

		$data['language_codes'] = array();
		foreach($language_codes as $language_code){
            $data['language_codes'][$language_code] = $this->language->get('text_language_' . $language_code);
        }

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');

		$data['entry_status_test_mode'] = $this->language->get('entry_status_test_mode');
		$data['entry_cost'] = $this->language->get('entry_cost');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_api_login'] = $this->language->get('entry_api_login');
		$data['entry_api_password'] = $this->language->get('entry_api_password');
		$data['entry_language_code'] = $this->language->get('entry_language_code');
		$data['entry_allow_select_departments'] = $this->language->get('entry_allow_select_departments');
		$data['entry_fixed_cost'] = $this->language->get('entry_fixed_cost');
		$data['entry_fixed_cost_mode'] = $this->language->get('entry_fixed_cost_mode');
		$data['entry_cost_free'] = $this->language->get('entry_cost_free');
		$data['entry_weight_class_shop'] = $this->language->get('entry_weight_class_shop');
		$data['entry_weight_relation'] = $this->language->get('entry_weight_relation');

		$data['text_general'] = $this->language->get('text_general');
		$data['text_more_settings'] = $this->language->get('text_more_settings');
		$data['text_branches'] = $this->language->get('text_branches');
		$data['text_refresh_branches'] = $this->language->get('text_refresh_branches');
		$data['text_add_branch'] = $this->language->get('text_add_branch');
		$data['text_up_branch'] = $this->language->get('text_up_branch');
		$data['text_ok_branch'] = $this->language->get('text_ok_branch');
		$data['text_last_refresh'] = $this->language->get('text_last_refresh');
		$data['text_cities'] = $this->language->get('text_cities');
		$data['text_refresh_cities'] = $this->language->get('text_refresh_cities');
		$data['text_cost'] = $this->language->get('text_cost');
		$data['text_title_weight_cost'] = $this->language->get('text_title_weight_cost');
		$data['text_weight_cost_kg'] = $this->language->get('text_weight_cost_kg');
		$data['text_weight_class_mode'] = $this->language->get('text_weight_class_mode');

        $data['help_refresh_branches'] = $this->language->get('help_refresh_branches');
        $data['help_fixed_cost_mode'] = $this->language->get('help_fixed_cost_mode');
        $data['help_cost_free'] = $this->language->get('help_cost_free');
        $data['help_weight_class_mode'] = $this->language->get('help_weight_class_mode');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['token'] = $this->session->data['token'];

        $data['last_refresh_departments'] = $this->config->get('justin_refresh_departments');
        $data['last_refresh_cities'] = $this->config->get('justin_refresh_cities');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/justin', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/justin', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);

		// Масив розподілу ваги
        $data['justin_weight'] = array(
            '0-0.1',
            '0.1-1',
            '1-2',
            '2-5',
            '5-10',
            '10-15',
            '15-30'
        );

        // Класи одиниці ваги
        $this->load->model('localisation/weight_class');
        $data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['justin_cost'])) {
			$data['justin_cost'] = $this->request->post['justin_cost'];
		} else {
			$data['justin_cost'] = $this->config->get('justin_cost');
		}
        if (isset($this->request->post['justin_fixed_cost_mode'])) {
            $data['justin_fixed_cost_mode'] = $this->request->post['justin_fixed_cost_mode'];
        } else {
            $data['justin_fixed_cost_mode'] = $this->config->get('justin_fixed_cost_mode');
        }
        if (isset($this->request->post['justin_weight_cost'])) {
            $data['justin_weight_cost'] = $this->request->post['justin_weight_cost'];
        } else {
            $data['justin_weight_cost'] = $this->config->get('justin_weight_cost');
        }
        if (isset($this->request->post['justin_cost_free'])) {
            $data['justin_cost_free'] = $this->request->post['justin_cost_free'];
        } else {
            $data['justin_cost_free'] = $this->config->get('justin_cost_free');
        }
        if (isset($this->request->post['justin_weight_class_id'])) {
            $data['justin_weight_class_id'] = $this->request->post['justin_weight_class_id'];
        } else {
            $data['justin_weight_class_id'] = $this->config->get('justin_weight_class_id');
        }
        if (isset($this->request->post['justin_weight_relation'])) {
            $data['justin_weight_relation'] = $this->request->post['justin_weight_relation'];
        } else {
            $data['justin_weight_relation'] = $this->config->get('justin_weight_relation');
        }

		if (isset($this->request->post['justin_tax_class_id'])) {
			$data['justin_tax_class_id'] = $this->request->post['justin_tax_class_id'];
		} else {
			$data['justin_tax_class_id'] = $this->config->get('justin_tax_class_id');
		}
        if (isset($this->request->post['justin_weight_class_mode'])) {
            $data['justin_weight_class_mode'] = $this->request->post['justin_weight_class_mode'];
        } else {
            $data['justin_weight_class_mode'] = $this->config->get('justin_weight_class_mode');
        }

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['justin_geo_zone_id'])) {
			$data['justin_geo_zone_id'] = $this->request->post['justin_geo_zone_id'];
		} else {
			$data['justin_geo_zone_id'] = $this->config->get('justin_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['justin_status'])) {
			$data['justin_status'] = $this->request->post['justin_status'];
		} else {
			$data['justin_status'] = $this->config->get('justin_status');
		}

		if (isset($this->request->post['justin_sort_order'])) {
			$data['justin_sort_order'] = $this->request->post['justin_sort_order'];
		} else {
			$data['justin_sort_order'] = $this->config->get('justin_sort_order');
		}

        if (isset($this->request->post['justin_api_login'])) {
            $data['justin_api_login'] = $this->request->post['justin_api_login'];
        } else {
            $data['justin_api_login'] = $this->config->get('justin_api_login');
        }

        if (isset($this->request->post['justin_api_password'])) {
            $data['justin_api_password'] = $this->request->post['justin_api_password'];
        } else {
            $data['justin_api_password'] = $this->config->get('justin_api_password');
        }
        if (isset($this->request->post['justin_test_mode'])) {
            $data['justin_test_mode'] = $this->request->post['justin_test_mode'];
        } else {
            $data['justin_test_mode'] = $this->config->get('justin_test_mode');
        }
        if (isset($this->request->post['justin_language_code'])) {
            $data['justin_language_code'] = $this->request->post['justin_language_code'];
        } else {
            $data['justin_language_code'] = $this->config->get('justin_language_code');
        }
        if (isset($this->request->post['justin_allow_select_departments'])) {
            $data['justin_allow_select_departments'] = $this->request->post['justin_allow_select_departments'];
        } else {
            $data['justin_allow_select_departments'] = $this->config->get('justin_allow_select_departments');
        }

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/justin', $data));
	}

	public function refreshdepartments(){

	    if(isset($this->request->post['work'])){

            $this->load->language('extension/shipping/justin');
            $this->load->model('setting/setting');
            $this->load->model('extension/shipping/justin');

            $return = array();
	        $error = '';

	        $login = $this->config->get('justin_api_login');
	        $password = $this->config->get('justin_api_password');
	        $language_code = $this->config->get('justin_language_code');
	        $test_mode = $this->config->get('justin_test_mode');

	        if(!empty($login) and !empty($password)){

	            // Встановлення підключення до API через клас
                $justin = new JustInApi($login,$password, $language_code, $test_mode);
                // Отримання інформації про відділення по API
                $departments = $justin->get_departments();
                // Отримання інформації про вже записані відділення в базу
                $shop_departments = $this->model_extension_shipping_justin->getTableAllDepartments();
                // Запис кожного відділення в базу
                if(!empty($departments)){
                    foreach($departments as $department){
                        if(isset($department['fields'])){
                            if(!isset($shop_departments[$department['fields']['Depart']['uuid']])){
                                $this->model_extension_shipping_justin->addTableDepartments($department['fields']);
                            }
                        }
                    }
                    $refresh = array(
                        'justin_refresh_departments' => date('d-m-Y H:i')
                    );
                    $return['new_refresh_data'] = $refresh['justin_refresh_departments'];
                    $this->model_setting_setting->editSetting('justin_refresh', $refresh);
                }


            }else{ // Не внесені логін та пароль до API
	            $error = $this->language->get('error_login_and_pass');
            }

            if(isset($error) and !empty($error)){
                $return['error'] = $error;
            }else{
                $return['success'] = $this->language->get('success_refresh_branches');
            }

            $this->response->setOutput(json_encode($return));

        }

    }

    public function refreshcities(){

        if(isset($this->request->post['work'])){

            $this->load->language('extension/shipping/justin');
            $this->load->model('setting/setting');
            $this->load->model('extension/shipping/justin');

            $return = array();
            $error = '';

            $login = $this->config->get('justin_api_login');
            $password = $this->config->get('justin_api_password');
            $language_code = $this->request->post['work'];
            $test_mode = $this->config->get('justin_test_mode');

            if(!empty($login) and !empty($password)){

                // Встановлення підключення до API через клас
                $justin = new JustInApi($login,$password, $language_code, $test_mode);
                // Отримання інформації про міста по API
                $cities = $justin->get_cities();
//                print_r('<pre>');
//                print_r($cities);
//                print_r('</pre>');
//                exit();
                // Отримання інформації про вже записані відділення в базу
                $shop_cities = $this->model_extension_shipping_justin->getTableAllCities();
                // Запис кожного відділення в базу
                if(!empty($cities)){
                    foreach($cities as $city){
                        if(isset($city['fields'])){
                            if(!isset($shop_cities[$city['fields']['uuid']])){
                                $this->model_extension_shipping_justin->addTableCities($city['fields'], $language_code);
                            }elseif(isset($shop_cities[$city['fields']['uuid']]) and $language_code == 'UA'){
                                $this->model_extension_shipping_justin->addTableCities($city['fields'], $language_code);
                            }
                        }
                    }
                    $refresh = array(
                        'justin_refresh_cities' => date('d-m-Y H:i')
                    );
                    $return['new_refresh_data'] = $refresh['justin_refresh_cities'];
                    $this->model_setting_setting->editSetting('justin_refresh_c', $refresh);
                }


            }else{ // Не внесені логін та пароль до API
                $error = $this->language->get('error_login_and_pass');
            }

            if(isset($error) and !empty($error)){
                $return['error'] = $error;
            }else{
                $return['success'] = $this->language->get('success_refresh_cities');
            }

            $this->response->setOutput(json_encode($return));

        }

    }

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/justin')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function orderInfo($data){
        $html = '';
        $script = '';
        if(isset($data['order_id'])){
            $this->load->language('extension/shipping/justin');
            $this->load->model('extension/shipping/justin');

            $get_info_order_department = $this->model_extension_shipping_justin->getTableOrderDepartment($data['order_id']);

            if(!empty($get_info_order_department)){
                $get_all_departments = $this->model_extension_shipping_justin->getTableAllDepartments();
                $get_info_department = $this->model_extension_shipping_justin->getTableDepartments($get_info_order_department['uuid_department'], $get_info_order_department['number_department']);

                $html .='
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-truck"></i>' . $data['shipping_method'] . '</h3>
                  </div>
                  <div class="panel-body">
                    ' . $this->language->get('text_order_shipping') . '<span id="justin_depart_descr">' . $get_info_department['depart_descr'] . ' </span>
                    <button type="button" class="btn btn-primary" id="edit_justin_department"><i class="fa fa-edit"></i> ' . $this->language->get('button_edit') . '</button>
                    <div id="wrap_edit_department" style="display: none;" class="panel-body">
                    <div class="col-sm-10">';

                    $html .= '<select id="justin_departments_all" class="form-control">';

                    foreach($get_all_departments as $department){
                        $html .= '<option value="' . $department['number'] . '/' . $department['uuid'] . '">' . $department['depart_descr'] . '</option>';
                    }

                    $html .= '</select>';

                    $html .= '
                        </div>
                        <div class="col-sm-2">
                            <button type="button" id="save_justin_department" title="' . $this->language->get('button_save') . '" class="btn btn-primary"><i class="fa fa-save"></i></button>
                        </div>
                    </div>
                  </div>
                  ';

                $html .= $this->requestDelivery($data['order_id']);

                $html .= '
                </div>                
                ';

                $script .= '
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $(document).on(\'click\',\'#edit_justin_department\',function(){
                                $(\'#wrap_edit_department\').slideToggle();
                            });
                            
                            $(document).on(\'click\',\'#show_justin_request_delivery_form\',function(){
                                $(\'#form_justin_request_delivery\').slideToggle();
                            });
                            
                            $(document).on(\'click\',\'#save_justin_department\',function(){
                                var department = $("#justin_departments_all").val();
                                var order_id = "' . $data['order_id'] . '";
                                $.ajax({
                                    url: \'index.php?route=extension/shipping/justin/editDepartment&token=' . $this->session->data['token'] . '\',
                                    type: \'POST\',
                                    dataType: \'json\',
                                    data: {work:department,order_id:order_id},
                                    success: function(json) {
                                        $(\'#wrap_edit_department\').before(\'<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> \' + json[\'success\'] + \' <button type="button" class="close" data-dismiss="alert">&times;</button></div>\');
                                        $(\'#justin_depart_descr\').html(json[\'department\']);
                                        $(\'#wrap_edit_department\').slideUp();
                                    }
                                });
                            });
                            
                            $(document).on(\'click\',\'#save_justin_request_delivery_form\',function(){
                                $.ajax({
                                    url: \'index.php?route=extension/shipping/justin/saveRequestDelivery&token=' . $this->session->data['token'] . '\',
                                    type: \'POST\',
                                    dataType: \'json\',
                                    data: $("#form_justin_request_delivery").serialize(),
                                    success: function(json) {
                                        $(\'#form_justin_request_delivery\').slideUp();
                                        $(\'#form_justin_request_delivery\').before(\'<div class="panel-body"><div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> \' + json + \' <button type="button" class="close" data-dismiss="alert">&times;</button></div></div>\');
                                    }
                                });
                            });
                            $(document).on(\'click\',\'#send_justin_request_delivery_form\',function(){
                                $.ajax({
                                    url: \'index.php?route=extension/shipping/justin/sendRequestDelivery&token=' . $this->session->data['token'] . '\',
                                    type: \'POST\',
                                    dataType: \'json\',
                                    data: $("#form_justin_request_delivery").serialize(),
                                    success: function(json) {
                                    console.log(json);
                                        if(json[\'success\']){
                                            $(\'#form_justin_request_delivery\').slideUp();
                                            $(\'#form_justin_request_delivery\').before(\'<div class="panel-body"><div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> \' + json[\'success\'] + \' <button type="button" class="close" data-dismiss="alert">&times;</button></div></div>\');
                                    
                                            setTimeout(function() { location.reload() }, 1000);
                                                                                    
                                        }
                                        if (json[\'error\']) {
                                            $(\'#form_justin_request_delivery\').slideUp();
                                            $(\'#form_justin_request_delivery\').before(\'<div class="panel-body"><div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> \' + json[\'error\'] + \' <button type="button" class="close" data-dismiss="alert">&times;</button></div></div>\');
                                        }

                                    }
                                });
                            });
                        });
                    </script>
                ';
            }

        }

        $return = array(
            'html' => $html,
            'script' => $script
        );

        return $return;
    }

    public function editDepartment(){
	    if(isset($this->request->post['work'])){
            $this->load->language('extension/shipping/justin');
            $this->load->model('extension/shipping/justin');

            $department = explode('/', $this->request->post['work']);
            $this->model_extension_shipping_justin->editTableOrderDepartment($this->request->post['order_id'], $department[0], $department[1]);
            $get_info_order_department = $this->model_extension_shipping_justin->getTableOrderDepartment($this->request->post['order_id']);
            $get_info_department = $this->model_extension_shipping_justin->getTableDepartments($get_info_order_department['uuid_department'], $get_info_order_department['number_department']);

            $return = array(
                'success' => $this->language->get('success_edit_order_department'),
                'department' => $get_info_department['depart_descr']
            );

            $this->response->setOutput(json_encode($return));
        }
    }

    public function requestDelivery($order_id, $return_array = 2){
        $this->load->model('extension/shipping/justin');
        $get_info_request_delivery = $this->model_extension_shipping_justin->getTableRequestDelivery($order_id);
        $get_cities = $this->model_extension_shipping_justin->getAllCity();
        $get_departments = $this->model_extension_shipping_justin->getTableAllDepartments();
        if(!empty($get_info_request_delivery)){
            $data = $get_info_request_delivery;
        }else{
            $this->load->model('sale/order');
            $this->load->model('catalog/product');
            $justin_settings['justin_weight_class_mode'] = $this->config->get('justin_weight_class_mode'); // Режим розрахунку одиниці ваги
            $justin_settings['justin_weight_class_id'] = $this->config->get('justin_weight_class_id'); // Одиниця ваги, яка використовується в магазині

            // Інформація про замовлення
            $get_info_order = $this->model_sale_order->getOrder($order_id);
            // Інформація про суму
            $get_order_total = $this->model_sale_order->getOrderTotals($order_id);
            $sub_order_total = 0;
            if(!empty($get_order_total)){
                foreach($get_order_total as $order_total){
                    if($order_total['code'] == 'sub_total'){
                        $sub_order_total = $order_total['value'];
                    }
                }
            }
            // Товари в замовленні
            $get_order_products = $this->model_sale_order->getOrderProducts($order_id);
            $products_weight = 0;
            foreach($get_order_products as $order_product){
                $product_info = $this->model_catalog_product->getProduct($order_product['product_id']);
                // Якщо ввімкнено режим розрахунку одиниці ваги
                if($justin_settings['justin_weight_class_mode']){
                    if($product_info['weight_class_id'] == $justin_settings['justin_weight_class_id']){
                        $products_weight += $product_info['weight'] / $justin_settings['justin_weight_relation'];
                    }else{
                        $products_weight += $product_info['weight'];
                    }
                }else{
                    $products_weight += $product_info['weight'];
                }
            }

            // Вибране користувачем відділення
            $get_info_order_department = $this->model_extension_shipping_justin->getTableOrderDepartment($order_id);
            // Інформація про вибране відділення
            $get_branch = $this->model_extension_shipping_justin->getTableDepartments($get_info_order_department['uuid_department'], $get_info_order_department['number_department']);
            if(!empty($get_branch)){
                $branch = $get_branch['branch'];
            }else{
                $branch = '';
            }

            $shop_name = $this->config->get('config_name');
            $shop_owner = $this->config->get('config_owner');
            $shop_phone = $this->config->get('config_telephone');
            $shop_address = $this->config->get('config_address');

            $data = array(
                'number' => $order_id,                                                                               // Ідентифікатор замовлення торговця
                'date' => date('Y-m-d', strtotime($get_info_order['date_added'])),                                  // Дата оформлення замовлення
                'sender_city_id' => '32b69b95-9018-11e8-80c1-525400fb7782',                                         // Ідентифікатор міста відправника
                'sender_company' => $shop_name,                                                                     // Торговець (назва компанії/магазину)
                'sender_contact' => $shop_owner,                                                                    // Відправник (ПІП)
                'sender_phone' => $shop_phone,                                                                      // Номер телефону відправника
                'sender_pick_up_address' => $shop_address,                                                          // Адреса забору замовлення
                'pick_up_is_required' => true,                                                                      // true - забрати замовлення у торговця, false - забрати на відділенні
                'sender_branch' => '',                                                                              // Номер відділення доставки, потрібно якщо pick_up_is_required = false
                'receiver' => $get_info_order['shipping_firstname'] . ' ' . $get_info_order['shipping_lastname'],   // ПІП отримувача
                'receiver_contact' => '',                                                                           // ПІП контакту отримувача (дозволяється пусте значення, якщо отримувач = контакт
                'receiver_phone' => $get_info_order['telephone'],                                                   // Телефон отримувача
                'count_cargo_places' => 1,                                                                          // Кількість грузових місць
                'branch' => $branch,                                                                                // Номер відділення доставки
                'weight' => $products_weight,                                                                       // Вага
                'volume' => 0,                                                                                      // Об'єм
                'declared_cost' => $sub_order_total,                                                                // Задекларована вартість замовлення
                'delivery_amount' => 0,                         //НЕ ЗМІНЮВАТИ = 0                                  // Вартість доставки (якщо = 0 та delivery_payment_is_required = true - розрахунок проводиться по тарифам)
                'redelivery_amount' => 0,                       //НЕ ЗМІНЮВАТИ = 0                                  // Вартість комісії за доставку (якщо = 0 та redelivery_payment_is_required = true - розрахунок проводиться по тарифам)
                'order_amount' => $sub_order_total,                                                                 // Тіло платежу
                'redelivery_payment_is_required' => true,       //НЕ ЗМІНЮВАТИ = true                               // true - потрібна оплата комісії, false - не потрібна оплата комісії
                'redelivery_payment_payer' => 0,                                                                    // Платник комісії (0 - відправник, 1 - отримувач)
                'delivery_payment_is_required' => true,         //НЕ ЗМІНЮВАТИ = true                               // true - потрібна оплата доставки, false - не потрібна оплата доставки
                'delivery_payment_payer' => 0,                                                                      // Платник доставки (0 - відправник, 1 - отримувач)
                'order_payment_is_required' => true,                                                                // true - потрібна оплата замовлення, false - не потрібна оплата замовлення
                'add_description' => ''                                                                             // Коментар
            );
        }

        if($return_array == 2){
            $html = '';
            if(isset($get_info_request_delivery['number_ttn']) and isset($get_info_request_delivery['number_pms'])){
                if($get_info_request_delivery['number_ttn'] != null and $get_info_request_delivery['number_pms'] != null){

                    $login = $this->config->get('justin_api_login');
                    $password = $this->config->get('justin_api_password');
                    $language_code = $this->config->get('justin_language_code');
                    $test_mode = $this->config->get('justin_test_mode');

                    if(!empty($login) and !empty($password)) {

                        // Встановлення підключення до API через клас
                        $justin = new JustInApi($login, $password, $language_code, $test_mode);
                        // Отримання uuid по API
                        $get_uuid = $justin->get_uuid();
                        if(!empty($get_uuid) and isset($get_uuid[0])) {
                            if (isset($get_uuid[0]['fields'])) {
                                $uuid = $get_uuid[0]['fields']['counterpart']['uuid'];

                                $href = 'http://195.201.72.186/pms/hs/api/v1/printStickerWithContactPerson/order?order_number=' . $data['number'] . '&api_key=' . $uuid;

                                $html .= '<div class="panel-body"><a href="' . $href . '" class="btn btn-default" target="_blank">' . $this->language->get('text_get_sticker') . '</a></div>';
                            }
                        }
                    }
                }
            }

            $html .= '
              <div class="panel-body">
                <button type="button" class="btn btn-primary" id="show_justin_request_delivery_form"><i class="fa fa-eye"></i> ' . $this->language->get('text_show_request_delivery_form') . '</button>
              
                  <form id="form_justin_request_delivery" class="form-horizontal" style="display:none;">
                  
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="justin-number">' . $this->language->get('text_number_order') . '</label>
                      <div class="col-sm-10">
                        <input type="text" name="justin_request[number]" value="' . $data['number'] . '" id="justin-number" class="form-control" readonly/>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="justin-date">' . $this->language->get('text_order_date') . '</label>
                      <div class="col-sm-10">
                        <input type="text" name="justin_request[date]" value="' . $data['date'] . '" id="justin-date" class="form-control"/>
                      </div>
                    </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="justin-sender_city">' . $this->language->get('text_sender_city') . '</label>
                    <div class="col-sm-10">
                      <select name="justin_request[sender_city_id]" id="justin-sender_city" class="form-control">';
            foreach($get_cities as $city) {
                $select = $city['uuid'] == $data['sender_city_id']?'selected="selected"':'';
                $html .= '<option value="' . $city['uuid'] . '" ' . $select . '>' . $city['name_ua'] .' </option>';
            }
            $html .= '        
                      </select>
                    </div>
                  </div>';

            $html .= '<div class="form-group">
                      <label class="col-sm-2 control-label" for="justin-sender_company">' . $this->language->get('text_sender_company') . '</label>
                      <div class="col-sm-10">
                        <input type="text" name="justin_request[sender_company]" value="' . $data['sender_company'] . '" id="justin-sender_company" class="form-control"/>
                      </div>
                    </div>';
            $html .= '<div class="form-group">
                      <label class="col-sm-2 control-label" for="justin-sender_contact">' . $this->language->get('text_sender_contact') . '</label>
                      <div class="col-sm-10">
                        <input type="text" name="justin_request[sender_contact]" value="' . $data['sender_contact'] . '" id="justin-sender_contact" class="form-control"/>
                      </div>
                    </div>';
            $html .= '<div class="form-group">
                      <label class="col-sm-2 control-label" for="justin-sender_phone">' . $this->language->get('text_sender_phone') . '</label>
                      <div class="col-sm-10">
                        <input type="text" name="justin_request[sender_phone]" value="' . $data['sender_phone'] . '" id="justin-sender_phone" class="form-control"/>
                      </div>
                    </div>';
            $html .= '<div class="form-group">
                      <label class="col-sm-2 control-label" for="justin-sender_pick_up_address">' . $this->language->get('text_sender_pick_up_address') . '</label>
                      <div class="col-sm-10">
                        <input type="text" name="justin_request[sender_pick_up_address]" value="' . $data['sender_pick_up_address'] . '" id="justin-sender_pick_up_address" class="form-control"/>
                      </div>
                    </div>';
            $html .= '<div class="form-group">
                    <label class="col-sm-2 control-label" for="justin-pick_up_is_required">' . $this->language->get('text_pick_up_is_required') . '</label>
                    <div class="col-sm-10">
                      <select name="justin_request[pick_up_is_required]" id="justin-pick_up_is_required" class="form-control">';
                        if($data['pick_up_is_required'] == 0){
                            $html .= '<option value="0" selected="selected">' . $this->language->get('text_pick_up_is_required_department') .' </option>';
                            $html .= '<option value="1">' . $this->language->get('text_pick_up_is_required_shop') .' </option>';
                        }else{
                            $html .= '<option value="0">' . $this->language->get('text_pick_up_is_required_department') .' </option>';
                            $html .= '<option value="1" selected="selected">' . $this->language->get('text_pick_up_is_required_shop') .' </option>';
                        }
                      $html .= '</select>
                    </div>
                  </div>';
            $html .= '<div class="form-group">
                    <label class="col-sm-2 control-label" for="justin-sender_branch"><span data-toggle="tooltip" title="' . $this->language->get('help_sender_branch') . '">' . $this->language->get('text_sender_branch') . '</span></label>
                    <div class="col-sm-10">
                      <select name="justin_request[sender_branch]" id="justin-sender_branch" class="form-control">';
                foreach($get_departments as $department) {
                    $select = $department['branch'] == $data['sender_branch']?'selected="selected"':'';
                    $html .= '<option value="' . $department['branch'] . '" ' . $select . '>' . $department['depart_descr'] .' </option>';
                }
            $html .= '        
                      </select>
                    </div>
                  </div>';
            $html .= '<div class="form-group">
                  <label class="col-sm-2 control-label" for="justin-receiver">' . $this->language->get('text_receiver') . '</label>
                  <div class="col-sm-10">
                    <input type="text" name="justin_request[receiver]" value="' . $data['receiver'] . '" id="justin-receiver" class="form-control"/>
                  </div>
                </div>';
            $html .= '<div class="form-group">
                  <label class="col-sm-2 control-label" for="justin-receiver_contact">' . $this->language->get('text_receiver_contact') . '</label>
                  <div class="col-sm-10">
                    <input type="text" name="justin_request[receiver_contact]" value="' . $data['receiver_contact'] . '" id="justin-receiver_contact" class="form-control"/>
                  </div>
                </div>';
            $html .= '<div class="form-group">
                  <label class="col-sm-2 control-label" for="justin-receiver_phone">' . $this->language->get('text_receiver_phone') . '</label>
                  <div class="col-sm-10">
                    <input type="text" name="justin_request[receiver_phone]" value="' . $data['receiver_phone'] . '" id="justin-receiver_phone" class="form-control"/>
                  </div>
                </div>';
            $html .= '<div class="form-group">
                  <label class="col-sm-2 control-label" for="justin-count_cargo_places">' . $this->language->get('text_count_cargo_places') . '</label>
                  <div class="col-sm-10">
                    <input type="text" name="justin_request[count_cargo_places]" value="' . $data['count_cargo_places'] . '" id="justin-count_cargo_places" class="form-control"/>
                  </div>
                </div>';
            $html .= '<div class="form-group">
                    <label class="col-sm-2 control-label" for="justin-branch">' . $this->language->get('text_branch') . '</label>
                    <div class="col-sm-10">
                      <select name="justin_request[branch]" id="justin-branch" class="form-control">';
                foreach($get_departments as $department) {
                    $select = $department['branch'] == $data['branch']?'selected="selected"':'';
                    $html .= '<option value="' . $department['branch'] . '" ' . $select . '>' . $department['depart_descr'] .' </option>';
                }
            $html .= '        
                      </select>
                    </div>
                  </div>';
            $html .= '<div class="form-group">
                  <label class="col-sm-2 control-label" for="justin-weight">' . $this->language->get('text_weight') . '</label>
                  <div class="col-sm-10">
                    <input type="text" name="justin_request[weight]" value="' . $data['weight'] . '" id="justin-weight" class="form-control"/>
                  </div>
                </div>';
            $html .= '<div class="form-group">
                  <label class="col-sm-2 control-label" for="justin-volume">' . $this->language->get('text_volume') . '</label>
                  <div class="col-sm-10">
                    <input type="text" name="justin_request[volume]" value="' . $data['volume'] . '" id="justin-volume" class="form-control"/>
                  </div>
                </div>';
            $html .= '<div class="form-group">
                  <label class="col-sm-2 control-label" for="justin-declared_cost">' . $this->language->get('text_declared_cost') . '</label>
                  <div class="col-sm-10">
                    <input type="text" name="justin_request[declared_cost]" value="' . $data['declared_cost'] . '" id="justin-declared_cost" class="form-control"/>
                  </div>
                </div>';
            /*$html .= '<div class="form-group">
                  <label class="col-sm-2 control-label" for="justin-delivery_amount">' . $this->language->get('text_delivery_amount') . '</label>
                  <div class="col-sm-10">
                    <input type="text" name="justin_request[delivery_amount]" value="' . $data['delivery_amount'] . '" id="justin-delivery_amount" class="form-control"/>
                  </div>
                </div>';
            $html .= '<div class="form-group">
                  <label class="col-sm-2 control-label" for="justin-redelivery_amount">' . $this->language->get('text_redelivery_amount') . '</label>
                  <div class="col-sm-10">
                    <input type="text" name="justin_request[redelivery_amount]" value="' . $data['redelivery_amount'] . '" id="justin-redelivery_amount" class="form-control"/>
                  </div>
                </div>';*/
            $html .= '<div class="form-group">
                  <label class="col-sm-2 control-label" for="justin-order_amount">' . $this->language->get('text_order_amount') . '</label>
                  <div class="col-sm-10">
                    <input type="text" name="justin_request[order_amount]" value="' . $data['order_amount'] . '" id="justin-order_amount" class="form-control"/>
                  </div>
                </div>';
            /*html .= '<div class="form-group">
                    <label class="col-sm-2 control-label" for="justin-redelivery_payment_is_required">' . $this->language->get('text_redelivery_payment_is_required') . '</label>
                    <div class="col-sm-10">
                      <select name="justin_request[redelivery_payment_is_required]" id="justin-redelivery_payment_is_required" class="form-control">';
                        if($data['redelivery_payment_is_required'] == 0){
                            $html .= '<option value="0" selected="selected">' . $this->language->get('text_no_need') .' </option>';
                            $html .= '<option value="1">' . $this->language->get('text_need') .' </option>';
                        }else{
                            $html .= '<option value="0">' . $this->language->get('text_no_need') .' </option>';
                            $html .= '<option value="1" selected="selected">' . $this->language->get('text_need') .' </option>';
                        }
                      $html .= '</select>
                    </div>
                </div>';*/
            $html .= '<div class="form-group">
                    <label class="col-sm-2 control-label" for="justin-redelivery_payment_payer">' . $this->language->get('text_redelivery_payment_payer') . '</label>
                    <div class="col-sm-10">
                      <select name="justin_request[redelivery_payment_payer]" id="justin-redelivery_payment_payer" class="form-control">';
                        if($data['redelivery_payment_payer'] == 0){
                            $html .= '<option value="0" selected="selected">' . $this->language->get('text_sender') .' </option>';
                            $html .= '<option value="1">' . $this->language->get('text_receiver') .' </option>';
                        }else{
                            $html .= '<option value="0">' . $this->language->get('text_sender') .' </option>';
                            $html .= '<option value="1" selected="selected">' . $this->language->get('text_receiver') .' </option>';
                        }
                      $html .= '</select>
                    </div>
                </div>';
            /*$html .= '<div class="form-group">
                    <label class="col-sm-2 control-label" for="justin-delivery_payment_is_required">' . $this->language->get('text_delivery_payment_is_required') . '</label>
                    <div class="col-sm-10">
                      <select name="justin_request[delivery_payment_is_required]" id="justin-delivery_payment_is_required" class="form-control">';
                        if($data['delivery_payment_is_required'] == 0){
                            $html .= '<option value="0" selected="selected">' . $this->language->get('text_no_need') .' </option>';
                            $html .= '<option value="1">' . $this->language->get('text_need') .' </option>';
                        }else{
                            $html .= '<option value="0">' . $this->language->get('text_no_need') .' </option>';
                            $html .= '<option value="1" selected="selected">' . $this->language->get('text_need') .' </option>';
                        }
                      $html .= '</select>
                    </div>
                </div>';*/
            $html .= '<div class="form-group">
                    <label class="col-sm-2 control-label" for="justin-delivery_payment_payer">' . $this->language->get('text_delivery_payment_payer') . '</label>
                    <div class="col-sm-10">
                      <select name="justin_request[delivery_payment_payer]" id="justin-delivery_payment_payer" class="form-control">';
                        if($data['delivery_payment_payer'] == 0){
                            $html .= '<option value="0" selected="selected">' . $this->language->get('text_sender') .' </option>';
                            $html .= '<option value="1">' . $this->language->get('text_receiver') .' </option>';
                        }else{
                            $html .= '<option value="0">' . $this->language->get('text_sender') .' </option>';
                            $html .= '<option value="1" selected="selected">' . $this->language->get('text_receiver') .' </option>';
                        }
                      $html .= '</select>
                    </div>
                </div>';
            $html .= '<div class="form-group">
                    <label class="col-sm-2 control-label" for="justin-order_payment_is_required">' . $this->language->get('text_order_payment_is_required') . '</label>
                    <div class="col-sm-10">
                      <select name="justin_request[order_payment_is_required]" id="justin-order_payment_is_required" class="form-control">';
                        if($data['order_payment_is_required'] == 0){
                            $html .= '<option value="0" selected="selected">' . $this->language->get('text_no_need') .' </option>';
                            $html .= '<option value="1">' . $this->language->get('text_need') .' </option>';
                        }else{
                            $html .= '<option value="0">' . $this->language->get('text_no_need') .' </option>';
                            $html .= '<option value="1" selected="selected">' . $this->language->get('text_need') .' </option>';
                        }
                      $html .= '</select>
                    </div>
                </div>';
            $html .= '<div class="form-group">
                    <label class="col-sm-2 control-label" for="justin-add_description">' . $this->language->get('text_add_description') . '</label>
                <div class="col-sm-10">
                    <textarea name="justin_request[add_description]" rows="6" id="justin-add_description" class="form-control">' . $data['add_description'] . '</textarea>
                </div>
                </div>';
            $html .= '
                    <button type="button" class="btn btn-success" id="save_justin_request_delivery_form"><i class="fa fa-save"></i> ' . $this->language->get('text_save_request_delivery_form') . '</button>
                    <button type="button" class="btn btn-success" id="send_justin_request_delivery_form"><i class="fa fa-truck"></i> ' . $this->language->get('text_send_request_delivery_form') . '</button>
                  </form>
              </div>
            ';


            return $html;
        }else{ // $return_array призначений для повернення сформованого масиву доставки
            return $data;
        }



    }

    public function saveRequestDelivery($save = 'save'){
        if(isset($this->request->post['justin_request']) and !empty($this->request->post['justin_request'])){
            $this->load->language('extension/shipping/justin');
            $this->load->model('extension/shipping/justin');

            $this->model_extension_shipping_justin->addTableRequestDelivery($this->request->post['justin_request']);

            if($save == 'save'){
                $this->response->setOutput(json_encode($this->language->get('success_save_request_delivery')));
            }else{
                return $save;
            }
        }

    }

    public function sendRequestDelivery(){
        if(isset($this->request->post['justin_request']) and !empty($this->request->post['justin_request'])){
            $this->load->language('extension/shipping/justin');
            $this->load->model('extension/shipping/justin');
            $this->saveRequestDelivery('send');

            $get_data_request = $this->model_extension_shipping_justin->getTableRequestDelivery($this->request->post['justin_request']['number']);
//            if($get_data_request['number_ttn'] == null and $get_data_request['number_pms'] == null){

                unset($get_data_request['number_ttn']);
                unset($get_data_request['number_pms']);

                // Потрібно перевести із цифрових значень в булів тип
                if($get_data_request['pick_up_is_required'] == 1){
                    $get_data_request['pick_up_is_required'] = true;
                }else{
                    $get_data_request['pick_up_is_required'] = false;
                }
                if($get_data_request['redelivery_payment_is_required'] == 1){
                    $get_data_request['redelivery_payment_is_required'] = true;
                }else{
                    $get_data_request['redelivery_payment_is_required'] = false;
                }
                if($get_data_request['delivery_payment_is_required'] == 1){
                    $get_data_request['delivery_payment_is_required'] = true;
                }else{
                    $get_data_request['delivery_payment_is_required'] = false;
                }
                if($get_data_request['order_payment_is_required'] == 1){
                    $get_data_request['order_payment_is_required'] = true;
                }else{
                    $get_data_request['order_payment_is_required'] = false;
                }


                $return = array();
                $error = '';

                $login = $this->config->get('justin_api_login');
                $password = $this->config->get('justin_api_password');
                $language_code = $this->config->get('justin_language_code');
                $test_mode = $this->config->get('justin_test_mode');

                if(!empty($login) and !empty($password)){

                    // Встановлення підключення до API через клас
                    $justin = new JustInApi($login,$password, $language_code, $test_mode);
                    // Отримання uuid по API
                    $get_uuid = $justin->get_uuid();

                    if(!empty($get_uuid) and isset($get_uuid[0])){
                        if(isset($get_uuid[0]['fields'])){
                            $uuid = $get_uuid[0]['fields']['counterpart']['uuid'];

                            $request_delivery = $justin->request_delivery($uuid, $get_data_request);

                            if(!empty($request_delivery) and isset($request_delivery['ttn']) and isset($request_delivery['number'])){
                                $this->model_extension_shipping_justin->upTableRequestDelivery($this->request->post['justin_request']['number'], $request_delivery);
                            }
                            elseif(isset($request_delivery[0]) and isset($request_delivery[0]['error'])){
                                $error = $request_delivery[0]['error'];
                            }

                        }
                    }else{ // Якщо прийшла помилка - повертаємо
                        $error = $get_uuid;
                    }


                }else{ // Не внесені логін та пароль до API
                    $error = $this->language->get('error_login_and_pass');
                }
//            }else{ // Запит вже відправлявся
//                $error = $this->language->get('error_isset_send_request_delivery');
//            }


            if(isset($error) and !empty($error)){
                $return['error'] = $error;
            }else{
                $return['success'] = $this->language->get('success_send_request_delivery');
            }

            $this->response->setOutput(json_encode($return));

        }
    }

}
?>