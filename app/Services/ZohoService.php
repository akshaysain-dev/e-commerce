<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZohoService
{
    public function getZohoAccessToken()
    {
        $response = Http::asForm()->post('https://accounts.zoho.in/oauth/v2/token', [
            'refresh_token' => env('ZOHO_REFRESH_TOKEN'),
            'client_id' => env('ZOHO_CLIENT_ID'),
            'client_secret' => env('ZOHO_CLIENT_SECRET'),
            'grant_type' => 'refresh_token',
        ]);

        $data = $response->json();

        Log::info('Zoho Access Token Response', $data);

        return $data['access_token'] ?? null;
    }

    public function sendCustomer($customer)
    {
        try {
            $token = $this->getZohoAccessToken();

            if (!$token) {
                Log::error('Zoho Token not generated');
                return false;
            }

            $check = Http::withToken($token)->get(
                env('ZOHO_BASE_URL') . '/crm/v2/Contacts/search?email=' . $customer->email
            );

            $checkResult = $check->json();

            if (isset($checkResult['data']) && count($checkResult['data']) > 0) {

                $zohoId = $checkResult['data'][0]['id'];

                $customer->update([
                    'zoho_id' => $zohoId
                ]);

                Log::info('Duplicate found, updating contact', [
                    'zoho_id' => $zohoId
                ]);

                $payload = [
                    'data' => [
                        [
                            'id' => $zohoId,

                            'Last_Name' => $customer->name,
                            'Email' => $customer->email,
                            'Phone' => $customer->phone,

                            'Mailing_Street' => $customer->address,
                            'Mailing_City' => $customer->city,
                            'Mailing_State' => $customer->state,
                            'Mailing_Country' => $customer->country,
                            'Mailing_Zip' => $customer->postal_code,

                            'Account_Name' => [
                                'name' => $customer->company ?? config('app.name')
                            ]
                        ]
                    ]
                ];

                $response = Http::withToken($token)
                    ->put(env('ZOHO_BASE_URL') . '/crm/v2/Contacts', $payload);

                $result = $response->json();

                Log::info('Zoho Contact Updated', $result);

                return [
                    'status' => true,
                    'message' => 'Duplicate found, contact updated',
                    'zoho_id' => $zohoId
                ];
            }

            $payload = [
                'data' => [
                    [
                        'Last_Name' => $customer->name,
                        'Email' => $customer->email,
                        'Phone' => $customer->phone,

                        'Mailing_Street' => $customer->address,
                        'Mailing_City' => $customer->city,
                        'Mailing_State' => $customer->state,
                        'Mailing_Country' => $customer->country,
                        'Mailing_Zip' => $customer->postal_code,

                        'Account_Name' => [
                            'name' => $customer->company ?? config('app.name')
                        ]
                    ]
                ]
            ];

            Log::info('Creating new Zoho Contact', $payload);

            $response = Http::withToken($token)
                ->post(env('ZOHO_BASE_URL') . '/crm/v2/Contacts', $payload);

            $result = $response->json();

            Log::info('Zoho Contact Created', $result);

            // ✅ Save Zoho ID
            if (isset($result['data'][0]['details']['id'])) {

                $zohoId = $result['data'][0]['details']['id'];

                $customer->update([
                    'zoho_id' => $zohoId
                ]);

                Log::info('New Zoho ID saved', ['zoho_id' => $zohoId]);
            }

            return [
                'status' => true,
                'data' => $result
            ];

        } catch (\Exception $e) {
            Log::error('Zoho API Error', [
                'message' => $e->getMessage()
            ]);

            return [
                'status' => false,
                'message' => 'Zoho API Error'
            ];
        }
    }

    public function updateCustomer($customer)
    {
        try {
            $token = $this->getZohoAccessToken();

            if (!$token) {
                Log::error('Zoho Token not generated');
                return false;
            }

            if (!$customer->zoho_id) {
                Log::warning('Zoho ID missing, creating instead of updating', [
                    'customer_id' => $customer->id
                ]);

                return $this->sendCustomer($customer);
            }

            $payload = [
                'data' => [
                    [
                        'id' => $customer->zoho_id, 

                        'Last_Name' => $customer->name,
                        'Email' => $customer->email,
                        'Phone' => $customer->phone,

                        'Mailing_Street' => $customer->area,
                        'Mailing_City' => $customer->city,
                        'Mailing_State' => $customer->state,
                        'Mailing_Country' => $customer->country,
                        'Mailing_Zip' => $customer->postal_code,

                        'Account_Name' => [
                            'name' => $customer->company ?? config('app.name')
                        ]
                    ]
                ]
            ];

            Log::info('Updating Customer in Zoho', $payload);

            $response = Http::withToken($token)
                ->put(env('ZOHO_BASE_URL') . '/crm/v2/Contacts', $payload);

            $result = $response->json();

            Log::info('Zoho Update Response', $result);

            return [
                'status' => true,
                'data' => $result
            ];

        } catch (\Exception $e) {
            Log::error('Zoho Update Error', [
                'message' => $e->getMessage()
            ]);

            return [
                'status' => false,
                'message' => 'Zoho Update Failed'
            ];
        }
    }

    public function deleteCustomer($customer)
    {
        try {
            $token = $this->getZohoAccessToken();

            if (!$token) {
                Log::error('Zoho Token not generated');
                return false;
            }

            if (!$customer->zoho_id) {
                Log::warning('Zoho ID missing, skipping delete', [
                    'customer_id' => $customer->id
                ]);

                return true;
            }

            Log::info('Deleting Customer from Zoho', [
                'zoho_id' => $customer->zoho_id
            ]);

            $response = Http::withToken($token)
                ->delete(env('ZOHO_BASE_URL') . '/crm/v2/Contacts/' . $customer->zoho_id);

            $result = $response->json();

            Log::info('Zoho Delete Response', $result);

            return [
                'status' => true,
                'data' => $result
            ];

        } catch (\Exception $e) {
            Log::error('Zoho Delete Error', [
                'message' => $e->getMessage()
            ]);

            return [
                'status' => false,
                'message' => 'Zoho Delete Failed'
            ];
        }
    }
}