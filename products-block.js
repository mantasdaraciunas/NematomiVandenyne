console.log("Registering Block");
wp.blocks.registerBlockType("NematomiVandenyne/products-block", {
  title: wp.i18n.__("Mantas"),
  description: wp.i18n.__("Handpicked Producs"),
  icon: "universal-access-alt",
  category: "common",

  edit: function() {
    return wp.element.createElement(
      "p",
      { className: "custom-block" },
      "Hello World"
    );
  },

  save: function() {
    return wp.element.createElement(
      "p",
      { className: "custom-block" },
      "Saves in post Content"
    );
  }
});
