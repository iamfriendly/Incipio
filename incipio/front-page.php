<?php

    /* ======================================================================================

   This is the default "Front Page". WordPress will, by default, use this template when it exists
   which it does, because you're reading this. However, there is an option in the options panel
   which allows you to override that default, meaning you don't need to rename or delete this
   file to be able to use your blog home page or a normal page as your 'front' page.

   This template is built as a series of widget rows meaning you have as much flexibility 
   as possible.

   There are 2 distinct sections to the home page (other than the header and footer). Each 
   section contains 4 widget rows giving you a total of 8 available on the home page (plus
   the header/footer and optional below menu/above content sidebars). Each of the two sections
   is separated with a 'gap' showing through to the background. But within each section, the 
   4 rows are not separated, but they do still have space below each row to give vertical
   separation.

   You could also add more, should you wish, by using the built in hooks that we've provided.

   The Below Menu/Above Content Sidebar, which in this template's case would be above the main
   slider row (by default) can be turned on/off in the theme options panel under "Home Page".
   There's an option called "show_below_menu_sidebar_on_front_page" which, if ticked, shows
   this sidebar.

    ====================================================================================== */

    get_header();

?>
  
  <?php do_action( 'incipio_front_page_before_section_1' ); ?>


  <?php do_action( 'incipio_front_page_section_1' ); ?>


  <?php do_action( 'incipio_front_page_between_sections' ); ?>

  <?php do_action( 'incipio_front_page_section_2' ); ?>


  <?php do_action( 'incipio_front_page_after_section_2' ); ?>


<?php get_footer(); ?>