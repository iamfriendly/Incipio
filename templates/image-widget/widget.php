<?php

	/* ===========================================================================================

	This template file overrides the markup for the Themeists Image Widget.

    =========================================================================================== */

	// Block direct requests
	if ( !defined( 'ABSPATH' ) )
		die('-1');


	//If this is from the the Home Page Slider sidebar ( $args['id'] == 'front_section_1_row_1' ) 
	if( $args['id'] == 'front_section_1_row_1' )
	{
		?>
		{{MARKUP FOR THIS SIDEBAR}}
		<?php
	}
	else
	{


		echo $before_widget;
		
			//output the image
			if( !empty( $imageurl ) )
			{

				if( $link )
				{
					echo '<a class="'.$this->widget_options['classname'].'-image-link" href="'.$link.'" target="'.$linktarget.'">';
				}

					if( $imageurl )
					{

						echo '<img src="'.$imageurl.'" style="';
						
						if( !empty( $width ) && is_numeric( $width ) )
						{
							echo "max-width: {$width}px;";
						}
						
						if( !empty( $height ) && is_numeric( $height ) )
						{
							echo "max-height: {$height}px;";
						}

						echo "\"";

						if( !empty( $align ) && $align != 'none' )
						{
							echo " class=\"align{$align}\"";
						}

						if( !empty( $alt ) )
						{
							echo " alt=\"{$alt}\"";
						}
						else
						{
							echo " alt=\"{$title}\"";
						}

						echo " />";

					}

				if( $link ) { echo '</a>'; }

			}

			//Output the title
			if( !empty( $title ) ) { echo $before_title . $title . $after_title; }

			//Output the caption
			if( !empty( $description ) )
			{

				echo '<div class="'.$this->widget_options['classname'].'-description" >';
				echo wpautop( $description );
				echo "</div>";

			}

		echo $after_widget;


	}

?>