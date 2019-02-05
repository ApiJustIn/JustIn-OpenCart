<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-justin" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">


        <div>
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-start" data-toggle="tab" aria-expanded="true"><i class="fa fa-gear"></i>&nbsp&nbsp<?php echo $text_general; ?></a></li>
            <li><a href="#tab-cost" data-toggle="tab" aria-expanded="true"><i class="fa fa-money"></i>&nbsp&nbsp<?php echo $text_cost; ?></a></li>
            <li><a href="#tab-branches" data-toggle="tab" aria-expanded="true"><i class="fa fa-university"></i>&nbsp&nbsp<?php echo $text_branches; ?></a></li>
            <li><a href="#tab-cities" data-toggle="tab" aria-expanded="true"><i class="fa fa-building"></i>&nbsp&nbsp<?php echo $text_cities; ?></a></li>
            <li><a href="#tab-more_settings" data-toggle="tab" aria-expanded="true"><i class="fa fa-gears"></i>&nbsp&nbsp<?php echo $text_more_settings; ?></a></li>
          </ul>
        </div>


        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-justin" class="form-horizontal">

          <div class="tab-content">
            <div class="tab-pane active" id="tab-start">


              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-login"><?php echo $entry_api_login; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="justin_api_login" value="<?php echo $justin_api_login; ?>" placeholder="<?php echo $entry_api_login; ?>" id="input-login" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_api_password; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="justin_api_password" value="<?php echo $justin_api_password; ?>" placeholder="<?php echo $entry_api_password; ?>" id="input-password" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-language_code"><?php echo $entry_language_code; ?></label>
                <div class="col-sm-10">
                  <select name="justin_language_code" id="input-language_code" class="form-control">

                    <?php foreach($language_codes as $language_key => $language_code){ ?>

                      <option value="<?php echo $language_key; ?>" <?php echo $language_key == $justin_language_code?'selected="selected"':''; ?>><?php echo $language_code; ?></option>

                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-test_mode"><?php echo $entry_status_test_mode; ?></label>
                <div class="col-sm-10">
                  <select name="justin_test_mode" id="input-test_mode" class="form-control">
                    <?php if ($justin_test_mode == 1) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="not_test"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="not_test" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
                <div class="col-sm-10">
                  <select name="justin_tax_class_id" id="input-tax-class" class="form-control">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($tax_classes as $tax_class) { ?>
                    <?php if ($tax_class['tax_class_id'] == $justin_tax_class_id) { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                <div class="col-sm-10">
                  <select name="justin_geo_zone_id" id="input-geo-zone" class="form-control">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $justin_geo_zone_id) { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="justin_status" id="input-status" class="form-control">
                    <?php if ($justin_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="justin_sort_order" value="<?php echo $justin_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>



            </div>

            <div class="tab-pane" id="tab-cost">

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-cost"><?php echo $entry_fixed_cost; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="justin_cost" value="<?php echo $justin_cost; ?>" placeholder="<?php echo $entry_cost; ?>" id="input-cost" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-fixed_cost_mode"><span data-toggle="tooltip" title="<?php echo $help_fixed_cost_mode; ?>"><?php echo $entry_fixed_cost_mode; ?></span></label>
                <div class="col-sm-10">
                  <select name="justin_fixed_cost_mode" id="input-fixed_cost_mode" class="form-control">
                    <?php if ($justin_fixed_cost_mode) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-cost_free"><span data-toggle="tooltip" title="<?php echo $help_cost_free; ?>"><?php echo $entry_cost_free; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="justin_cost_free" value="<?php echo $justin_cost_free; ?>" placeholder="<?php echo $entry_cost_free; ?>" id="input-cost_free" class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-7 control-label"><?php echo $text_title_weight_cost; ?></label class="col-sm-7 control-label">
              </div>

              <?php foreach($justin_weight as $justin_weight_one){ ?>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-weight_cost-<?php echo $justin_weight_one; ?>"><?php echo $justin_weight_one . $text_weight_cost_kg; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="justin_weight_cost[<?php echo $justin_weight_one; ?>]" value="<?php echo $justin_weight_cost[$justin_weight_one]; ?>" placeholder="<?php echo $justin_weight_one . $text_weight_cost_kg; ?>" id="input-weight_cost-<?php echo $justin_weight_one; ?>" class="form-control" />
                  </div>
                </div>
              <?php } ?>

            </div>

            <div class="tab-pane" id="tab-branches">


              <div class="form-group">
                <label class="col-sm-3 control-label" for="button_refresh_branches"><?php echo $text_refresh_branches; ?></label>
                <div class="col-sm-9">
                  <button type="button" id="button_refresh_branches" data-toggle="tooltip" title="<?php echo $help_refresh_branches; ?>" class="btn btn-success"><i class="fa fa fa-refresh"></i></button>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo $text_last_refresh; ?></label>
                <div class="col-sm-2">
                  <input type="text" value="<?php echo $last_refresh_departments; ?>" id="input-last_refresh_departments" class="form-control" readonly/>
                </div>
              </div>


            </div>


            <div class="tab-pane" id="tab-cities">


              <div class="form-group">
                <label class="col-sm-3 control-label" for="button_refresh_cities"><?php echo $text_refresh_cities; ?></label>
                <div class="col-sm-9">
                  <button type="button" id="button_refresh_cities" data-toggle="tooltip" title="<?php echo $help_refresh_branches; ?>" class="btn btn-success"><i class="fa fa fa-refresh"></i></button>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo $text_last_refresh; ?></label>
                <div class="col-sm-2">
                  <input type="text" value="<?php echo $last_refresh_cities; ?>" id="input-last_refresh_cities" class="form-control" readonly/>
                </div>
              </div>


            </div>


            <div class="tab-pane" id="tab-more_settings">

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-allow_select_departments"><?php echo $entry_allow_select_departments; ?></label>
                <div class="col-sm-10">
                  <select name="justin_allow_select_departments" id="input-allow_select_departments" class="form-control">
                    <?php if ($justin_allow_select_departments) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <span class="title_setting_justin text-warning col-sm-12"><?php echo $help_weight_class_mode; ?></span>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-weight_class_mode"><?php echo $text_weight_class_mode; ?></label>
                <div class="col-sm-10">
                  <select name="justin_weight_class_mode" id="input-weight_class_mode" class="form-control">
                    <?php if ($justin_weight_class_mode) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-justin_weight-class"><?php echo $entry_weight_class_shop; ?></label>
                <div class="col-sm-10">
                  <select name="justin_weight_class_id" id="input-justin_weight-class" class="form-control">
                    <?php foreach ($weight_classes as $weight_class) { ?>
                      <option value="<?php echo $weight_class['weight_class_id']; ?>" <?php echo $weight_class['weight_class_id']==$justin_weight_class_id?'selected="selected"':''; ?>><?php echo $weight_class['title']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-weight_relation"><?php echo $entry_weight_relation; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="justin_weight_relation" value="<?php echo $justin_weight_relation; ?>" placeholder="<?php echo $entry_weight_relation; ?>" id="input-weight_relation" class="form-control" />
                </div>
              </div>

            </div>
          </div>


        </form>
      </div>
    </div>
  </div>
</div>

<style>
  #input-last_refresh_departments, #input-last_refresh_cities{
    background: none;
    border: none;
    box-shadow: none;
  }
  .title_setting_justin{
    width: inherit;
    text-align: center;
    font-weight: bold;
  }
</style>

<?php echo $footer; ?>

<script type="text/javascript">
    $('#button_refresh_branches').on('click', function(){

        var btn = '#button_refresh_branches';

        $.ajax({
            url: 'index.php?route=extension/shipping/justin/refreshdepartments&token=<?php echo $token; ?>',
            type: 'POST',
            dataType: 'json',
            data: 'work=refresh',
            beforeSend: function() {
                $(btn).button('loading');
            },
            complete: function() {
                $(btn).button('reset');
            },
            success: function(json) {
                if (json['error']) {
                    $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }
                if (json['success']) {
                    console.log(json['success']);
                    $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    if(json['new_refresh_data']){
                        $('#input-last_refresh_departments').val(json['new_refresh_data']);
                    }
                }

            }
        });
    });

    $('#button_refresh_cities').on('click', function(){

        var btn = '#button_refresh_cities';

        $.ajax({
            url: 'index.php?route=extension/shipping/justin/refreshcities&token=<?php echo $token; ?>',
            type: 'POST',
            dataType: 'json',
            data: 'work=RU',
            beforeSend: function() {
                $(btn).button('loading');
            },
            success: function(json) {
                if (json['error']) {
                    $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }
                if (json['success']) {
                    console.log(json['success']);

                    $.ajax({
                        url: 'index.php?route=extension/shipping/justin/refreshcities&token=<?php echo $token; ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: 'work=UA',
                        complete: function() {
                            $(btn).button('reset');
                        },
                        success: function(json) {
                            if (json['error']) {
                                $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                            }
                            if (json['success']) {
                                console.log(json['success']);
                                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                                if(json['new_refresh_data']){
                                    $('#input-last_refresh_cities').val(json['new_refresh_data']);
                                }
                            }

                        }
                    });

                }

            }
        });
    });

</script>