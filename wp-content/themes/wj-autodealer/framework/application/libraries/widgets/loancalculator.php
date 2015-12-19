<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Loancalculator_Widget extends WP_Widget { 
  
    public function AT_Loancalculator_Widget() {
        $widget_ops = array('description' => __('Display loan calculator.',AT_ADMIN_TEXTDOMAIN) );
        $control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'loan_calculator' );
        $this->WP_Widget( 'loan_calculator', sprintf( __('%1$s - Loan Calculator', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ), $widget_ops, $control_ops );
    }
  
    public function widget($args, $instance) {
        extract($args);
        $defaults = array( 'title' => '', 'description' => '');
        $instance = wp_parse_args( (array) $instance, $defaults );

        $title = '';

        if (isset($instance['title'])) {
          $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        }

        if ( !empty($title) ) {
            $title_array = explode("\x20",$title);
            $title = "";
            foreach($title_array as $tcount => $word) {
                if ( $tcount == 0 ) {
                    $word = "<strong>" . $word . "</strong>";
                }
                $title .= $word . " ";
            }
            $title = "<h3>" . $title . "</h3>";
        }

        $description = $instance['description'];

        $loans = AT_Core::get_instance()->get_option( 'loans' );

        $html = '<form name="loan_calculator" class="loan calculator form">';

        $annual_rate = array();
        $downpay = array();
        $period_min = array();
        $period_max = array();
        $name = array();

        $total = count( $loans ) - 1;

        foreach ($loans as $key => $loan) {
            $period_min[]  = $loan['period_min'];
            $period_max[]  = $loan['period_max'];
            $annual_rate[] = str_replace('%','',$loan['annual_rate']);
            $downpay[]     = str_replace('%','',$loan['downpay']);
            $names[]       = $loan['name'];
        }
        $cn = 0;

        $html .= '<div class="loan calculator-wrapper">';

        $html .= $title;

        // Loan amount
        $html .= '<label><strong>' . __( 'Loan Amount', AT_TEXTDOMAIN ) . ':</strong></label>';
        $html .= '<input name="amount" class="text loan amount" value="" />';

        // Down pay
        $html .= '<div class="select_wrapper">';

        $html .= '<label><strong>' . __( 'Down Payment', AT_TEXTDOMAIN ) . ':</strong></label>';
        $html .= '<select name="downpay" class="custom-select loan down pay">';
        // $html .= '<option value="-1" selected>' . __( 'Please select...', AT_TEXTDOMAIN ) . '</option>';
        foreach( $names as $name ) {

            $html .= '<option value="' . $cn . '">' . $downpay[$cn] . '%</option>';
            $cn++;
        }
        $html .= '</select>';
        $html .= '</div>';

        // Annual rate
        $html .= '<div class="select_wrapper">';
        $html .= '<label><strong>' . __( 'Annual Rate', AT_TEXTDOMAIN ) . ':</strong></label>';
        $html .= '<select name="rate" class="custom-select loan annual rate">';
        $html .= '<option value="">' . __( 'Please select...', AT_TEXTDOMAIN ) . '</option>';
        $html .= '</select>';
        $html .= '</div>';

        // period_max
        $html .= '<div class="select_wrapper">';
        $html .= '<label><strong>' . __( 'Loan Period', AT_TEXTDOMAIN ) . ':</strong></label>';
        $html .= '<select name="period" class="custom-select loan period">';
        $html .= '<option value="">' . __( 'Please select...', AT_TEXTDOMAIN ) . '</option>';
        $html .= '</select>';
        $html .= '</div>';

        $html .= '<input type="submit" value="' . __( 'Calculate', AT_TEXTDOMAIN ) . '" class="btn_calc">';
        $html .= '</div>';

        $script = '
        <noscript>
            ' . __( 'Loan Calculator requires enable Javascript support. Please enable Javascript in your browser settings to continue, and reload page.', AT_TEXTDOMAIN ) . '
        </noscript>
        <script>
            jQuery(document).ready(function(){
                var $j = jQuery;
                var period  = ' . json_encode($period_max) . ';
                var downpay = ' . json_encode($downpay) . ';
                var rate    = ' . json_encode($annual_rate) . ';
                var settings = [];
                var downpay_html = [];
                var meta = $j(".car_characteristics .price");
                var dropdown_period = $j(".loan.calculator .loan.period");
                var amount = $j(".loan.calculator .loan.amount");
                var annual_rate = $j(".loan.calculator .loan.annual.rate");
                var selector = $j(".loan.calculator-wrapper .loan.down.pay");
                var initLoanCalculator = function(el) {
                    var val = el.val();
                    if ( val.length > 0 ) {
                        settings["period"]=period[val]*1;
                        settings["downpay"]=downpay[val];
                        settings["rate"]=rate[val]*1;
                        dropdown_period.html("");
                        annual_rate.html("");
                        // Period
                        for (var p = 1; p < (period[val]*1+1); p++) {
                            $j("<option/>", {value: p, text: p + " year"}).appendTo(dropdown_period);
                        }
                        // Rate
                        $j("<option/>", {value: rate[val], text: rate[val] + "%"}).appendTo(annual_rate);
                    }                    
                }
                $j(".loan.calculator-wrapper .loan.down.pay option").each(function(e) {
                    if ( meta.length > 0) {

                        settings["price"] = meta.data("price")*1;
                        settings["currency"] = meta.data("currency");
                        // downpay_html[e] = downpay[e] * (((meta.data("price"))*1) / 100);
                        // downpay_html[e] += " " + meta.data("currency");
                        amount.val(meta.data("price"));
                        amount.attr("readonly","true");
                        $j(this).html(downpay_html[e]);
                    }
                });

                initLoanCalculator(selector);

                selector.change(
                    function(){
                        var el = $j(this);
                        initLoanCalculator(el);
                    }
                );

                $j("form.loan.calculator.form").bind("submit", function() {
                    var html = "";
                    settings["period"]=(dropdown_period.val())*1;
                    var total_rate = settings["period"] * settings["rate"];
                    var total_fee_amount = 0;
                    var paid = ( amount.val()*1 ) - (settings["downpay"] * ( amount.val()*1 / 100 ));
                    for ( var v=0; v < settings["period"]; v++ ) {
                        total_fee_amount += (( settings["rate"]*1 ) * paid / 100 );
                        paid = ( ( settings["rate"]*1 ) * paid / 100 ) + paid;
                    }
                    var monthly_payment = paid/( settings["period"]*1*12 );
                    var html = "";
                    var total_month = 1;
                    for ( var v=1; v < (settings["period"]*1*12+1); v++ ) {
                        html += "<tr><td>Month " + v + "</td><td>" + monthly_payment.toFixed(2) + " " + settings["currency"] + "</td></tr>";
                    }
                    html = "<table width=100% cellpadding=5 cellspacing=0 border=1><tbody>" + html + "</tbody></table>";
                    html += "<h3>Fee amount: " + total_fee_amount.toFixed(2) + "</h3>";
                    html += "<h3>Total price: " + (total_fee_amount + ( amount.val()*1 )).toFixed(2) + "</h3>";
                    AT_Application.ModalBox({"width": 940, "handler": "prepend"},html);
                    return false;
                });
            });
        </script>
        ';


        if ( !empty($description) ) {
            $description = '<p>' . $description . '</p>';
        }

        if ( isset( $instance['type'] ) && ( $instance['type'] == 'page_content' ) ) {
            echo '<div class="wj_loan_calculators">';
            // echo '<h3>' . $title . '</h3>';
            echo $html;
            echo $script;
            echo '</div>';
        } else {
            echo $before_widget;
            // echo "<h3>" . $title . "</h3>";
            echo $html;
            echo $script;
            echo $description;
            echo $after_widget;
        }
    }
  
    /* Store */
    public function update( $new_instance, $old_instance ) {  
        $instance = $old_instance; 
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['description'] = strip_tags( $new_instance['description'] );

        return $instance;
    }

    /* Settings */
    public function form($instance) {
    $defaults = array( 'title' => 'Loan calculator', 'description' => 'Website powered by WinterJuice theme');
        $instance = wp_parse_args( (array) $instance, $defaults );

        echo '
            <p>
                <label for="' . $this->get_field_id( 'title' ) . '">Widget Title:</label>
                <input class="widefat" id="' . $this->get_field_id( 'title' ) .'" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" />
            </p>

            <p>
                <label for="' . $this->get_field_id( 'title' ) . '">Custom Text:</label>
                <input class="widefat" id="' . $this->get_field_id( 'description' ) . '" name="' . $this->get_field_name( 'description' ) . '" value="' . $instance['description'] . '" />
            </p>';
        $html = '<p style="text-align: center">' . __('Loan Calculator options available at the Site Options &rarr; Loans Options page.', AT_ADMIN_TEXTDOMAIN ) . '</p>';
        echo $html;
    }
}
