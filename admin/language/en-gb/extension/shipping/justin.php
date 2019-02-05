<?php 
// Heading
$_['heading_title']                         = 'Shipping Justin';

// Text
$_['text_extension']                        = 'Shipping';
$_['text_success']                          = 'Success: You have modified module!';
$_['text_edit']                             = 'Edit Account Module';

// Entry
$_['entry_cost']                            = 'Cost';
$_['entry_tax_class']                       = 'Tax Class';
$_['entry_geo_zone']                        = 'Geo Zone';
$_['entry_status']                          = 'Status';
$_['entry_sort_order']                      = 'Sort Order';

$_['entry_api_login']                       = 'Login to API';
$_['entry_api_password']                    = 'API Password';
$_['entry_status_test_mode']                = 'Test Mode';
$_['entry_language_code']                   = 'Language of received information with API';
$_['entry_allow_select_departments']        = 'The user can select a branch in any city';
$_['entry_fixed_cost']                      = 'Fixed Value';
$_['entry_fixed_cost_mode']                 = 'Fixed price for all orders';
$_['entry_cost_free']                       = 'Free shipping from';
$_['entry_weight_class_shop']               = 'Used in-store weight unit';
$_['entry_weight_relation']                 = 'The ratio of the unit of weight used to the kilogram';



$_['text_general']                          = 'Basic Settings';
$_['text_more_settings']                    = 'Advanced';
$_['text_branches']                         = 'Branches';
$_['text_refresh_branches']                 = 'Update information about offices';
$_['text_add_branch']                       = 'Added branches';
$_['text_up_branch']                        = 'Updated branches';
$_['text_ok_branch']                        = 'Update completed successfully';
$_['text_last_refresh']                     = 'Last Updated';
$_['text_cities']                           = 'Cities';
$_['text_refresh_cities']                   = 'Update City Information';
$_['text_cost']                             = 'Cost';
$_['text_title_weight_cost']                = 'Delivery rate depending on the weight of the goods in the order';
$_['text_weight_cost_kg']                   = 'kg.';
$_['text_weight_class_mode']                = 'Weight Calculation Mode';
$_['text_order_shipping']                   = '<b> Shipping to: </ b>';
$_['text_show_request_delivery_form']       = 'Show delivery request form';
$_['text_save_request_delivery_form']       = 'Save Form';
$_['text_send_request_delivery_form']       = 'Send a request for delivery';


$_['text_number_order']                     = 'Store order number';
$_['text_order_date']                       = 'Order Date';
$_['text_sender_city']                      = 'Sender City';
$_['text_sender_company']                   = 'Company Name';
$_['text_sender_contact']                   = 'Name of the Sender';
$_['text_sender_phone']                     = 'Sender\'s Phone';
$_['text_sender_pick_up_address']           = 'Address of the order fence';
$_['text_pick_up_is_required']              = 'Order must be picked up:';
$_['text_pick_up_is_required_shop']         = 'To store address';
$_['text_pick_up_is_required_department']   = 'In the department JustIn';
$_['text_sender_branch']                    = 'The branch in which to pick up the order';
$_['text_receiver']                         = 'Name of the recipient';
$_['text_receiver_contact']                 = 'Name of the contact of the recipient (may be empty)';
$_['text_receiver_phone']                   = 'Recipient Phone';
$_['text_count_cargo_places']               = 'Number of pieces';
$_['text_branch']                           = 'Delivery Branch';
$_['text_weight']                           = 'Weight';
$_['text_volume']                           = 'Volume';
$_['text_declared_cost']                    = 'Declared value of the order';
$_['text_delivery_amount']                  = 'Shipping Cost';
$_['text_redelivery_amount']                = 'Cost of Delivery Fee';
$_['text_order_amount']                     = 'Payment body';
$_['text_redelivery_payment_is_required']   = 'Payment of commission';
$_['text_redelivery_payment_payer']         = 'Payer commission';
$_['text_delivery_payment_is_required']     = 'Payment of delivery';
$_['text_delivery_payment_payer']           = 'Delivery Payer';
$_['text_order_payment_is_required']        = 'Order Payment';
$_['text_add_description']                  = 'Comment';
$_['text_get_sticker']                      = 'Get an EW sticker';

$_['text_need']                             = 'Needed';
$_['text_no_need']                          = 'Not needed';

$_['text_sender']                           = 'Sender';
$_['text_receiver']                         = 'Recipient';

$_['text_language_RU']                      = 'Русский';
$_['text_language_UA']                      = 'Українська';
$_['text_language_EN']                      = 'English';


// Help
$_['help_refresh_branches']                 = 'Before clicking the update button, make sure that the Login and Password for the JustIn API are filled in the Basic Settings tab';
$_['help_fixed_cost_mode']                  = 'If enabled, the fixed cost value will be applied to all orders regardless of the weight of the goods in the order';
$_['help_cost_free']                        = 'If in the order the cost of goods is greater than the specified value, the delivery is considered free of charge. Attention! If the field is empty or is zero (0) - free delivery is not taken into account. ';
$_['help_weight_class_mode']                = 'ATTENTION! If the store uses a unit of weight not kilogram, the mode of calculating the unit of weight must be turned on, then select the unit of weight used and indicate the ratio of the selected unit to the kilogram. in the ratio indicate 1000 ';
$_['help_sender_branch']                    = 'Be sure to specify if the order needs to be picked up at the office';

// Error
$_['error_permission']                      = 'Warning: You do not have permission to modify module!';
$_['error_login_and_pass']                  = 'You did not fill in the Login and Password fields or did not save the module settings!';
$_['error_isset_send_request_delivery']     = 'A delivery request has already been sent!';

// Success


$_['success_refresh_branches']              = 'The branch information has been updated successfully!';
$_['success_refresh_cities']                = 'City information has been successfully updated!';
$_['success_edit_order_department']         = 'Office successfully changed';
$_['success_save_request_delivery']         = 'The delivery request form was successfully saved';
$_['success_send_request_delivery']         = 'Delivery request successfully sent';

?>