<?php namespace App\Controllers;

class Functions
{
	public $agents = [
	    'Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.2.15 Version/10.10',
	    'Opera/9.64 (Windows NT 5.1; U; ru) Presto/2.1.1',
	    'Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4',
	    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
	'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
	'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
	    'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1',
	    'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0',
	    'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36'
	];


	// http://www.qoo10.sg/gmkt.inc/Search/SearchResultAjaxTemplate.aspx?minishop_bar_onoff=N&sell_coupon_cust_no=+VHRIxvG8sTE3SvV2+lxhA==&SellerCooponDisplay=N&sell_cust_no=%2BVHRIxvG8sTE3SvV2%2BlxhA%3D%3D&theme_sid=0&global_yn=N&qid=0&search_mode=basic&fbidx=-1&sortType=SORT_RANK_POINT&dispType=UIG4&filterDelivery=NNNNNANNNNNNNN&search_global_yn=N&shipto=ALL&is_research_yn=Y&coupon_filter_no=0&partial=on&paging_value=1&curPage=2&pageSize=120&ajax_search_type=M&___cache_expire___=1508913018211

	public $fields = [
		'likes',
		'solds'
	];

	public $paramMorePorducts = [
		'minishop_bar_onoff' => 'N',
		'sell_coupon_cust_no' => 'VHRIxvG8sTE3SvV2 lxhA==',
		'SellerCooponDisplay' => 'N',
		'sell_cust_no' => '+VHRIxvG8sTE3SvV2+lxhA==',
		'theme_sid' => '0',
		'global_yn' => 'N',
		'qid' => '0',
		'search_mode' => 'basic',
		'fbidx' => '-1',
		'sortType' => 'SORT_RANK_POINT',
		'dispType' => 'UIG4',
		'filterDelivery' => 'NNNNNANNNNNNNN',
		'search_global_yn' => 'N',
		'shipto' => 'ALL',
		'is_research_yn' => 'Y',
		'coupon_filter_no' => '0',
		'partial' => 'on',
		'paging_value' => '1',
		'curPage' => '2',
		'pageSize' => '120',
		'ajax_search_type' => 'M',
		'___cache_expire___' => '1508913018211',
	];

	public function run($post = [], $agent = 'Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4')
	{
		if (!isset($post['url'])) {
			return [];
		}

		if (isset($_POST['field'])) {
			if (in_array($_POST['field'], $this->fields)) {
				$field = $_POST['field'];

				if ($field == 'likes') {
					$href = $post['url'] . '?search_mode=basic';
					$href = str_replace('??', '?', $href);
					$page = $this->runCurlPage($href, $agent);
					$html = str_get_html($page);
					$getDom = $this->formedProductFile($html, true);

					return $this->getLikeProducts();
				}

				if ($field == 'solds') {
					return $this->getSoldProducts();
				}
			}
		}

		// $urlDeliberyDays = "http://list.qoo10.sg/gmkt.inc/swe_GoodsAjaxService.asmx/GetStandardDeliveryPeriodList";

		// $params = [
		// 	'___cache_expire___' => '1508844781661',
		// 	'delivery_nation_cd' => 'SG',
		// 	'delivery_option_code' => 'RM',
		// 	'global_order_type' => 'L',
		// 	'start_nation_cd' => 'SG',
		// 	'transc_cd' => '100000020'
		// ];

		// if ($data = $this->parsePage($page)) {
		// 	return $data;
		// }

		// $page = $this->runCurlPage($post['url'], $agent);

		// $html = str_get_html($page);
		// if (!$html) {
			$href = $post['url'] . '?search_mode=basic';
			$href = str_replace('??', '?', $href);
			$data = $this->curlShopProducts($href, $agent);

			return $data;
		// }

		$agentKey = $this->getAgent();
		$newAgent = $this->agents[$agentKey];

		$this->run($post['url'], $newAgent);
	}

