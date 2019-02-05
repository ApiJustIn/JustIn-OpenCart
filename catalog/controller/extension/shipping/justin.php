<?php

class ControllerExtensionShippingJustin extends Controller {


    public function get_departments_like_city(){
        if(isset($this->request->post['work'])){

            $return = array();

            if($this->config->get('justin_status')){

                if(isset($this->session->data['shipping_address']['city'])){

                    $city = $this->session->data['shipping_address']['city'];

                    $this->load->model('extension/shipping/justin');
                    $this->load->language('extension/shipping/justin');

                    // Шукаємо місто російською мовою, а у випадку відсутності - шукаємо українською мовою
                    $search_city = $this->model_extension_shipping_justin->searchCity($city, 'ru');
                    if(empty($search_city)){
                        $search_city = $this->model_extension_shipping_justin->searchCity($city, 'ua');
                    }

                    if(isset($search_city['uuid'])){

                        $get_departments_city = $this->model_extension_shipping_justin->searchDepartmentsLikeCity($search_city['uuid']);

                        if(!empty($get_departments_city)){

                            $html = '<div class="wrap_select_departments">';
                            $html .= '<div class="form-group">';
                            $html .= '<label class="col-sm-2 control-label" for="justin_department">' . $this->language->get('text_select_departments') . '</label>';
                            $html .= '<div class="col-sm-10">';

                            $html .= '<select name="justin_department" id="justin_department" class="form-control">';

                            foreach($get_departments_city as $department){

                                $html .= '<option value="' . $department['number'] . '/' . $department['uuid'] . '">' . $department['depart_descr'] . '</option>';

                            }

                            $html .= '</select>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</div>';

                            // Якщо ввімкнено налаштування довільного вибору відділення
                            if($this->config->get('justin_allow_select_departments')){
                                $html .= '<div class="form-group">';
                                $html .= '<label class="col-sm-3 control-label" for="select_from_all_departments">' . $this->language->get('text_select_all_departments') . '</label>';
                                $html .= '<div class="col-sm-9">';
                                $html .= '<input type="checkbox" name="select_from_all_departments" id="select_from_all_departments">';
                                $html .= '</div>';
                                $html .= '</div>';
                                $html .= '<div class="wrap_select_all_departments" style="display: none;">';
                                $html .= $this->get_select_all_departments();
                                $html .= '</div>';
                            }

                            $return['success'] = 'success';
                            $return['html'] = $html;

                            $this->response->setOutput(json_encode($return));


                        }else{ // Місто знайдено, але не знайдені відділення - такого бути не може, але в такому випадку виведемо всі відділення

                            $html = '<div class="wrap_select_departments" style="display: none;">';
                            $html .= '<div class="form-group">';
                            $html .= '<label class="col-sm-2 control-label" for="justin_department">' . $this->language->get('text_select_departments') . '</label>';
                            $html .= '<div class="col-sm-10">';
                            $html .= '<select name="justin_department" id="justin_department" class="form-control">';
                            $html .= '<option value="no_department"No Department</option>';
                            $html .= '</select>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</div>';

                            $html .= $this->get_select_all_departments();
                            $return['success'] = 'success';
                            $return['html'] = $html;
                            $this->response->setOutput(json_encode($return));
                        }

                    }else{ // Місто не знайдено
                        $html = '<div class="wrap_select_departments" style="display: none;">';
                        $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-2 control-label" for="justin_department">' . $this->language->get('text_select_departments') . '</label>';
                        $html .= '<div class="col-sm-10">';
                        $html .= '<select name="justin_department" id="justin_department" class="form-control">';
                        $html .= '<option value="no_department"No Department</option>';
                        $html .= '</select>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';

                        $html .= $this->get_select_all_departments();
                        $return['success'] = 'success';
                        $return['html'] = $html;
                        $this->response->setOutput(json_encode($return));
                    }

                }

            }else{
                $return['error'] = 'Модуль JustIn не ввімкнено';
                $this->response->setOutput(json_encode($return));
            }

        }
    }

    public function get_select_all_departments(){

        $this->load->model('extension/shipping/justin');
        $this->load->language('extension/shipping/justin');

        $get_all_departments = $this->model_extension_shipping_justin->getAllDepartments();

        if(!empty($get_all_departments)){

            $html = '<div class="form-group">';
            $html .= '<label class="col-sm-2 control-label" for="justin_department_all">' . $this->language->get('text_select_departments') . '</label>';
            $html .= '<div class="col-sm-10">';

            $html .= '<select name="justin_departments_all" id="justin_departments_all" class="form-control">';

            foreach($get_all_departments as $department){

                $html .= '<option value="' . $department['number'] . '/' . $department['uuid'] . '">' . $department['depart_descr'] . '</option>';

            }

            $html .= '</select>';
            $html .= '</div>';
            $html .= '</div>';

        }else{
            $html = '';
        }

        if(isset($this->request->post['departments'])){
            $this->response->setOutput(json_encode($html));
        }else{
            return $html;
        }
    }

    public function set_department(){

        if(isset($this->request->post['justin_departments'])){
            if(isset($this->request->post['check'])){
                $check = $this->request->post['check'];

                // Перевірка вибору відділень
                if($check == 'no_check'){
                    $post_department = $this->request->post['justin_departments'];
                }else{
                    $post_department = $this->request->post['justin_departments_all'];
                }

                if($post_department != 'no_department'){
                    $department = explode('/', $post_department);

                    $this->session->data['justin_shipping'] = array(
                        'number_department' => $department[0],
                        'uuid_department' => $department[1]
                    );

                }
            }
        }

    }

    public function order_final(){
        if($this->config->get('justin_status')) {
            if (isset($this->session->data['justin_shipping']) and isset($this->session->data['order_id'])){
                $this->load->model('checkout/order');
                $get_info_order = $this->model_checkout_order->getOrder($this->session->data['order_id']);
                if($get_info_order['shipping_code'] == 'justin.justin'){
                    $this->load->model('extension/shipping/justin');
                    $this->model_extension_shipping_justin->addOrderDepartment($this->session->data['order_id'], $this->session->data['justin_shipping']);
                }
            }
        }
    }





}