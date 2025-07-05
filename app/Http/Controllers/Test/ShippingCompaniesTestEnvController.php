<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;

class ShippingCompaniesTestEnvController extends Controller
{
    private const UPS_API_URL = 'https://wwwcie.ups.com/api/rating';
    private const API_VERSION = 'v2403';
    private const REQUEST_OPTION = 'Rate';
    private const TEST_SHIPPER_NUMBER = 'C95A75';

    private const UPS_AUTH_TOKEN = 'eyJraWQiOiI2NGM0YjYyMC0yZmFhLTQzNTYtYjA0MS1mM2EwZjM2Y2MxZmEiLCJ0eXAiOiJKV1QiLCJhbGciOiJSUzM4NCJ9.eyJzdWIiOiJzYWxlc0BqYWh6aGEuY29tIiwiY2xpZW50aWQiOiJacVlBZXRXWlFQVXhURlpYelhRQ2hNRThrRWxBcnphbEVWdW9LVXV1SEpsZkV4Z2UiLCJpc3MiOiJodHRwczovL2FwaXMudXBzLmNvbSIsInV1aWQiOiI4MDkyNzY0MS0yMTM2LTFGNEItOERERi1FNkQzNDUxRENEQTciLCJzaWQiOiI2NGM0YjYyMC0yZmFhLTQzNTYtYjA0MS1mM2EwZjM2Y2MxZmEiLCJhdWQiOiJKaGF6aGEiLCJhdCI6ImtHMDlIMHZ1RUFrSTQ2eEdjdEtBZnhYYTcyUWIiLCJuYmYiOjE3MjIzMzMyNjcsInNjb3BlIjoiTG9jYXRvcldpZGdldCIsIkRpc3BsYXlOYW1lIjoiSmhhemhhIiwiZXhwIjoxNzIyMzQ3NjY3LCJpYXQiOjE3MjIzMzMyNjcsImp0aSI6ImU5NzFkNTMxLTdhZDQtNGYyYy1hNDFhLTdjNTM2N2Q2NmM2YSJ9.qb8LQ9XukWoL8-Ah-F8qYvkcyHZwMWUCjWBepjaGVCQEKpv7LKB_JJCfK-zNOkTJIrNrFosgdNtWv5oSoOIpwECJiB1Ew1LiUz37UgoBrleq7iEn0D6CXRnk8p9zIVkSwzub7TbuL0REP4K47FnAkrPK8-WPYu9giMnpdc7KRmK3FXZtZSIn7owB0tHERA4kgJl4FhaJTq1gLRRkTdJirwyoQl_C4q4oIxsbNO4j8MVukWfPfmD8oc_rJfj7gKb5cI_PbOydapSukmFSThyuSkgeKOydwLPzVtc6sMisyvHLHkc1lTqHNLB2DQLvrq2pUr9PEEKe8cHrxgaUNCkHMRSs2S-zzjoN-xOrnTq5R0LcTR0_Ym-O06qJfG7cPvPd4s_uPnEzJp6tu0FmVYnGjfWkoJ77iQM8NrUahCuEBYgzLE2IZtk6c3_Gne6Snft24hmuZ8SMNF6XQQFLZenZepqvyQZbWBBXnMdchhH-BEzmtEMniI5hUSVbi3dDbqI3M3GsvdgAR52GvTfaLedhuQEHVIUWYE-ZxyJbwhwBkE5MOGbdbfioaFMeUAr2BGb4L1dz9lZFpDmt_TenUbYWNwT42TPvsY2e9tiHkHTwTUGffvoyx28jY1GDbvdqPT0zk9qDHE5arj0H91k9vptLpFCet4PlVFF-Wsp4NFD5zO0';

    public function UPS()
    {
        $payload = [
            "RateRequest" => [
                "Request" => [
                    "TransactionReference" => [
                        "CustomerContext" => "CustomerContext"
                    ]
                ],
                "Shipment" => [
                    "Shipper" => [
                        "Name" => "John",
                        "ShipperNumber" => self::TEST_SHIPPER_NUMBER,
                        "Address" => [
                            "AddressLine" => [
                                ""
                            ],
                            "City" => "",
                            "StateProvinceCode" => "",
                            "PostalCode" => "",
                            "CountryCode" => "SA"
                        ]
                    ],
                    "ShipTo" => [
                        "Name" => "ShipToName",
                        "Address" => [
                            "AddressLine" => [
                                ""
                            ],
                            "City" => "",
                            "StateProvinceCode" => "",
                            "PostalCode" => "M5V1E3",
                            "CountryCode" => "CA"
                        ]
                    ],
                    "ShipFrom" => [
                        "Name" => "ShipFromName",
                        "Address" => [
                            "AddressLine" => [
                                "t"
                            ],
                            "City" => "",
                            "StateProvinceCode" => "",
                            "PostalCode" => "10001",
                            "CountryCode" => "US"
                        ]
                    ],
                    "Service" => [
                        "Code" => "11",
                        "Description" => "UPS Worldwide Express Freight Midday"
                    ],
                    "Package" => [
                        "PackagingType" => [
                            "Code" => "02",
                            "Description" => "Package"
                        ],
                        "Dimensions" => [
                            "UnitOfMeasurement" => [
                                "Code" => "IN"
                            ],
                            "Length" => "5",
                            "Width" => "5",
                            "Height" => "5"
                        ],
                        "PackageWeight" => [
                            "UnitOfMeasurement" => [
                                "Code" => "LBS"
                            ],
                            "Weight" => "10"
                        ]
                    ]
                ]
            ]
        ];

        $headers = [
            "Authorization: Bearer " . self::UPS_AUTH_TOKEN,
            "Content-Type: application/json",
            "transId: string",
            "transactionSrc: testing"
        ];

        $response = $this->sendCurlRequest(self::UPS_API_URL . '/' . self::API_VERSION . '/' . self::REQUEST_OPTION, $payload, $headers);

        if ($response['error']) {
            return response()->json(['error' => $response['error']], 500);
        }

        return response()->json(json_decode($response['data'], true), 200);
    }

    private function sendCurlRequest($url, $payload, $headers)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        return [
            'data' => $response,
            'error' => $error
        ];
    }

    public function OAuth()
    {
        dd('data');
    }
}