	public function runCurlPage($url, $agent)
	{
		$ch = curl_init($url);

		// curl_setopt($ch, CURLOPT_URL, $post['url']);
		// curl_setopt($ch, CURLOPT_PROXY, "79.136.33.142");
		// curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);

		curl_setopt($ch, CURLOPT_HEADER, true);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_NOBODY, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$page = curl_exec($ch);
		curl_close($ch);
		unset($ch);

		return $page;
	}

	public function curlShopProducts($url, $agent)
	{
		if (!isset($url)) {
			return [];
		}

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, false);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_NOBODY, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$page = curl_exec($ch);
		curl_close($ch);
		unset($ch);

		if ($data = $this->parsePage($page)) {
			return $data;
		}

		$agentKey = $this->getAgent();
		$newAgent = $this->agents[$agentKey];

		$this->run($url, $newAgent);
	}

	public function getAgent()
	{
		return array_rand($this->agents, 1);
	}

	public function parsePage($page)
	{
		$html = str_get_html($page);

		/**
		 * Create data file.csv
		 * product informatons
		 */
		$getDom = $this->formedProductFile($html);

		// if (!$getDom) {
 	// 		return [];
		// }

		$data = [];
		if (!empty($html) && !is_null($html)) {

			$data = [
				'seller_ame' => [
					'label' => 'Seller Name',
					'value' => $this->getSellerName($html),
					'class' => 't_lable',
					'type' => 'text',
				],
				'location' => [
					'label' => 'Location',
					'value' => $this->getLocation($html),
					'class' => 't_lable',
					'type' => 'text',
				],
				'seller_id' => [
					'label' => 'Seller ID',
					'value' => $this->getSellerId($html),
					'class' => 't_lable',
					'type' => 'text',
				],
				'seller_rating' => [
					'label' => 'Seller Rating',
					'value' => $this->getSellerRating($html),
					'class' => 't_lable',
					'type' => 'text',
				],
				'seller_fellows' => [
					'label' => 'Fellows',
					'value' => $this->getFellows($html),
					'class' => 't_lable',
					'type' => 'text',
				],
				// 'top_seller_star' => [
				// 	'label' => 'Top Seller Star',
				// 	'value' => $this->getTopSellerStar($html),
				// 	'class' => 't_lable',
				// 	'type' => 'text',
				// ],
				'seller_brands' => [
					'label' => 'Seller Brands',
					'value' => $this->getSellerBrands($html),
					'class' => 't_lable',
					'type' => 'text',
				],
				'seller_categories' => [
					'label' => 'Seller Categories',
					'value' => $this->getSellerCategories($html),
					'class' => 't_lable',
					'type' => 'text',
				],
				// 'review_numbers' => [
				// 	'label' => 'Review Numbers',
				// 	'class' => 't_lable',
				// 	'type' => 'table',
				// 	'values' => [
				// 		'positive' => [
				// 			'label' => 'Positive',
				// 			'value' => '2',
				// 		],
				// 		'neutral' => [
				// 			'label' => 'Neutral',
				// 			'value' => '0',
				// 		],
				// 		'negative' => [
				// 			'label' => 'Negative',
				// 			'value' => '0',
				// 		],
				// 	],
				// ],
				// 'total_seller_rating' => [
				// 	'label' => 'Total Seller Rating',
				// 	'value' => '0 Seller Ratings and Reviews',
				// 	'class' => 't_lable',
				// 	'type' => 'text',
				// ],
				// 'seller_reviews' => [
				// 	'label' => 'Seller Reviews',
				// 	'href' => 'http://devwordpress.club/lazadatool//seller_reviews.php?id=48135',
				// 	'value' => 'View Seller Reviews',
				// 	'class' => 't_lable',
				// 	'type' => 'link',
				// ],
				// 'shipped_on_time' => [
				// 	'label' => 'Shipped On Time',
				// 	'value' => '0% ( 0 % better than other sellers in same category )',
				// 	'class' => 't_lable',
				// 	'type' => 'text',
				// ],
				// 'product_reviews' => [
				// 	'label' => 'Product Reviews',
				// 	'href' => 'http://devwordpress.club/lazadatool//product_reviews.php?id=48135',
				// 	'value' => 'View Seller Reviews',
				// 	'class' => 't_lable',
				// 	'type' => 'link',
				// ],
				'count_products' => [
					'label' => 'All Items',
					'value' => $this->getItems($html),
					'class' => 't_lable',
					'type' => 'text',
				],
				'total_product_reviews' => [
					'label' => 'Total Product Reviews',
					'value' => $this->getTotalProductReviews($html),
					'class' => 't_lable',
					'type' => 'text',
				],
				// 'shipping_from' => [
				// 	'label' => 'Shipping From',
				// 	'value' => $this->getShoppingFrom(),
				// 	'class' => 't_lable',
				// 	'type' => 'text',
				// ],
				'payment_method' => [
					'label' => 'Payment Method',
					'value' => $this->getPaymentMethod	(),
					'class' => 't_lable',
					'type' => 'text',
				],
				'items_sold' => [
					'label' => 'Items Sold Products',
					'value' => 'Loading..',//$this->getSoldProducts(),
					'class' => 't_lable',
					'type' => 'text',
					'preloader' => true,
					'field' => 'solds',
				],
				'shipping_from' => [
					'label' => 'Shipping From',
					'value' => $this->getShippingFrom(),
					'class' => 't_lable',
					'type' => 'text',
				],
				'countru_likes' => [
					'label' => 'Number Like Products',
					'value' => 'Loading..',//$this->getLikeProducts(),
					'class' => 't_lable',
					'preloader' => true,
					'field' => 'likes',
					'type' => 'text',
				],
				'range_of_price' => [
					'label' => 'Range of price',
					'value' => $this->getRangeOfPrice($html),
					'class' => 't_lable',
					'type' => 'text',
				],
				'number_discount' => [
					'label' => 'Number Discount',
					'value' => $this->getNumberDiscount($html),
					'class' => 't_lable',
					'type' => 'text',
				],
				'number_non_discount' => [
					'label' => 'Number Non Discount',
					'value' => $this->getNumberNonDiscount($html),
					'class' => 't_lable',
					'type' => 'text',
				],
				'main_category' => [
					'label' => 'Main Category',
					'value' => $this->getMainCategory($html),
					'class' => 't_lable',
					'type' => 'text',
				],
				// 'time_on_lazada' => [
				// 	'label' => 'Time On Lazada',
				// 	'value' => '2 months',
				// 	'class' => 't_lable',
				// 	'type' => 'text',
				// ],
				// 'size' => [
				// 	'label' => 'Size',
				// 	'value' => '2',
				// 	'class' => 't_lable',
				// 	'type' => 'text',
				// ],
			];

			foreach ($data as $key => &$item) {
				if ($item['value'] === null) {
					unset($data[$key]);
				}
			}

			return $data;
		}
	}

	public function getSellerName($html)
	{
		if (isset($html->find('.mshop_bar a img',0)->plaintext)) {
	 	 	return $html->find('.mshop_bar a img',0)->getAttribute('alt');
		}

		return null;
	}

	public function getSellerId($html)
	{
		if (isset($html->find('.mshop_bar a img',0)->plaintext)) {
	 	 	$str = mb_strtolower(trim($html->find('.mshop_bar a img',0)->getAttribute('alt')));
	 	 	return str_replace(' ', '', $str);
		}

		return null;
	}

	public function getLocation($html)
	{
		if (isset($html->find('div.inner .current span',0)->plaintext)) {
	 	 	return $html->find('div.inner .current span',0)->plaintext;
		}

		return null;
	}

	public function getSellerRating($html, $ceil = false)
	{
		$percent = 0;

		if (isset($html->find('span.mshop_rate dfn', 0)->plaintext)) {
			$percent = trim($html->find('span.mshop_rate dfn', 0)->plaintext, '%');
		}

		if ($percent) {
			$percent  = ($percent / 2) / 10;

			if ($ceil) {
				return $percent;
			}

			return $percent  . ' / 5';
		}

		return null;
	}

	public function getFellows($html)
	{
		$fellows = trim(trim($html->find('span.flw_num', 0)->plaintext), 'Fellows');

		if (!$fellows) {
			return null;
		}

		return $fellows;
	}

	public function getTopSellerStar($html)
	{
		$percent = $this->getSellerRating($html, true);

		if ($percent >= 5.9) {
			return 'yes';
		}

		return 'no';
	}

	public function getMainCategory($html)
	{
		$categories= '';
		$arayCategories = [];

		foreach ($html->find('#div_minishop_category_result ul li') as $category) {
			if (isset($category->find('span', 0)->plaintext)) {
				$arayCategories[] = $category->find('span', 0)->plaintext;
			}
		}

		if (sizeof($arayCategories) <= 0) {
			return null;
		}

		$arayCategories = array_filter($arayCategories);

		if (sizeof($arayCategories) > 0) {
			unset($arayCategories[0]);
		}

		if (sizeof($arayCategories) == 1) {
			return array_shift($arayCategories);
		}

		if (sizeof($arayCategories) > 1) {
			$href = $html->find('a#btn_allitem', 0)->href;

			$agent = 'Opera/9.64 (Windows NT 5.1; U; ru) Presto/2.1.1';
			$linkCategories = $this->curlMainCategory($href, $agent);

			// if (sizeof($linkCategories) > 0) {
			// 	foreach ($linkCategories as $key => $category) {
			// 		// $count[$key]['count'] = $this->curlCountProduct($category['href'], $agent);

			// 		$count[$key]['count'] = preg_replace("/[^0-9]/", '', $category['name']);

			// 		$count[$key]['name'] = $category['name'];
			// 	}
			// }

			// $name = [];
			// $nameNew = '';
			// $names = [];
			// foreach ($count as $key => $item) {
			// 	if (isset($item['name'])) {
			// 		$name = explode('(', $item['name']);

			// 		$nameNew = isset($name[0]) ? $name[0] : '';
			// 	}
			// 	$names[$key]['name'] = $nameNew;
			// 	$names[$key]['qty'] = $item['count'];
			// }

			$data = [];
			$name = '';
			$nameNew = '';
			foreach ($linkCategories as $key => $category) {
				if (isset($category['name'])) {
					$data[$key]['count'] = preg_replace("/[^0-9]/", '', $category['name']);
					$data[$key]['name'] = preg_replace("/[^A-z]/", ' ', $category['name']);
				}
			}

			$max = max($data);

			if (sizeof($data) > 0) {
				return $max['name'];
			}

			return null;
		}
	}

	public function getSellerCategories($html)
	{
		$categories= '';

		foreach ($html->find('#div_minishop_category_result ul li') as $category) {
			
			if (isset($category->find('span', 0)->plaintext)) {
				$categories .= $category->find('span', 0)->plaintext . ', ';
			}
		}

		if (empty($categories)) {
			return null;
		}

		return trim(substr($categories, 0, -2), 'All, ');
	}

	public function formedProductFile($html, $all = false)
	{
		$hrefs = $this->getHrefProducts($html);

		if (sizeof($hrefs) <= 0) {
			return false;
		}

		$agent = 'Opera/9.64 (Windows NT 5.1; U; ru) Presto/2.1.1';
		$product = [];

		if (!$all) {
			$products[] = $this->curlProduct($hrefs[0], $agent);
		}

		if ($all) {
			foreach ($hrefs as $href) {
				$products[] = $this->curlProduct($href, $agent);
			}
		}

		$likes = 0;
		$sold = 0;
		$shippingForm = [];
		$shippingFormString = '';

		$paymentMethodString = '';
		$paymentMethod = [];

		foreach ($products as $product) {
			if ($all) {
				$likes += isset($product['like']) && !empty($product['like']) ? $product['like'] : 0;
				$sold += isset($product['sold']) && !empty($product['sold']) ? $product['sold'] : 0;
			}

			if (!$all) {
				$shippingFormString .= isset($product['shipping_form']) && !empty($product['shipping_form']) ? $product['shipping_form'] . ', ' : 0;

				$paymentMethodString .= isset($product['payment_method']) && !empty($product['payment_method']) ? $product['payment_method'] . ', ' : 0;
			}
		}

		$expShippingFrom = explode(', ', $shippingFormString);

		if (sizeof($expShippingFrom) > 0) {
			$expShippingFrom = array_unique(array_filter($expShippingFrom));

			if (sizeof($expShippingFrom) > 0) {
				$shippingForm = array_shift($expShippingFrom);
			}
		}

		$expPaymentMethod = explode(', ', $paymentMethodString);

		if (sizeof($expPaymentMethod) > 0) {
			$paymentMethod = array_unique(array_filter($expPaymentMethod));
		}

		$path = __DIR__ . '/data.csv';

		$data = [
			'likes' => $likes,
			'solds' => $sold,
			'shipping_form' => $shippingForm,
			'payment_method' => $paymentMethod
		];

		$this->write_to_file(json_encode($data), $path);

		return true;
	}

	public static function write_to_file($data,$filename) {
        
        if (!$handle = fopen($filename, 'w+'))
        {
            return false;
        }

        if (@is_writable($filename))
        {
            if (fwrite($handle, $data) === FALSE)
            {
                return false;
            }

            fclose($handle);
            return true;
        } else {
            return false;
        }

    }

    public function curlCountProduct($href, $agent)
	{
		$ch = curl_init($href);
		curl_setopt($ch, CURLOPT_HEADER, true);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_NOBODY, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$page = curl_exec($ch);
		curl_close($ch);
		unset($ch);

		$html = str_get_html($page);

		return $this->getItems($html);
	}

    public function curlMainCategory($href, $agent)
	{
		$ch = curl_init($href);
		curl_setopt($ch, CURLOPT_HEADER, true);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_NOBODY, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$page = curl_exec($ch);
		curl_close($ch);
		unset($ch);

		$html = str_get_html($page);

		$link = [];
		foreach ($html->find('#div_minishop_category_result ul li') as $key => $category) {
			if (isset($category->find('a', 0)->href) && isset($category->find('a', 0)->plaintext)) {
				$link[$key]['href'] = $category->find('a', 0)->href;
				$link[$key]['name'] = $category->find('a', 0)->plaintext;
			}
		}

		$link = array_filter($link);

		return $link;
	}

	public function curlProduct($href, $agent)
	{
		$ch = curl_init($href);
		curl_setopt($ch, CURLOPT_HEADER, true);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_NOBODY, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$page = curl_exec($ch);
		curl_close($ch);
		unset($ch);

		$html = str_get_html($page);

		$like = null;
		if (isset($html->find('span.like strong', 0)->plaintext)) {
			$like = $html->find('span.like strong', 0)->plaintext;
		}

		$sold = null;
		if (isset($html->find('span.sold strong', 0)->plaintext)) {
			$sold = $html->find('span.sold strong', 0)->plaintext;
		}

		$shoppingFrom = null;
		if (isset($html->find('div#ItemInfoWrap2 table tbody tr', 1)->find('td', 0)->plaintext)) {
			$shoppingFrom = $html->find('div#ItemInfoWrap2 table tbody tr', 1)->find('td', 0)->plaintext;
		}

		$paymentMethod = null;
		$arrayPaymentMethod = [];
		if (isset($html->find('div#ItemInfoWrap2 table tbody tr', 4)->find('td', 0)->plaintext)) {
			$paymentMethod = $html->find('div#ItemInfoWrap2 table tbody tr', 4)->find('td', 0)->plaintext;

			$arrayPaymentMethod = $paymentMethod;
		}

		// $estimatedDeliveryDate = $html->find('div#ItemInfoWrap2 table tbody tr', 2)->outertext;

		// var_dump($estimatedDeliveryDate);

		$data = [
			'like' => $like,
			'sold' => $sold,
			'shipping_form' => $shoppingFrom,
			'payment_method' => $arrayPaymentMethod
			// 'estimatedDeliveryDate' => $estimatedDeliveryDate
		];

		return $data;
	}

	/**
	 * Get all href products
	 */
	public function getHrefProducts($html)
	{
		$hrefs = [];

		if ($html) {
			foreach ($html->find('#div_gallery_new ul li') as $key => $product) {
				
				if (isset($product->find('div.prc strong', 0)->plaintext)) {
					$hrefs[] = $product->find('a.quick', 0)->href;
				}
			}
		}
		
		return $hrefs;
	}

	public function getShoppingFrom()
	{
		$path = __DIR__ . '/data.csv';

		$data = json_decode(file_get_contents($path));

		return $data->shipping_form;
	}

	public function getPaymentMethod()
	{
		$path = __DIR__ . '/data.csv';

		$data = json_decode(file_get_contents($path));

		if (isset($data->payment_method)) {
			$paymentMethod = implode(', ', $data->payment_method);

			return $paymentMethod;
		}

		return '';
	}

	public function getSoldProducts()
	{
		$path = __DIR__ . '/data.csv';

		$data = json_decode(file_get_contents($path));

		return $data->solds;
	}

	public function getShippingFrom()
	{
		$path = __DIR__ . '/data.csv';

		$data = json_decode(file_get_contents($path));

		if (isset($data->shipping_form)) {
			return $data->shipping_form;
		}

		return '';
	}

	public function getLikeProducts()
	{
		$path = __DIR__ . '/data.csv';

		$data = json_decode(file_get_contents($path));

		return $data->likes;
	}

	public function getRangeOfPrice($html)
	{
		$pricesArray = [];

		foreach ($html->find('#div_gallery_new ul li') as $key => $product) {
			
			$priceText = '';
			if (isset($product->find('div.prc strong', 0)->plaintext)) {
				$priceText = $product->find('div.prc strong', 0)->plaintext;
				$pricesArray[$key] = trim($priceText, '$');
			}
		}

		if (sizeof($pricesArray) <= 0) {
			return null;
		}

		$minPrice = min($pricesArray);
		$maxPrice = max($pricesArray);

		return 'SGD ' . $minPrice .' - SGD ' . $maxPrice;
	}

	public function getNumberDiscount($html)
	{
		$pricesArray = [];

		foreach ($html->find('#div_gallery_new ul li') as $key => $product) {
			
			$priceText = '';
			if (isset($product->find('div.prc del', 0)->plaintext)) {
				$priceText = $product->find('div.prc strong', 0)->plaintext;
				$pricesArray[$key] = trim($priceText, '$');
			}
		}

		$count = sizeof($pricesArray);

		if ($count > 0) {
			return $count;
		}

		return null;
	}

	public function getNumberNonDiscount($html)
	{
		$pricesArray = [];

		foreach ($html->find('#div_gallery_new ul li') as $key => $product) {
			
			$priceText = '';
			if (!isset($product->find('div.prc del', 0)->plaintext)) {
				$priceText = $product->find('div.prc strong', 0)->plaintext;
				$pricesArray[$key] = trim($priceText, '$');
			}
		}

		$count = sizeof($pricesArray);

		return $count;
	}

	public function getItems($html)
	{
		if (isset($html->find('a#btn_allitem span', 0)->plaintext)) {
			return $html->find('a#btn_allitem span', 0)->plaintext;
		}

		return null;
	}

	public function getTotalProductReviews($html)
	{
		$pricesArray = [];

		foreach ($html->find('#div_gallery_new ul li') as $key => $product) {
			
			$priceText = '';
			if (isset($product->find('a.review strong', 0)->plaintext)) {
				$priceText = $product->find('a.review strong', 0)->plaintext;
				$pricesArray[$key] = $priceText;
			}
		}

		if (sizeof($pricesArray) <= 0) {
			return null;
		}

		$sumReviews = array_sum($pricesArray);

		return $sumReviews;
	}

	public function getSellerBrands($html)
	{
		$productsBrands = [];

		foreach ($html->find('#div_gallery_new ul li') as $key => $product) {
			if (isset($product->find('a.brand', 0)->plaintext)) {
				$productsBrands[$key] = $product->find('a.brand', 0)->plaintext;
			}
		}

		$productsBrands = array_unique($productsBrands);

		if (sizeof($productsBrands) > 0) {
			return implode(', ', $productsBrands);
		}

		return null;

	}
}