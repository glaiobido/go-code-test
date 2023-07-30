<?php

namespace App\Traits;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait InviteTrait {

    public static function processInvite($parseData = [])
    {
        try {
            if (!empty($parseData)) {

                $parsed = [];
                $currentDate = Carbon::parse('2023-03-05'); // As if current date is: March 5, 2023

                // Filtered by way of date
                $invitedArr = Arr::mapWithKeys($parseData, function ($customer, $index) use ($currentDate,$parseData) {

                    $customer['uid'] = (string) Str::uuid();
                    $customer['error_message'] = null;
                    $customer['via'] = null;

                    $isInvited = false;

                    if ($currentDate->diffInDays($customer['trans_date']) <= 7) {
                        $isInvited = true;
                    }
                    $customer['notify'] = false;
                    $customer['is_invited'] = $isInvited;
                    $customer['error_message'] = ($isInvited == false) ? "Record is beyond 7 days" : null;
                    return [$customer['uid'] => $customer];
                });

                
                // Customer Number Check
                foreach ($invitedArr as $uid => $invited) {

                    if ($invited['is_invited']) {
                        
                        $customerNumberDuplicates = self::checkForDuplicateCustomerNumbers($invitedArr, $invited['cust_num']);
                        
                        if (count($customerNumberDuplicates) > 1) {
                            // compare the records that have the same customer number if they have the same email or phone
                            $customerContactDuplicateArr = self::checkForDuplicates($customerNumberDuplicates, $invited);
                           
                            if (count($customerContactDuplicateArr) > 1) {

                                $customerPrecedent = self::pickOldestRecord($customerContactDuplicateArr, $invited);
                                $invited['notify'] = ($customerPrecedent['uid'] == $invited) ? true : false;
                                $invited['error_message'] = ($invited['notify'] == false) 
                                    ? "Customer #{$invited['cust_num']} is a duplicate." 
                                    : null;

                            } else {
                                $invited['notify'] = true;
                            }

                            $parsed[$uid] = $invited;
                        } else {
                            // even if it has unique cust_num, trigger a check for duplicate contact details
                            $customerContactDuplicateArr = self::checkForDuplicates($invitedArr, $invited);
                           
                            if (count($customerContactDuplicateArr) > 0) {
                                $customerPrecedent = self::pickOldestRecord($customerContactDuplicateArr, $invited);
                                $invited['notify'] = ($customerPrecedent['uid'] == $invited) ? true : false;
                                $invited['error_message'] = ($invited['notify'] == false) 
                                    ? "Customer #{$invited['cust_num']} is a duplicate." 
                                    : null;
                                $parsed[$uid] = $invited;

                                // set the others to false if they are in the $parsed array
                                $duplicates = array_keys($customerContactDuplicateArr);
                                foreach ($duplicates as $key) {
                                    if (array_key_exists($key, $parsed) && $invited['notify'] == true) {
                                        $parsed[$key]['notify'] = false; // set the duplicates to false
                                        $parsed[$key]['notify'] = "Customer #{$parsed[$key]['cust_num']} is a duplicate." ;
                                    }
                                }
                            } else {
                                
                                $invited['notify'] = true;
                                $parsed[$uid] = $invited;
                            }

                            
                        }
                    } else {
                        $parsed[$uid] = $invited;
                    }
                }

                $updatedData = self::simuliateSendInvite($parsed);
                // Log::info(array_values($updatedData));
                return array_values($updatedData);

            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private static function simuliateSendInvite($invitedPeople)
    {
        try {
            $num_index = 0;
            $invitedPeople = Arr::map($invitedPeople,  function ($customer, $index) use ($num_index) {
                $customer['trans_date'] = Carbon::parse($customer['trans_date'])->format('M d Y');
                // check if person has both phone and email, use PHONE
                if ($customer['is_invited'] && $customer['notify'] == true) {
                    if (!empty($customer['cust_email']) && !empty($customer['cust_phone'])) {
                        $customer['via'] = "Phone";
                      return $customer;
                    } elseif (!empty($customer['cust_email'])) {
                        $customer['via'] = "Email";
                      return $customer;
                    } elseif (!empty($customer['cust_phone'])) {
                        $customer['via'] = "Phone";
                      return $customer;
                    } else {
                        $customer['via'] = null;
                        $customer['error_message'] = "No contact details provided";
                        $customer['notify'] = false;

                      return $customer;
                    }
                }

                return $customer;
            });

            return $invitedPeople;

        } catch (\Throwable $th) {
            throw $th;
            
        }
    }

    private static function checkForDuplicates($parseData, $customerDetail)
    {

        $filtered = array_filter($parseData, function($record, $key) use ($customerDetail) {
            return ($record['uid'] != $customerDetail['uid'] && isset($record['cust_email']) && isset($record['cust_phone']) && $record['cust_email'] == $customerDetail['cust_email'] && $record['cust_phone'] == $customerDetail['cust_phone']);
        }, ARRAY_FILTER_USE_BOTH);

        return $filtered;
    }

    private static function pickOldestRecord($recordsArray, $currentInvitee)
    {
        $currentDate = Carbon::parse('2023-03-05'); // As if current date is: March 5, 2023
        $oldestRecord = null;
        foreach ($recordsArray as $key => $record) {

            // If the record being checked is not the $currentInvitee
            if ($record['uid'] != $currentInvitee['uid']) {
                
                $parseDateTime = Carbon::parse($record['trans_date'] . $record['trans_time']);
                $currentInviteeParseDateTime = Carbon::parse($currentInvitee['trans_date'] . $currentInvitee['trans_time']);
                
                if ($currentInviteeParseDateTime->isBefore($parseDateTime)) {
                    $oldestRecord = $currentInviteeParseDateTime;
                } else {
                    $oldestRecord = $record;
                }
            }
            
        }
        // Log::info($oldestRecord);
        return $oldestRecord;

    }

    private static function checkForDuplicateCustomerNumbers($parseData, $customerNumber)
    {
        $filtered = array_filter($parseData, function($record, $key) use ($customerNumber) {
            return $record['cust_num'] == $customerNumber;
        }, ARRAY_FILTER_USE_BOTH);

        return $filtered;
    }

} /* End of trait */