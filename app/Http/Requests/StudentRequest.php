<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'studentnumber' => 'numeric|unique:students,studentnumber,'. $this->id,
            // 'studentnumber' => "required|unique:students,studentnumber,{$this->id},id,deleted_at,NULL",
            'lrn'           => 'nullable|numeric|unique:students,lrn,'.$this->id.',id,deleted_at,NULL',
            'application'   => 'required',
            'firstname'     => 'required|min:1|max:100',
            'lastname'      => 'required|min:1|max:100',
            // 'new_or_old'  => 'required|string',
            // 'middlename' => 'max:100',
            'birthdate' => 'required',
            // 'citizenship' => 'required|min:1|max:100',
            // 'birthplace' => 'required',
            // 'residentialaddress' => 'required',
            // 'email' => 'min:5|max:100',
            // 'religion' => 'required|min:1|max:100',
            'street_number' => 'required|min:3|max:100',
            'barangay' => 'required|min:3|max:100',
            'city_municipality' => 'required|min:3|max:100',
            'province' => 'required|min:3|max:100',


            // FAMILY BACKGROUND
            // 'father'                => 'required|in:Father,Step-Father,Legal Guardian',
            // 'fatherlastname'        => 'required',
            // 'fatherfirstname'       => 'required',
            // 'fatherreceivetext_1'   => 'required|boolean',

            // 'mother'                => 'required|in:Mother,Mother-Father,Legal Guardian',
            // 'motherlastname'        => 'required',
            // 'motherfirstname'       => 'required',
            // 'motherreceivetext_1'   => 'required|boolean',

            // 'legal_guardian_lastname'    => 'required',
            // 'legal_guardian_firstname'   => 'required',
            // 'living' => 'required',
            // 'legalguardian' => 'required|min:5|max:20',
            // 'contactnumberCountryCode' => 'required',
            // 'contactnumber' => 'required|min:5|max:20',
            // 'majorlanguages' => 'required',
            // 'other_language_specify' => 'min:1|max:100',
            // // 'previousschool' => 'required',
            // // 'previousschooladdress' => 'required',
            // // 'schooltable' => 'required',
            // 'fatherfirstname' => 'required|min:1|max:100',
            // 'fatherlastname' => 'required|min:1|max:100',
            // 'fathermiddlename' => 'min:1|max:100',
            // 'fathercitizenship' => 'required|min:1|max:100',
            // 'fathervisastatus' => 'required',
            // 'fatheremployer' => 'required',
            // // 'fatherofficenumberCountryCode' => 'required',
            // 'fatherofficenumber' => 'min:5|max:20',
            // 'fatherdegree' => 'required',
            // 'fatherschool' => 'required',
            // // 'fatherMobileNumberCountryCode' => 'required',
            // 'fatherMobileNumber' => 'max:20',
            // 'motherfirstname' => 'required|min:1|max:100',
            // 'motherlastname' => 'required|min:1|max:100',
            // 'mothermiddlename' => 'max:100',
            // 'mothercitizenship' => 'required|min:1|max:100',
            // 'mothervisastatus' => 'required',
            // 'motheremployer' => 'required',
            // // 'motherOfficeNumberCountryCode' => 'required',
            // 'motherOfficeNumber' => 'min:5|max:20',
            // 'motherdegree' => 'required',
            // 'motherschool' => 'required',
            // // 'mothernumberCountryCode' => 'required',
            // 'mothernumber' => 'required|min:5|max:100',
            // 'emergencycontactname' => 'required',
            // 'emergencyRelationshipToChild' => 'required',
            // 'emergencyofficephoneCountryCode' => 'required',
            // 'emergencyofficephone' => 'required|min:5|max:100',
            // 'emergencymobilenumberCountryCode' => 'required',
            // 'emergencymobilenumber' => 'required|min:5|max:100',
            // 'emergencyaddress' => 'required',
            // 'emergencyhomephoneCountryCode' => 'required',
            // 'emergencyhomephone' => 'required',
            // 'date' => 'required',
            'schoolyear'    => 'required|numeric|exists:school_years,id',
            // 'department_id' => 'required|numeric',
            // 'level_id'      => 'required|numeric',
            // 'date2' => 'required',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [];
        // return [
        //     'studentnumber' => 'Student Number',
        //     'application' => 'Application',
        //     'firstname' => 'First Name',
        //     'lastname' => 'Last Name',
        //     'middlename' => 'Middle Name',
        //     'birthdate' => 'Birthdate',
        //     'citizenship' => 'Citizenship',
        //     'birthplace' => 'Birthplace',
        //     'residentialaddress' => 'Residential Address',
        //     'email' => 'Email',
        //     'religion' => 'Religion',
        //     'living' => 'Guardian/Parents',
        //     'contactnumber' => 'Contact Number',
        //     'majorlanguages' => 'Major languages spoken at home',
        //     'other_language_specify' => 'Specify the other language',
        //     'previousschool' => 'Previous School is required',
        //     'previousschooladdress' => 'Address of the previous School',
        //     'schooltable' => 'School(s) attended',
        //     'fatherfirstname' => 'Father First Name',
        //     'fatherlastname' => 'Father Last Name',
        //     'fathermiddlename' => 'Father Middle Name',
        //     'fathercitizenship' => 'Father Citizenship',
        //     'fathervisastatus' => 'Father Visa status',
        //     'fatheremployer' => 'Father Employer',
        //     'fatherofficenumber' => 'Father Office Number',
        //     'fatherdegree' => 'Father Degree',
        //     'fatherschool' => 'Father School',
        //     'fatherMobileNumber' => 'Father Contact number',
        //     'motherfirstname' => 'Mother First Name',
        //     'motherlastname' => 'Mother Last Name',
        //     'mothermiddlename' => 'Mother Middle Name',
        //     'mothercitizenship' => 'Mother Citizenship',
        //     'mothervisastatus' => 'Mother Visa status',
        //     'motheremployer' => 'Mother Employer',
        //     'motherOfficeNumber' => 'Mother Office Number',
        //     'motherdegree' => 'Mother Degree',
        //     'motherschool' => 'Mother School',
        //     'mothernumber' => 'Mother Number',
        //     'emergencycontactname' => 'Emergency Name',
        //     'emergencyofficephone' => 'Emergency Office Phone',
        //     'emergencymobilenumber' => 'Emergency Mobile Number',
        //     'emergencyaddress' => 'Emergency Address',
        //     'emergencyhomephone' => 'Emergency Home Phone',
        //     'date' => 'Date',
        //     'schoolyear' => 'School Year',
        //     'date2' => 'Date',

        // ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
