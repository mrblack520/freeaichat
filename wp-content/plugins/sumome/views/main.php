<?php
$itemArray = [
    'My Apps'       => [
        'data-name' => 'sumome-control-apps',
        'class'     => 'sumo-apps',
        'columns'   => 2,
        'data-type' => 'sumome-app'
    ],
    'Store'         => [
        'data-name' => 'sumome-control-store',
        'class'     => 'sumo-store',
        'columns'   => 2,
        'data-type' => 'sumome-app'
    ],
    'Notifications' => [
        'data-name' => 'sumome-control-notifications',
        'class'     => 'sumo-notifications',
        'columns'   => 1,
        'data-type' => 'sumome-app'
    ],
    'Statistics'    => [
        'data-name' => 'sumome-control-statistics',
        'class'     => 'sumome-popup-no-dim sumo-statistics',
        'columns'   => 1
    ],
    'I Need Help'   => [
        'data-name' => 'sumome-control-help',
        'class'     => 'sumome-control-help sumome-popup-no-dim',
        'columns'   => 1
    ],
    'About'         => [
        'data-name' => 'sumome-control-about',
        'class'     => 'sumome-tile-about sumome-popup-no-dim',
        'columns'   => 1
    ],
    'Sumo Settings' => [
        'data-name' => 'sumome-control-settings',
        'class'     => 'sumo-settings',
        'columns'   => 1,
        'data-type' => 'sumome-app'
    ]
];
?>
<div class="sumome-plugin-main-wrapper">
    <div class="sumome-logged-in-container">
        <!-- Header -->

        <div class="header-banner"></div>

        <div class="items">
            <?php foreach($itemArray as $title => $parameters): ?>
                <div
                    <?php
                    foreach($parameters as $parameterName => $parameterValue):
                        echo $parameterName . '="' . ($parameterName === 'class' ? $parameterValue . ' item-tile' : $parameterValue) . '" ';
                    endforeach;
                    ?>
                        data-title="<?php echo $title; ?>"
                >
                    <?php if($parameters['columns'] > 1): ?>
                        <div class="item-tile-background"></div>
                    <?php endif; ?>

                    <div class="item-tile-title"><?php echo $title; ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="tabbed-content-container">
            <div class="back-logged-in">Back</div>
            <div class="content"></div>
        </div>
    </div>


    <div class="sumome-plugin-main main-bottom">
        <!-- Review -->
        <div class="row row3">
            <div class="large-12 columns">
                <div class="list-bullet">
                    <h4 class="list-number-title">Leave a Review!</h4>
                </div>
                <div class="sumome-instructions">We will love you forever if you leave an <a
                            href="https://wordpress.org/support/view/plugin-reviews/sumome" target="_blank">honest
                        review here</a> of the Sumo plugin.
                </div>
            </div>
        </div>

        <!-- Help -->
        <div class="row">
            <div class="large-12 columns footer">
                <h4 class="list-number-title">Need Help?</h4>
                <div class="sumome-help">
                    <span>Take a look at our <a target="_blank" href="https://help.sumome.com/">help page</a> to see our frequently answered</span>
                    <span>questions or <a target="_blank" href="https://help.sumome.com/hc/en-us/requests/new">send us a message</a> and we will get back to you asap.</span>
                </div>
            </div>
        </div>


    </div>
</div>

<div class="sumome-logged-in-container-overlay"></div>
<?php
include_once 'popup.php';
?>


