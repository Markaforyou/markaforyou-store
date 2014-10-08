<?php

class ControllerFeedLatestProductsRSS extends Controller {

    public function index() {
	if ($this->config->get('latest_products_rss_status')) {

	    $this->load->model('catalog/product');
	    $this->load->model('localisation/currency');

	    $this->load->model('tool/image');

	    $limit = $this->config->get('latest_products_rss_limit') ? $this->config->get('latest_products_rss_limit') : 100;
	    $show_price = $this->config->get('latest_products_rss_show_price');
	    $show_date = $this->config->get('latest_products_rss_show_date');
	    $show_image = $this->config->get('latest_products_rss_show_image');

	    if ($show_image) {
		$image_width = $this->config->get('latest_products_rss_image_width') ? $this->config->get('latest_products_rss_image_width') : 100;
		$image_height = $this->config->get('latest_products_rss_image_height') ? $this->config->get('latest_products_rss_image_height') : 100;
	    }

	    $products = $this->model_catalog_product->getLatestProducts($limit);

	    if (isset($this->request->get['currency'])) {
		$currency = $this->request->get['currency'];
	    } else {
		$currency = $this->currency->getCode();
	    }

	    $output = '<?xml version="1.0" encoding="UTF-8" ?>';
	    $output .= '<rss version="2.0">';
	    $output .= '<channel>';
	    $output .= '<title>' . $this->config->get('config_name') . '</title>';
	    $output .= '<description>' . $this->config->get('config_meta_description') . '</description>';
	    $output .= '<link>' . HTTP_SERVER . '</link>';

	    foreach ($products as $product) {
		if ($product['description']) {

		    $title = $product['name'];

		    $link = $this->url->link('product/product', 'product_id=' . $product['product_id']);

			if (VERSION < "1.5.0.3") {
				$link = str_replace('&', '&amp;', $link);
			}

		    $description = "";

		    if ($show_price) {
				if ((float)$product['special']) {
					$price = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), $currency, FALSE, TRUE);
                } else {
                    $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), $currency, FALSE, TRUE);
                }
				$description .= htmlspecialchars('<p><strong>Price: ' . $price . '</strong></p>');
		    }

		    if ($show_image) {
			$image_url = $this->model_tool_image->resize($product['image'], $image_width, $image_height);
			$description .= htmlspecialchars('<p><a href="' . $link . '"><img src="' . $image_url . '"></a></p>');
		    }

		    $description .= $product['description'];



		    $output .= '<item>';
		    $output .= '<title>' . $title . '</title>';
		    $output .= '<link>' . $link . '</link>';
		    $output .= '<description>' . $description . '</description>';

		    if ($show_date) {
			$output .= '<pubDate>' . date('D, j F Y H:i:s e', strtotime($product['date_added'])) . '</pubDate>';
		    }

		    $output .= '</item>';
		}
	    }

	    $output .= '</channel>';
	    $output .= '</rss>';

	    $this->response->addHeader('Content-Type: application/rss+xml');
	    $this->response->setOutput($output, 0);
	}
    }

}

?>