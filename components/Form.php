<?php namespace Pensoft\Tdwgform\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Multiwebinc\Recaptcha\Validators\RecaptchaValidator;
use Pensoft\Calendar\Models\Entry;
use Pensoft\Tdwgform\Models\Codes;
use Pensoft\Tdwgform\Models\Data;
use Pensoft\Tdwgform\Models\DiscountOptions;
use October\Rain\Support\Facades\Flash;
use Pensoft\Tdwgform\Models\Products;
use RainLab\Location\Models\Country;
use System\Models\MailSetting;
use ValidationException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Validator;
use Redirect;

/**
 * Form Component
 */
class Form extends ComponentBase {
    public function componentDetails() {
        return [
            'name' => 'TDWG Form Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties() {
        return [
            'recaptcha_key' => [
                'title' => 'Recaptcha site key',
                'type' => 'string',
                'default' => ''
            ],
        ];
    }

    public function onRun() {
        $this->page['countries'] = $this->countries();
        $this->page['discount_options'] = $this->discount_options();
        $this->page['data'] = null;
        if($this->param('registration_id')) {
            $this->page['data'] = Data::where('id', (int)$this->param('registration_id'))->first();
        }
    }

    public function countries() {
        return Country::orderBy('name')->get();
    }

    public function discount_options() {
        return DiscountOptions::orderBy('id', 'asc')->get();
    }

    public function onTicketsList() {
        $tickets = $this->discount_options();
        if(post('type') == 'virtual') {
            $tickets = $tickets->map(function($t) {
                $t->amount = $t->amount_virtual;
                return $t;
            });
        }
        return $tickets->toArray();
    }

    public function onCheckLowIncomeCountry() {
        if((int)post('country')) {
            $country = Country::where('id', (int)post('country'))->where('is_pinned', true)->first();
            if($country) return ['result' => 1];
        }
        return ['result' => 0];
    }

    public function onSubmit() {
        $registration_id = \Input::get('registration_id');
        $emailValidationRule = 'required|between:6,255|email|unique:pensoft_tdwgform_data,email';
        if($registration_id) {
            $emailValidationRule = 'required|between:6,255|email';
        }
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
        $address2 = \Input::get('address2');
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
		$accompanying_person_name = \Input::get('accompanying_person_name');
        $help_others = \Input::get('help_others');
        $accompanying_person_has_invoice = \Input::get('accompanying_person_has_invoice');
        $help_others_has_invoice = \Input::get('help_others_has_invoice');

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
				'g-recaptcha-response' => \Input::get('g-recaptcha-response'),
            ],
            [
                'type' => 'required|string',
                'first_name' => 'required|string|min:2',
                'last_name' => 'required|string|min:2',
                'email' => $emailValidationRule,
                'verify_email' => 'required_with:email|same:email',
                'country' => 'required|integer',
                'city' => 'required|string',
                'address' => 'required|string',
                'postal_code' => 'required|string',
                'emergency_contact_name' => 'required|string|min:2',
                'emergency_contact_phone' => 'required|string|min:2',
                'payment_options' => 'required',
                'discount_code' => 'string|min:4',
                'discount_options' => 'required|integer',
                'group_members_list' => 'required_if:payment_options,group_invoice,string',
                'billing_details' => 'required_if:payment_options,group_invoice,string',
                'invoice_email' => 'required_if:payment_options,group_invoice,email',
				'g-recaptcha-response' => [
					'required',
					new RecaptchaValidator(),
				],
            ]
        );


