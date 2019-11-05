(function(blocks, element) {
  console.log("Register block");
  var el = element.createElement;

  var blockStyle = {
    backgroundColor: "#900",
    color: "#fff",
    padding: "20px"
  };

  console.log("Register block", blocks, el);
  blocks.registerBlockType("gutenberg-examples/example-01-basic", {
    title: "Example: Basic",
    icon: "universal-access-alt",
    category: "layout",
    example: {},
    edit: function() {
      return el(
        "p",
        { style: blockStyle },
        "Hello World, step 1 (from the editor)."
      );
    },
    save: function() {
      return el(
        "p",
        { style: blockStyle },
        "Hello World, step 1 (from the frontend)."
      );
    }
  });
})(window.wp.blocks, window.wp.element);
