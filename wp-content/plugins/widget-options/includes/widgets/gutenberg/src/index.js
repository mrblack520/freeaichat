import "./attributes/sidebar";
import "./attributes/post-sidebar";
import "./attributes/customize-sidebar";

function addWidgetOptionAttributes(settings, name) {
  if (settings.attributes) {
    let isWidgetBlockEditor = document.body.classList.contains("widgets-php");
    let isWpCustomizer = document.body.classList.contains("wp-customizer");

    if (isWidgetBlockEditor || isWpCustomizer) {
      settings.attributes.extended_widget_opts_block = {
        type: "object",
        default: {},
      };

      if (isWpCustomizer) {
        settings.attributes.extended_widget_opts = {
          type: "object",
          default: {},
        };
      }
    } else {
      settings.attributes.extended_widget_opts = {
        type: "object",
        default: {},
      };
    }

    settings.attributes.extended_widget_opts_state = {
      type: "number",
      default: 0,
    };

    settings.attributes.extended_widget_opts_clientid = {
      type: "string",
      default: "",
    };
  }
  return settings;
}

wp.hooks.addFilter(
  "blocks.registerBlockType",
  "extended-widget-options/sidebar-component",
  addWidgetOptionAttributes
);
