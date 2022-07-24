<?php
	include("utilities.php");
	echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"style/job.css\">";

	if (Utilities::isMobile()){
		echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"style/job-m.css\">";
	} else {
		echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"style/job-d.css\">";
	}
?>