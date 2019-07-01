$(document).ready(function() {
    (function() {
        var cb_cal_input1 = "#cb_cal_input1";
        var cb_cal_input2 = "#cb_cal_input2";
        var cb_cal_style1 = "cb_cal_from";
        var cb_cal_style2 = "cb_cal_to";
        var cb_cal_text = ".cb_cal_text";
        var cb_cal_button = ".cb_cal_button";
        var max_days_interval = 7;

        var now = new Date();

        var common_configs = {
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            minDate: null,
            maxDate: 0
        };


        function convert_str_date(value) {
            return $.datepicker.parseDate(common_configs.dateFormat, value);
        }


        function convert_date_str(value) {
            return $.datepicker.formatDate(common_configs.dateFormat, value);
        }

        function get_date(input_id) {
            var value = convert_str_date($(input_id).val());
            if (value) {
                return value.getTime();
            }
            return null;
        }

        function get_min_date() {
            var selected = convert_str_date($(cb_cal_input1).val());
            selected.setDate(selected.getDate() - max_days_interval);
            return selected.getTime();
        }

        function get_max_date() {
            var selected = convert_str_date($(cb_cal_input1).val());
            selected.setDate(selected.getDate() + max_days_interval);
            return selected.getTime();
        }

        function is_selected(limit) {
            return function(date) {
                var date1 = get_date(cb_cal_input1);
                var date2 = get_date(cb_cal_input2);
                var current = date.getTime();
                return [current <= now && (!limit || (current <= get_max_date() && current >= get_min_date())), (current == date1) || (current >= date1 && current <= date2) ? "cb-highlight" : ""];
            }
        }

        $(cb_cal_input1).datepicker({
            ...common_configs,
            beforeShow: function(input, inst) {
                $(inst.dpDiv).addClass(cb_cal_style1);
            },
            beforeShowDay: is_selected(false),
            onClose: function(dateText, inst) {
                if (this.date_changed) {
                    $(cb_cal_input2).datepicker("show");
                    this.date_changed = false;
                }
                $(inst.dpDiv).removeClass(cb_cal_style1);
            },
            onSelect: function(dateText, inst) {
                $(cb_cal_input2).val("");
                this.date_changed = true;
            }
        });

        $(cb_cal_input2).datepicker({
            ...common_configs,
            beforeShow: function(input, inst) {
                $(inst.dpDiv).addClass(cb_cal_style2);
            },
            showAnim: 0,
            beforeShowDay: is_selected(true),
            onSelect: function(dateText, inst) {
                var date1 = get_date(cb_cal_input1);
                var date2 = convert_str_date(dateText);
                if (date2 < date1) {
                    $(cb_cal_input2).val($(cb_cal_input1).val());
                    $(cb_cal_input1).val(dateText);
                }
            },
            onClose: function(dateText, inst) {
                if (!dateText) {
                    $(cb_cal_input2).val($(cb_cal_input1).val());
                } else {

                }
                $(inst.dpDiv).removeClass(cb_cal_style2);

                var date1 = $(cb_cal_input1).val();
                var date2 = $(cb_cal_input2).val();

                if (date1 == date2) {
                    $(cb_cal_text).text(date1);
                } else {
                    $(cb_cal_text).text(date1 + " - " + date2);
                }
                OCA.EosTrashbin.App.fileList.reload()
            }
        });

        $(cb_cal_button).click(function() {
            $(cb_cal_input1).datepicker("show");
        });
    })();
});