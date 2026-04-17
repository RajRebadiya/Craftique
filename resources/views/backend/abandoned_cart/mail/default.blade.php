<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Purchase</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background-color: #f8f9fa; font-family: Arial, sans-serif;">
<!-- Tracking Pixel -->
<img src="{{ route('ac.track.email', $abandonEmail->uuid) }}" width="1" height="1" alt="" style="display: none;">

<!-- Email Container -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f8f9fa;">
    <tr>
        <td align="center" style="padding: 20px 0;">

            <!-- Main Email Table -->
            <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); max-width: 600px; width: 100%;">

                <!-- Header -->
                <tr>
                    <td style="background: linear-gradient(135deg, #e62e04 0%, #CF2905 100%); background-color: #667eea; padding: 40px 30px; text-align: center; border-radius: 8px 8px 0 0;">
                        <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold; line-height: 1.2;">Your Cart is Waiting! 🛒</h1>
                        <p style="margin: 10px 0 0; color: #ffffff; font-size: 16px; opacity: 0.9;">Don't miss out on these amazing items</p>
                    </td>
                </tr>

                <!-- Main Content -->
                <tr>
                    <td style="padding: 40px 30px;">

                        <!-- Greeting -->
                        <h2 style="margin: 0 0 20px; color: #333333; font-size: 22px; font-weight: 600;">Hi {{ $user->name }}! 👋</h2>

                        <!-- Message -->
                        <p style="margin: 0 0 30px; color: #666666; font-size: 16px; line-height: 1.6;">
                            We noticed you left some great items in your cart. We've saved them for you! Complete your purchase now to get them delivered to your doorstep.
                        </p>

                        <!-- Cart Items Section -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                            <tr>
                                <td>
                                    <h3 style="margin: 0 0 20px; color: #333333; font-size: 20px; text-align: center;">Your Items</h3>

                                    @foreach($cart->items as $item)
                                        <!-- Product Item -->
                                        <table width="100%" border="0" cellspacing="0" cellpadding="15" style="background-color: #ffffff; border-radius: 8px; margin-bottom: 15px; border: 1px solid #e9ecef;">
                                            <tr>
                                                <td width="80" style="vertical-align: middle;">
                                                    @if($item->product->thumbnail)
                                                        <img src="{{ asset($item->product->thumbnail) }}" alt="{{ $item->product->name }}" style="width: 70px; height: 70px; border-radius: 6px; object-fit: cover;">
                                                    @else
                                                        <div style="width: 70px; height: 70px; background-color: #e9ecef; border-radius: 6px; display: flex; align-items: center; justify-content: center; text-align: center; font-size: 12px; color: #999999;">
                                                            No Image
                                                        </div>
                                                    @endif
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <h4 style="margin: 0 0 8px; color: #333333; font-size: 18px; font-weight: 600;">{{ $item->product->name }}</h4>
                                                    <div style="font-size: 16px;">
                                                        @if($item->product->discount > 0)
                                                            <span style="color: #999999; text-decoration: line-through; margin-right: 10px;">${{ number_format($item->product->unit_price, 2) }}</span>
                                                            <span style="color: #e74c3c; font-weight: bold; font-size: 18px;">
                                                                @if($item->product->discount_type == 'percent')
                                                                    ${{ number_format($item->product->unit_price * (1 - $item->product->discount / 100), 2) }}
                                                                @else
                                                                    ${{ number_format($item->product->unit_price - $item->product->discount, 2) }}
                                                                @endif
                                                            </span>
                                                            <span style="background-color: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-left: 10px;">
                                                                Save
                                                                @if($item->product->discount_type == 'percent')
                                                                    {{ $item->product->discount }}%
                                                                @else
                                                                    ${{ number_format($item->product->discount, 2) }}
                                                                @endif
                                                            </span>
                                                        @else
                                                            <span style="color: #333333; font-weight: bold; font-size: 18px;">${{ number_format($item->product->unit_price, 2) }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    @endforeach

                                </td>
                            </tr>
                        </table>

                        <!-- CTA Button -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 30px;">
                            <tr>
                                <td align="center">
                                    <!--[if mso]>
                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" style="height:50px;v-text-anchor:middle;width:250px;" arcsize="50%" stroke="f" fillcolor="#667eea">
                                        <w:anchorlock/>
                                    </v:roundrect>
                                    <![endif]-->
                                    <a href="{{ route('cart', ['ac_recovery_id' => $abandonedCart->uuid]) }}" style="background: linear-gradient(135deg, #e62e04 0%, #CF2905 100%); background-color: #667eea; border-radius: 25px; padding: 0 8px; color: #ffffff; display: inline-block; font-size: 18px; font-weight: bold; line-height: 50px; text-align: center; text-decoration: none; width: 250px; -webkit-text-size-adjust: none;">
                                        Complete Your Purchase →
                                    </a>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background-color: #2c3e50; padding: 30px; text-align: center; border-radius: 0 0 8px 8px;">
                        <h3 style="margin: 0 0 10px; color: #ffffff; font-size: 18px;">Thank You!</h3>

                        <!-- Unsubscribe -->
                        <p style="margin: 20px 0 0; font-size: 12px;">
                            <a href="{{ route('ac.unsubscribe', $abandonEmail->uuid) }}" style="color: #bdc3c7; text-decoration: none;">
                                Unsubscribe from these emails
                            </a>
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>
</body>
</html>