        if($validator->fails()) {
            Flash::error($validator->messages()->first());
        } else {

        	// more validation
			if($payment_options[0] == 'group_invoice') {
				if(!filter_var($invoice_email, FILTER_VALIDATE_EMAIL)) {
					$err = "Invalid invoice email format";
					throw new ValidationException(['invoice_email' => $err]);
				}
			}

			if($registration_id) {
				$lData = Data::where('email', $email)->where('id', '!=', $registration_id)->first();
				if($lData) {
					$err = "The email is already taken";
					throw new ValidationException(['email' => $err]);
				}
			}

			if($phone){
				if ($this->isValidTelephoneNumber($phone)) {
					$this->normalizeTelephoneNumber($phone);
				}else{
					$err = "The provided phone number is not valid";
					throw new ValidationException(['phone' => $err]);
				}
			}

			if($emergency_contact_phone){
				if ($this->isValidTelephoneNumber($emergency_contact_phone)) {
					$this->normalizeTelephoneNumber($emergency_contact_phone);
				}else{
					$err = "The provided emergency contact phone number is not valid";
					throw new ValidationException(['emergency_contact_phone' => $err]);
				}
			}


			if($discount_code){
				$discountCodeData = $this->checkDiscountCode($discount_code);
				if (!$discountCodeData){
					$err = "The provided discount code is not valid or is already used";
					throw new ValidationException(['discount_code' => $err]);
				}
			}


            if($registration_id) {
                $data = Data::find($registration_id);
            } else {
                $data = new Data();
            }

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
            $data->phone = $this->normalizeTelephoneNumber($phone);
            $data->address2 = $address2;
            $data->emergency_contact_name = $emergency_contact_name;
            $data->emergency_contact_phone = $emergency_contact_phone;
            $data->comments = $comments;
            $data->payment_options = $payment_options[0];
            $data->invoice_group_members = ($payment_options[0] == 'group_invoice') ? $group_members_list : null;
            $data->billing_details = ($payment_options[0] == 'group_invoice') ? $billing_details : null;
            $data->invoice_email = ($payment_options[0] == 'group_invoice') ? $invoice_email : null;
            $data->discount_code = ((int)$discount_options <> 1) ? null : $discount_code;
            $data->discount_option_id = (int)$discount_options;
            $data->accompanying_person = ($type == 'virtual') ? null : (int)$accompayning_person;
            $data->help_others = (int)$help_others;
			$data->accompanying_person_name = ($type == 'virtual') ? null : $accompanying_person_name;
			$data->accompanying_person_has_invoice = ($type == 'virtual' || !(int)$accompayning_person) ? null : $accompanying_person_has_invoice;
			$data->help_others_has_invoice = (!(int)$help_others) ? null : $help_others_has_invoice;

            $data->save();

            $recordID = $data->id;
            return Redirect::to('/registration-preview/' . $recordID);
        }
    }

    public function onFinishRegistration() {
        $ID = (int)post('ID');
        if((int)$ID) {
            $data = Data::where('id', (int)$ID)->where('submission_completed', false)->first();
            $data->submission_completed = 'true';
            $data->save();

            //mark code as used
			if($data->discount_code){
				$code = Codes::where('code', $data->discount_code)->first();
				$code->is_used = true;
				$code->save();
			}

			//SEND MAIL
			$settings = MailSetting::instance();
			$vars = [
				'full_name' => $data->prefix . ' ' . $data->first_name . ' ' . $data->middle_name . ' ' . $data->last_name . ' ' . $data->suffix,
			];
			Mail::send('pensoft.tdgw::mail.finish_tdwg_registration', $vars, function($message) use ($data, $settings) {
				$message->to($data->email, $data->full_name);
				$message->from($settings->sender_email, $settings->sender_name);
				$message->replyTo($settings->sender_email, $settings->sender_name);
			});

			if (count(Mail::failures()) > 0){
				Flash::error('Mail not sent');
				return;
			}

            return \Redirect::to('/');
        }
    }

    public function onPaymentProceed() {
        $ID = (int)post('ID');
        $data = Data::where('id', $ID)->first();
        $discount_options = $data->discount_option_id;
        $type = $data->type;
        $accompayning_person = $data->accompanying_person;
        $help_others = $data->help_others;
        $discount_code = $data->discount_code;
        $email = $data->email;

		//TODOD

        if($ID) {
            $products = [];
            $product_1 = Products::where('ticket_id', (int)$discount_options)
                ->where('type', $type)
                ->whereRaw('regular = CASE WHEN (early_booking_date >= now() AND type = \'physical\') THEN false ELSE true END')
                ->where('accompanying_person', 'false')
                ->where('help_others', 'false')
                ->first();
            if($product_1) {
                $product_1 = $product_1->toArray();
                $products[] = $product_1['product_id'];
            }
            //accompayning person
            $product_2 = [];
            if($accompayning_person) {
                $product_2 = Products::where('accompanying_person', 'true')->first()->toArray();
                $products[] = $product_2['product_id'];
            }

            //help others
            $product_3 = [];
            if($help_others) {
                $product_3 = Products::where('help_others', 'true')->first()->toArray();
                $products[] = $product_3['product_id'];
            }

            if(count($products)) {
                $data = [
                    'products' => $products,
                    'discount_code' => $discount_code,
                    'email' => $email,
                    'first_name' => $data->first_name,
                    'middle_name' => $data->middle_name,
                    'last_name' => $data->last_name,
                    'affiliation' => $data->last_name_tag,
                    'city' => $data->city,
                    'region' => $data->region,
                    'country' => $data->country,
                    'postal_code' => $data->postal_code,
                    'address' => $data->address,
                    'address2' => $data->address2,
                    'phone' => $data->phone,
                    'invoice_group_members' => $data->invoice_group_members,
                    'billing_details' => $data->billing_details,
                    'invoice_email' => $data->invoice_email,
                    'group_members_list' => $data->group_members_list,
                    'comments' => $data->comments,
                ];
                $json = json_encode($data, true);

                $httpResponse = \Http::post(env('TDWG_REQUEST_URL'), function($http) use ($json) {
                    $http->header('Accept', 'application/vnd.twitchtv.v5+json');
                    $http->header('Content-Type', 'application/json');
                    // Sends data with the request
                    $http->setOption(CURLOPT_POSTFIELDS, $json);

                    // Sets a cURL option manually
                    $http->setOption(CURLOPT_SSL_VERIFYHOST, false);
                    $http->setOption(CURLOPT_RETURNTRANSFER, true);

                });

                if($httpResponse->code != 200) {
                    throw new \ApplicationException(sprintf('Pensoft API error: %s', $httpResponse->body));
                }

                $response = json_decode($httpResponse->body, true);

                if(!is_array($response)) {
                    throw new \ApplicationException('Pensoft API error. Invalid response.');
                }
                if(isset($response['error'])) {
                    throw new \ApplicationException(sprintf('Pensoft API error: %s', $response['error']));
                }
                if(!isset($response['uri']) || !is_string($response['uri'])) {
                    throw new \ApplicationException('Pensoft API did not respond with a proper URI.');
                }
            }

//            $entry = Entry::where('id', env('TDWG_ID'))->first();
//            Flash::success($entry->thank_you_message); //TODO


			$saveData = Data::where('id', (int)$ID)->where('submission_completed', false)->first();
			$saveData->submission_completed = 'true';
			$saveData->save();

			//mark code as used
			if(isset($data->discount_code) && $data->discount_code){
				$code = Codes::where('code', $data->discount_code)->first();
				$code->is_used = true;
				$code->save();
			}


			//SEND MAIL
			$settings = MailSetting::instance();
			$vars = [
				'full_name' => $data->prefix . ' ' . $data->first_name . ' ' . $data->middle_name . ' ' . $data->last_name . ' ' . $data->suffix,
				'link' => 'https://pensoft.net',
			];
			Mail::send('pensoft.tdgw::mail.finish_tdwg_registration_with_payment', $vars, function($message) use ($data, $settings) {
				$message->to($data->email, $data->full_name);
				$message->from($settings->sender_email, $settings->sender_name);
				$message->replyTo($settings->sender_email, $settings->sender_name);
			});

			if (count(Mail::failures()) > 0){
				Flash::error('Mail not sent');
				return;
			}

			return \Redirect::to('/');
        }
    }



	private function isValidTelephoneNumber(string $telephone, int $minDigits = 9, int $maxDigits = 14): bool {
		if (preg_match('/^[+][0-9]/', $telephone)) { //is the first character + followed by a digit
			$count = 1;
			$telephone = str_replace(['+'], '', $telephone, $count); //remove +
		}

		//remove white space, dots, hyphens and brackets
		$telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);

		//are we left with digits only?
		return $this->isDigits($telephone, $minDigits, $maxDigits);
	}

	private function normalizeTelephoneNumber(string $telephone): string {
		//remove white space, dots, hyphens and brackets
        return str_replace([' ', '.', '-', '(', ')'], '', $telephone);
	}

	private function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool {
		return preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', $s);
	}

	private function checkDiscountCode(string $code) {
    	$codeData = Codes::where('code', $code)->where('is_used', false)->first();
    	if(!$codeData){
			return false;
		}

		return $codeData->toArray();

	}

	function onDiscountCodeValidate() {
		$code = post('code');
		if($code){
			$codeData = Codes::where('code', $code)->where('is_used', false)->first();
			if(!$codeData){
				$err = "The provided discount code is not valid or is already used";
				return ['err' => $err];
			}
			return ['result' => $codeData->toArray()];
		}
	}

}
