<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
elgg.provide('quebx.slide_menu');

quebx.slide_menu.init = function() {
  function slideMenu() {
    var activeState = $("#menu-container .menu-list").hasClass("active");
    $("#menu-container .menu-list").animate(
      {
        left: activeState ? "-150%" : "0%"
      },
      400
    );
  }
  $("#menu-wrapper").click(function(event) {
    event.stopPropagation();
    $("#hamburger-menu").toggleClass("open");
    $("#menu-container .menu-list").toggleClass("active");
    slideMenu();

    $("body").toggleClass("overflow-hidden");
  });

  $(".menu-list").find(".accordion-toggle").click(function() {
    $(this).next().toggleClass("open").slideToggle("fast");
    //var activeState = $(this).hasClass("active");
    //$(this).animate({left:activeState ? "150%" : "-150%"}, 400);
    $(this).toggleClass("active-tab").find(".menu-link").toggleClass("active");

    $(".menu-list .accordion-content")
      .not($(this).next())
      .slideUp("fast")
      .removeClass("open");
    $(".menu-list .accordion-toggle")
      .not(jQuery(this))
      .removeClass("active-tab")
      .find(".menu-link")
      .removeClass("active");
  });
}; // jQuery load

elgg.register_hook_handler('init', 'system', quebx.slide_menu.init);
elgg.register_hook_handler('success', 'quebx:slide_menu', quebx.slide_menu.init, 500);
