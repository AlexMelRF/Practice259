<?php

class View {
	public function generate ($content_view, $template_view, $data = null) {
		include 'application/views/'.$template_view;
		include 'application/views/'.$content_view;
	}
}

?>