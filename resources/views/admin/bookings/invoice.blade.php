<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description"
    content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
  <meta name="keywords"
    content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
  <meta name="author" content="pixelstrap">
  <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">
  <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">
  <title>{{ $pageTitle }}</title><!-- Themify icon-->
  <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify.css"><!-- Google font-->
  <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap"
    rel="stylesheet">
  <style type="text/css">
    body {
      font-family: Rubik, sans-serif;
      display: block;
      color: #000248;
    }
  </style>
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>
  <table style="width:1160px;margin:0 auto;">
    <tbody>
      <tr>
        <td>
          <table style="width: 100%;">
            <tbody>
              <tr style="padding: 28px 0 5px; display: flex; justify-content: space-between; align-items: center;">
                <td><img src="{{ asset('assets/images/logo.png') }}" alt="logo"></td>
                <td>
                  <ul
                    style="list-style: none; display: flex; align-items: center; background: linear-gradient(291deg, #29C5F6 21.2%, #29C5F6 83.92%); padding: 31px 80px; border-bottom-left-radius: 100px; gap:28px;">
                    <li> <svg class="stroke-icon"
                        style="height:14px; width: 14px; fill:#fff; margin-right: 10px; vertical-align:-2px;">
                        <use href="assets/svg/icon-sprite.svg#call"></use>
                      </svg><span style="color: #FFFFFF; font-weight: 500; font-size: 16px;">(239) 555-0108</span></li>
                    <li
                      style="border-left: 1px solid rgba(255, 255, 255, 0.3); border-right: 1px solid rgba(255, 255, 255, 0.3); padding: 0 22px;">
                      <svg class="stroke-icon"
                        style="height:16px; width: 16px; fill:#fff; margin-right: 10px; vertical-align:-2px;">
                        <use href="assets/svg/icon-sprite.svg#email-box"></use>
                      </svg><span
                        style="color: #FFFFFF; font-weight: 500; font-size: 16px;">cuba@themesforest.com</span>
                    </li>
                    <li> <svg class="stroke-icon"
                        style="height:16px; width: 16px; fill:#fff; margin-right: 10px; vertical-align:-2px;">
                        <use href="assets/svg/icon-sprite.svg#web"></use>
                      </svg><span style="color: #FFFFFF; font-weight: 500; font-size: 16px;">Website:
                        www.cubathemes.com</span></li>
                  </ul>
                </td>
              </tr>
              <tr style="display: flex; justify-content: space-between;">
                <td> <span
                    style="font-size: 18px; display:block; font-weight: 600; color:rgba(41, 197, 246, 1); margin-bottom: 14px;">Invoice
                    to :</span><span
                    style="font-size: 18px; display:block; font-weight: 600; color:rgba(0, 2, 72, 1); margin-bottom: 8px;">
                    {{ $bookingDetails->customer?->name }}
                    </span><span
                    style="font-size: 18px; display:block; color: #52526C; opacity: 0.8; margin-bottom: 8px; width: 72%; line-height: 1.4;">{{ $bookingDetails->address }} </span><span
                    style="font-size: 18px; display:block; color: #52526C; opacity: 0.8; margin-bottom: 8px;">Phone :
                    ({{ $bookingDetails->customer?->country_code }}) {{ $bookingDetails->customer?->phone }}</span><span
                    style="font-size: 18px; display:block; color: #52526C; opacity: 0.8; margin-bottom: 8px;">Email :
                    Info@cartub.uk</span><span
                    style="font-size: 18px; display:block; color: #52526C; opacity: 0.8; margin-bottom: 8px;">Website:
                    Cartub.uk</span></td>
                <td>
                  <h4 style="font-size:42px; font-weight: 600;color: #000248; margin:0 0 12px 0;">INVOICE</h4>
                  {{-- <span
                    style="font-size: 18px;color: #29C5F6; display: block; font-weight: 600; margin-bottom: 20px;">Bank
                    Transfer</span><span
                    style="color: #000248; display: block; margin-bottom: 12px; font-weight: 600;">Leslie
                    Alexander</span><span
                    style="display: block; color: #52526C; font-size: 18px; margin-bottom: 12px;">Bank Account :
                    0981234098765</span><span
                    style="display: block; color: #52526C; font-size: 18px; margin-bottom: 12px;">Code :
                    LEF098756</span> --}}
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table style="width: 100%; border-spacing: 4px; margin-bottom: 20px;">
            <tbody>
              <tr>
                <td style="background: #F5F6F9;padding: 15px 25px;">
                  <p style="font-size:16px; font-weight:500; color:#52526C; opacity:0.8; margin:0;line-height: 2;">Date
                    :</p><span style="font-size: 18px; font-weight: 600;">{{ $bookingDetails->created_at->format('d M, Y') }}</span>
                </td>
                <td style="background: #F5F6F9;padding: 15px 25px;">
                  <p style="font-size:16px; font-weight:500; color:#52526C; opacity:0.8; margin:0;line-height: 2;">
                    Booking No :</p><span style="font-size: 18px; font-weight: 600;">#{{ $bookingDetails->booking_number }}</span>
                </td>
                {{-- <td style="background: #F5F6F9;padding: 15px 25px;">
                  <p style="font-size:16px; font-weight:500; color:#52526C; opacity:0.8; margin:0;line-height: 2;">
                    Account No :</p><span style="font-size: 18px; font-weight: 600;">0981234098765</span>
                </td> --}}
                <td style="background: #F5F6F9;padding: 15px 25px;">
                  <p style="font-size:16px; font-weight:500; color:#52526C; opacity:0.8; margin:0;line-height: 2;">
                    Amount :</p><span style="font-size: 18px; font-weight: 600;">£{{ $bookingDetails->total_amount }} </span>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table style="width: 100%; border-spacing:0;">
            <thead>
              <tr style="background: #29C5F6;">
                <th style="padding: 18px 15px;text-align: center; position: relative; border-top-left-radius: 10px;">
                  <span style="color: #fff; font-size: 18px; font-weight: 600;">No.</span>
                </th>
                <th style="padding: 18px 16px;text-align: left;"><span
                    style="color: #fff; font-size: 18px; font-weight: 600;">Description</span></th>
                <th style="padding: 18px 15px;text-align: center"><span
                    style="color: #fff; font-size: 18px; font-weight: 600;">Unite Price</span></th>
                <th style="padding: 18px 15px;text-align: center"><span
                    style="color: #fff; font-size: 18px; font-weight: 600;">Quantity</span></th>
                <th style="padding: 18px 15px;text-align: center;position: relative; border-top-right-radius: 10px;">
                  <span style="color: #fff; font-size: 18px; font-weight: 600;">Subtotal</span>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td
                  style="width: 12%; text-align: center; border-bottom: 1px solid rgba(82, 82, 108, 0.2);background: #F5F6F9;">
                  <span style="color: #52526C;opacity: 0.8; font-weight: 600;">1</span>
                </td>
                <td style="padding: 16px; border-bottom: 1px solid rgba(82, 82, 108, 0.2);">
                  <h4 style="font-weight:600; margin:4px 0px; font-size: 18px; color: #000248;">{{ $bookingDetails->service?->name }}
                    </h4><span style="color: #52526C;opacity: 0.8; font-size: 16px;">{{ $bookingDetails->addOnsNames }}</span>
                </td>
                <td
                  style="width: 12%; text-align: center;border-bottom: 1px solid rgba(82, 82, 108, 0.2);background: #F5F6F9;">
                  <span style="color: #52526C;font-weight: 600;">£{{ $bookingDetails->gross_amount }}</span>
                </td>
                <td style="width: 12%; text-align: center; border-bottom: 1px solid rgba(82, 82, 108, 0.2);"> <span
                    style="color: #52526C;font-weight: 600;">1</span></td>
                <td
                  style="width: 12%; text-align: center; border-bottom: 1px solid rgba(82, 82, 108, 0.2);background: #F5F6F9;">
                  <span style="color: #000248; font-weight: 600;opacity: 0.9;">£{{ $bookingDetails->gross_amount }}</span>
                </td>
              </tr>

              <tr>
                <td> </td>
                <td> </td>
                <td> </td>
                <td style="text-align: center; padding:35px 0 18px;"> <span
                    style="color: #52526C;opacity: 0.8;font-weight: 600;">Subtotal </span></td>
                <td style=" text-align: center;background: #F5F6F9; display: block; padding:35px 0 18px;"> <span
                    style="color: #000248; font-weight: 600;opacity: 0.9;">£{{ $bookingDetails->gross_amount }}</span></td>
              </tr>
              <tr>
                <td> </td>
                <td> </td>
                <td> </td>

              </tr>
              <tr>
                <td></td>
                <td></td>
                <td> </td>
                <td style="width: 12%; text-align: center;"> <span
                    style="color: #52526C;opacity: 0.8;font-weight: 600;">Discount </span></td>
                <td style="text-align: center;background: #F5F6F9; display: block; padding-bottom: 18px;"> <span
                    style="color: #000248; font-weight: 600;opacity: 0.9;">£{{ $bookingDetails->discount_amount }}</span></td>
              </tr>
              <tr>
                <td> </td>
                <td> </td>
                <td></td>
                <td style="width: 12%; text-align: center;"> <span
                    style="color: #52526C;opacity: 0.8;font-weight: 600;">Total </span></td>
                <td style="text-align: center;background: #F5F6F9;"> <span
                    style="color: #ffffff; font-weight: 600;opacity: 0.9;background: #52526C; padding: 12px 37px; margin-top: 0px; display: block;">£{{ $bookingDetails->total_amount }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
      <tr style="width: 100%; display: flex; justify-content: space-between; margin-top: 36px;">
        <td> <img src="{{ asset('assets/images/email-template/invoice-3/sign.png') }}" alt="sign"><span
            style="color: #000248;display: block;font-size: 18px;font-weight: 600;">Laurine T. Ebbert</span><span
            style="color: #52526C; display: block; font-size: 14px; padding-top: 5px;">( Designer )</span></td>
        <td> <span style="display: flex; justify-content: end; gap: 15px;"><a
              style="background: rgba(41, 197, 246, 1); color:rgba(255, 255, 255, 1);border-radius: 10px;padding: 18px 27px;font-size: 16px;font-weight: 600;outline: 0;border: 0; text-decoration: none;"
              href="#!" onclick="window.print();">Print Invoice<i class="icon-arrow-right"
                style="font-size:13px;font-weight:bold; margin-left: 10px;"></i></a><a
              style="background: rgba(41, 197, 246, 0.1);color: rgba(41, 197, 246, 1);border-radius: 10px;padding: 18px 27px;font-size: 16px;font-weight: 600;outline: 0;border: 0; text-decoration: none;"
              href="template/invoice-5.html" download="">Download<i class="icon-arrow-right"
                style="font-size:13px;font-weight:bold; margin-left: 10px;"> </i></a></span></td>
      </tr>
    </tbody>
  </table>
</body>

</html>
