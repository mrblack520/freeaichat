<div class="sumome-plugin-container">
    <div class="sumome-plugin-main">
        <div class="statistics-container">
            <div class="statistics"></div>
        </div>
        <div class="loading"><img src="<?php echo plugins_url('images/sumome-loading.gif', dirname(__FILE__)) ?>"></div>
    </div>
</div>
<script>
    jQuery(document).ready(function () {
        getSumomeStats();
    });

    function getSumomeStats() {
        const siteID = '<?php print get_option('sumome_site_id'); ?>';
        let statisticsDate = jQuery('.sumome-dashboard-date-select').val();
        if (statisticsDate == null) {
            statisticsDate = '<?php print date('Y-m-d')?>'; //default=last week
        }
        jQuery.ajax({
            url: 'https://sumome.com/apps/dashboard/stats',
            type: 'POST',
            dataType: 'json',
            beforeSend: function (req) {
                req.setRequestHeader('X-Sumo-Auth', '<?php print $_COOKIE['__smToken']?>');
            },
            xhrFields: {
                withCredentials: false
            },
            crossDomain: true,
            data: {'site_id': siteID, 'date': statisticsDate},
            success: function (data) {
                jQuery('.loading').hide();

                let returnText;
                if (data.htmlBody != null) {
                    returnText = data.htmlBody;
                } else {
                    returnText = '<h3 class="headline">' + data.headline + '</h3>';
                }

                jQuery('.statistics').html(returnText);
                statisticsDateDropdown();
                jQuery(".sumome-dashboard-date-select option[value='" + statisticsDate + "']").attr('selected', 'selected');
                jQuery('.statistics-container').show();
            },
        });
    }


    const padDateString = n => n < 10 ? `0${n}` : n.toString();

    const getDropDownDateFormat = (givenDate, plusDays = 0) => {
        const year = givenDate.getFullYear();
        const month = padDateString(givenDate.getMonth() + 1);
        const day = padDateString(givenDate.getDate() + plusDays);
        return `${year}-${month}-${day}`;
    };

    const statisticsDateDropdown = () => {
        const today = new Date();
        const thisSunday = new Date();
        thisSunday.setDate(today.getDate() - today.getDay());

        const thisWeek = getDropDownDateFormat(today, 6);
        const lastWeek = getDropDownDateFormat(thisSunday);

        const dropdownContent = `
        <select class="sumome-dashboard-date-select">
            <option value="${thisWeek}">This Week</option>
            <option value="${lastWeek}" selected="">Last Week</option>
        </select><br>`;

        jQuery('.statistics .headline').prepend(dropdownContent);
    };

    jQuery(document).on('change', '.sumome-dashboard-date-select', function () {
        getSumomeStats();
    });
</script>
