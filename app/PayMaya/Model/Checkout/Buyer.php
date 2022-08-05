<?php

namespace App\PayMaya\Model\Checkout;

use App\PayMaya\Model\Checkout\Contact;
use App\PayMaya\Model\Checkout\Address;

class Buyer
{
	public $firstName;
	public $middleName;
	public $lastName;
	public $contact;
	public $shippingAddress;
	public $billingAddress;
}
