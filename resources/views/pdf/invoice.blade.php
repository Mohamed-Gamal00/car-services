<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
</head>
<style>
    h4 {
        margin: 0;
    }

    .w-full {
        width: 100%;
    }

    .w-half {
        width: 50%;
    }

    .margin-top {
        margin-top: 1.25rem;
    }

    .footer {
        font-size: 0.875rem;
        padding: 1rem;
        background-color: rgb(241 245 249);
    }

    table {
        width: 100%;
        border-spacing: 0;
    }

    table.products {
        font-size: 0.875rem;
    }

    table.products tr {
        background-color: #F05624;
    }

    table.products th {
        color: #ffffff;
        padding: 0.5rem;
    }

    table tr.items {
        background-color: rgb(241 245 249);
    }

    table tr.items td {
        padding: 0.5rem;
    }

    .total {
        text-align: right;
        margin-top: 1rem;
        font-size: 0.875rem;
    }
</style>

<body>
<table class="w-full">
    <tr>
        <td class="w-half">

            <img style="width: 200px"
                 src="data:image/png;base64,{{ base64_encode(file_get_contents(base_path('assets/uploads/Invoice/invoice_main_logo.png'))) }}">
        </td>
        <td class="w-half">
            <h4>Invoice ID: {{ $data['item_number'] }}</h4>
            <h4>Transaction ID: {{ $data['transaction_id'] }}</h4>
            <h4>Tracking Number: {{ $data['tracking_number'] }}</h4>
        </td>
    </tr>
</table>

<div class="margin-top">
    <table class="w-full">
        <tr>
            <td class="w-half">
                <div style="margin-bottom: 10px">
                    <h4>Shipper Details:</h4>
                </div>
                <div>Name: {{ $data['shipper']['name'] }}</div>
                <div>Country: {{ $data['shipper']['country'] }}</div>
                <div>City: {{ $data['shipper']['city'] }}</div>
                <div>Postal Code: {{ $data['shipper']['postcode'] }}</div>
                <div>Address: {{ $data['shipper']['long_street_name'] ?? $data['shipper']['street'] }}</div>
                <div>Email: {{ $data['shipper']['email'] }}</div>
                <div>Phone Number: +{{ $data['shipper']['dial_code'] . ' ' . $data['shipper']['telephone'] }}</div>
                <div>ID Number: {{ $data['shipper']['id_number'] }}</div>
            </td>
            <td class="w-half">
                <div style="margin-bottom: 10px">
                    <h4>Receiver Deatils:</h4>
                </div>
                <div>Name: {{ $data['receiver']['name'] }}</div>
                <div>Country: {{ $data['receiver']['country'] }}</div>
                <div>City: {{ $data['receiver']['city'] }}</div>
                <div>Postal Code: {{ $data['receiver']['postcode'] }}</div>
                <div>Address: {{ $data['receiver']['long_street_name'] ?? $data['receiver']['street'] }}</div>
                <div>Email: {{ $data['receiver']['email'] }}</div>
                <div>Phone Number: +{{ $data['receiver']['dial_code'] . ' ' . $data['receiver']['telephone'] }}</div>
        </tr>
    </table>
</div>

<div class="margin-top">
    <table class="products">
        <tr>
            <th colspan="2">Shipment Items</th>
        </tr>
        @foreach ($data['items'] as $item)
            <tr class="items" style="background-color: white">
                <td colspan="2" style="font-weight: bold">
                    Item Number - #{{ $loop->iteration }}
                </td>
            </tr>
            <tr class="items">
                <td>
                    Weight
                </td>

                <td style="text-align: right;">
                    {{ $item['weight'] }} KG
                </td>
            </tr>
            <tr class="items">
                <td>
                    Quantity
                </td>

                <td style="text-align: right;">
                    {{ $item['quantity'] }}
                </td>
            </tr>
            @if (isset($item['commodity_code']))
                <tr class="items">
                    <td>
                        Product Code
                    </td>

                    <td style="text-align: right;">
                        {{ $item['commodity_code'] }}
                    </td>
                </tr>
            @endif

            @if (!$data['splInvoice'])
                <tr class="items">
                    <td>
                        Item Price
                    </td>

                    <td style="text-align: right;">
                        {{ number_format($item['item_price'], 2, '.', ',') }} SAR
                    </td>
                </tr>
            @endif
        @endforeach
    </table>
</div>

@if (!$data['splInvoice'])
    <div class="total">
        Total Charges: {{ number_format($data['total_price'], 2, '.', ',') }} SAR
        <p>
            The price includes tax
        </p>
    </div>
    <br>
    @isset($data['change_address_fees'])
        <div class="total">
            Total Change Receiver Address Charges: {{ number_format($data['change_address_fees'], 2, '.', ',') }} SAR
            <p>
                The price includes tax
            </p>
        </div>
    @endisset
    <div class="footer margin-top">
        <div>Thank you</div>
        <div>&copy; Jahzha</div>
    </div>
@endif
</body>

</html>
