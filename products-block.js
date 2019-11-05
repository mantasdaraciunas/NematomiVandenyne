(function() {
  var registerBlockType = wp.blocks.registerBlockType;

  registerBlockType("NematomiVandenyne/products-block", {
    title: __("GB Basic", "GB"),
    icon: "shield-alt",
    category: "common",

    edit: function(props) {
      return wp.element.createElement(
        "p",
        { className: props.className },
        "Hello World! — from the editor (01 Basic Block)."
      );
    }
  });
})();
