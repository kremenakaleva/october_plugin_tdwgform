<?php namespace Pensoft\Tdwgform\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Multiwebinc\Recaptcha\Validators\RecaptchaValidator;
use Pensoft\Calendar\Models\Entry;
use Pensoft\Tdwgform\Models\Data;
use Pensoft\Tdwgform\Models\DiscountOptions;
use October\Rain\Support\Facades\Flash;
use Pensoft\Tdwgform\Models\Products;
use RainLab\Location\Models\Country;
use ValidationException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Validator;
use Redirect;

/**
 * Form Component
 */
class Form extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'TDWG Form Component',
            'description' => 'No description provided yet...'
        ];
    }

	public function defineProperties()
	{
		return [
			'recaptcha_key' => [
				'title' => 'Recaptcha site key',
				'type' => 'string',
				'default' => ''
			],
		];
	}

	public function onRun(){
		$this->page['countries'] = $this->countries();
		$this->page['discount_options'] = $this->discount_options();
	}

	public function countries(){
		return Country::orderBy('name')->get();
	}

	public function discount_options(){
		return DiscountOptions::get();
	}

	public function onTicketsList(){
    	$tickets = $this->discount_options();
    	if(post('type') == 'virtual'){
			$tickets = $tickets->map(function ($t) {
				$t->amount = $t->amount_virtual;
				return $t;
			});
		}
		return $tickets->toArray();
	}

	public function onSubmit(){
		$type = \Input::get('type');
		$prefix = \Input::get('prefix');
		$first_name = \Input::get('first_name');
		$middle_name = \Input::get('middle_name');
		$last_name = \Input::get('last_name');
		$suffix = \Input::get('suffix');
		$email = \Input::get('email');
		$verify_email = \Input::get('verify_email');
		$first_name_tag = \Input::get('first_name_tag');
		$last_name_tag = \Input::get('last_name_tag');
		$institution = \Input::get('institution');
		$title = \Input::get('title');
		$city = \Input::get('city');
		$country = \Input::get('country');
		$region = \Input::get('region');
		$postal_code = \Input::get('postal_code');
		$address = \Input::get('address');
		$phone = \Input::get('phone');
		$fax = \Input::get('fax');
		$emergency_contact_name = \Input::get('emergency_contact_name');
		$emergency_contact_phone = \Input::get('emergency_contact_phone');
		$comments = \Input::get('comments');
		$payment_options = \Input::get('payment_options');
		$group_members_list = \Input::get('group_members_list');
		$billing_details = \Input::get('billing_details');
		$invoice_email = \Input::get('invoice_email');
		$discount_code = \Input::get('discount_code');
		$discount_options = \Input::get('discount_options');
		$accompayning_person = \Input::get('accompanying_person');
		$help_others = \Input::get('help_others');

		$validator = Validator::make(
			[
				'type' => $type,
				'prefix' => $prefix,
				'first_name' => $first_name,
				'last_name' => $last_name,
				'email' => $email,
				'verify_email' => $verify_email,
				'city' => $city,
				'address' => $address,
				'postal_code' => $postal_code,
				'country' => $country,
				'emergency_contact_name' => $emergency_contact_name,
				'emergency_contact_phone' => $emergency_contact_phone,
				'payment_options' => $payment_options[0],
				'group_members_list' => $group_members_list,
				'billing_details' => $billing_details,
				'invoice_email' => $invoice_email,
				'discount_options' => $discount_options,
//				'g-recaptcha-response' => \Input::get('g-recaptcha-response'),
			],
			[
				'type' => 'required|string',
				'prefix' => 'required|string|min:2',
				'first_name' => 'required|string|min:2',
				'last_name' => 'required|string|min:2',
				'email' => 'required|between:6,255|email|unique:pensoft_tdwgform_data,email',
				'verify_email' => 'required_with:email|same:email',
				'country' => 'required|integer',
				'city' => 'required|string',
				'address' => 'required|string',
				'postal_code' => 'required|string',
				'emergency_contact_name' => 'required|string|min:2',
				'emergency_contact_phone' => 'required|string|min:2',
				'payment_options' => 'required',
				'discount_code' => 'string|min:2|unique:pensoft_tdwgform_data',
				'discount_options' => 'required|integer',
				'group_members_list' => 'required_if:payment_options,group_invoice,string',
				'billing_details' => 'required_if:payment_options,group_invoice,string',
				'invoice_email' => 'required_if:payment_options,group_invoice,email',
//				'g-recaptcha-response' => [
//					'required',
//					new RecaptchaValidator\,
//				],
			]
		);

		if($payment_options[0] == 'group_invoice'){
			if (!filter_var($invoice_email, FILTER_VALIDATE_EMAIL)) {
				$err = "Invalid invoice email format";
				throw new ValidationException(['invoice_email' => $err]);
			}
		};


		if($validator->fails()){
			Flash::error($validator->messages()->first());
		}else{
			$product_1 = Products::where('ticket_id', (int)$discount_options)
				->where('type', $type)
				->whereRaw('regular = CASE WHEN (early_booking_date >= now() AND type = \'physical\') THEN false ELSE true END')
				->where('accompanying_person', 'false')
				->where('help_others', 'false')
				->first()->toArray();

			//accompayning person
			$product_2 = [];
			if($accompayning_person){
				$product_2 = Products::where('accompanying_person', 'true')->first()->toArray();
			}

			//help others
			$product_3 = [];
			if($help_others){
				$product_3 = Products::where('help_others', 'true')->first()->toArray();
			}

			$products = array($product_1['product_id'], $product_2['product_id'], $product_3['product_id']);
			$data = [
						'products' => $products,
						'dicount_code' => $discount_code,
						'email' => $email,
					];
			$json = json_encode($data, true);

			$httpResponse = \Http::post(env('TDWG_REQUEST_URL'), function($http) use($json){

				$http->header('Accept', 'application/vnd.twitchtv.v5+json');
				$http->header('Content-Type', 'application/json');
				// Sends data with the request
				$http->data($json);

				$http->setOption(CURLOPT_RETURNTRANSFER, true);

			});

			if ($httpResponse->code != 200) {
				throw new \ApplicationException(sprintf('Pensoft API error: %s', $httpResponse->body));
			}
			$response = json_decode($httpResponse->body, true);
			if (!is_array($response)) {
				throw new \ApplicationException('Pensoft API error. Invalid response.');
			}
			if (isset($response['error'])) {
				throw new \ApplicationException(sprintf('Pensoft API error: %s', $response['error']));
			}
			if (!isset($response['uri']) || !is_string($response['uri'])) {
				throw new \ApplicationException('Pensoft API did not respond with a proper URI.');
			}
			$uri = rawurlencode($response['uri']);
//			\Cache::put($cacheKey, $uri = rawurlencode($response['uri']), 4320);
//			return $uri;

			dd($uri);

			$data = new Data();
			$data->type = $type;
			$data->prefix = $prefix;
			$data->first_name = $first_name;
			$data->middle_name = $middle_name;
			$data->last_name = $last_name;
			$data->suffix = $suffix;
			$data->email = $email;
			$data->first_name_tag = $first_name_tag;
			$data->last_name_tag = $last_name_tag;
			$data->institution = $institution;
			$data->title = $title;
			$data->city = $city;
			$data->country = $country;
			$data->region = $region;
			$data->postal_code = $postal_code;
			$data->address = $address;
			$data->phone = $phone;
			$data->fax = $fax;
			$data->emergency_contact_name = $emergency_contact_name;
			$data->emergency_contact_phone = $emergency_contact_phone;
			$data->comments = $comments;
			$data->payment_options = $payment_options[0];
			$data->invoice_group_members = ($payment_options[0] == 'group_invoice') ? $group_members_list : null;
			$data->billing_details = ($payment_options[0] == 'group_invoice') ? $billing_details : null;
			$data->invoice_email =  ($payment_options[0] == 'group_invoice') ? $invoice_email : null;
			$data->discount_code = $discount_code;
			$data->discount_option_id =  (int)$discount_options;
			$data->accompanying_person = (int)$accompayning_person;
			$data->help_others = (int)$help_others;

			$data->save();

			$entry = Entry::where('id', env('TDWG_ID'))->first();
			Flash::success($entry->thank_you_message);


		}


	}
}
