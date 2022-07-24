<?php
	include("utilities.php");
	echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"style/style.css\">";

	if (Utilities::isMobile()){
		echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"style/style-m.css\">";
	} else {
		echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"style/style-d.css\">";
	}
?>