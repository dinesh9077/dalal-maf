<style>
    table td {
        border-bottom: 1px solid var(--button-color) !important;
        border-left: 0px solid var(--button-color) !important;
    }

    table td {
        color: #000;
    }

    .card-box p {
        margin: 5px 0;
    }
</style>
<div class="table-responsive">
    <table border="0" cellpadding="0" cellspacing="0" style="width: {{$width}}%; margin: 0 auto;border-radius: 24px;margin-bottom: 50px;border: 1px solid #E6E7EC;background: #fff;">
        <tbody>
            <tr>
                <td style="padding: 0 0 0 0 !important;">
                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin: 0px auto;">
                        <tbody>
                            <tr>
                                <td style="border-right: 1px solid var(--button-color) !important;width: 50%;padding: 15px;">

                                </td>
                                <td style="text-align: end;">
                                    <b style="font-style: italic; padding-right: 20px;padding: 15px;">Original Copy</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin: 0 auto;">
                        <tbody>
                            <tr style="text-align: center;background: rgba(66, 133, 244, 0.10);">
                                <td style="padding-top: 40px;padding: 10px 0 !important;padding: 10px 0 !important;background: #eaeaea;">
                                    <b style="text-decoration: underline;"> INVOICE</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin: 0 auto; border-bottom: 1px solid var(--button-color);">
                        <tbody>
                            <tr>
                                <td style="border-right: 1px solid var(--button-color); width: 50%; padding-left: 15px;">
                                    <p>
                                        <b>Invoice number:</b> {{(!empty($invoice->prefix))?$invoice->prefix.'-':''}} {{$invoice->invoice_number}}
                                    </p>
                                    <p>
                                        <b>Invoice date:</b> {{date('d M Y',strtotime($invoice->invoice_date))}}
                                    </p>
                                </td>
                                <?php
                                $billing_status = "";
                                if ($invoice->billing_status == "1") {
                                    $billing_status = '<span class="badge badge-success in-pen">Paid</span>';
                                }
                                if ($invoice->billing_status == "2") {
                                    $billing_status = '<span class="badge badge-info in-pen">Partial</span>';
                                }
                                if ($invoice->billing_status == "3") {
                                    $billing_status = '<span class="badge badge-warning in-pen">Pending</span>';
                                }
                                ?>
                                <td style="width: 50%; padding-left: 15px;">
                                    <p>
                                        <b>Due date:</b> {{date('d M Y',strtotime($invoice->due_date))}}
                                    </p>
                                    <p>
                                        <b>Payment Status:</b> {!!$billing_status!!}
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin: 0 auto; border-bottom: 1px solid var(--button-color); margin-bottom: 30px;">
                        <tbody>
                            <tr style="width: 100%;">
                                <td style="border-right: 1px solid var(--button-color); width: 50%; padding-left: 15px;">
                                    <p><b>From :</b></p>

                                </td>
                                <td style=" width: 50%; padding-left: 15px;">
                                    <p><b>To:</b></p>
                                    <p><b style="text-transform: uppercase;">{{$customer->name}}</b></p>

                                    @if(!empty($customer->tax_value))
                                    <p><b>{{($customer->tax_format)?$customer->tax_format:'GST No'}}:</b> {{$customer->tax_value}}</p>
                                    @endif
                                    <p><b>Contact No:</b> {{$customer->phone}}</p>
                                    <p><b>Email:</b> {{$customer->email}}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin: 0 auto; border-bottom: 1px solid var(--button-color);">
                        <tbody>
                            <tr style="background: #eaeaea;">
                                <td style="border-bottom: 1px solid var(--button-color); border-right: 1px solid var(--button-color);; padding: 10px; border-top: 1px solid var(--button-color);">
                                    <b>Items</b>
                                </td>
                                <td style="border-bottom: 1px solid var(--button-color); border-right: 1px solid var(--button-color); padding: 10px; border-top: 1px solid var(--button-color);">
                                    <b>Rate</b>
                                </td>
                                <td style="border-bottom: 1px solid var(--button-color); border-right: 1px solid var(--button-color); padding: 10px; border-top: 1px solid var(--button-color);">
                                    <b>Area / Quantity </b>
                                </td>
                                <td style="border-bottom: 1px solid var(--button-color); border-right: 1px solid var(--button-color); padding: 10px; border-top: 1px solid var(--button-color);">
                                    <b>Unit</b>
                                </td>
                                <td style="border-bottom: 1px solid var(--button-color); border-right: 1px solid var(--button-color); padding: 10px; border-top: 1px solid var(--button-color);">
                                    <b>Discount</b>
                                </td>
                                <td style="border-bottom: 1px solid var(--button-color); border-right: 1px solid var(--button-color); padding: 10px; border-top: 1px solid var(--button-color);">
                                    <b>Tax</b>
                                </td>
                                <td style="border-bottom: 1px solid var(--button-color); padding: 10px; border-top: 1px solid var(--button-color);">
                                    <b>Amount</b>
                                </td>
                            </tr>

                            @foreach($invoice_items as $invoice_item)

                            <?php
                            $taxs = [];
                            if (!empty($invoice_item->tax)) {
                                $taxs = ($invoice_item->tax) ? json_decode($invoice_item->tax) : [];
                            }
                            ?>
                            <tr>
                                <td style=" border-right: 1px solid var(--button-color); padding: 10px; line-height: 1.5;">
                                    <b>{{$invoice_item->property ? $invoice_item->property->propertyContent->title : ''}}</b>
                                    <br>{{$invoice_item->details}}
                                </td>
                                <td style=" border-right: 1px solid var(--button-color); padding: 10px;">{{Helper::decimal_number($invoice_item->price)}}</td>
                                <td style=" border-right: 1px solid var(--button-color); padding: 10px;">{{$invoice_item->quantity}}</td>
                                <td style=" border-right: 1px solid var(--button-color); padding: 10px;">{{$invoice_item->unit}}</td>
                                <td style=" border-right: 1px solid var(--button-color); padding: 10px;">{{Helper::decimal_number($invoice_item->discount)}}</td>
                                <td style=" border-right: 1px solid var(--button-color); padding: 10px;"> @if(count($taxs) > 0)
                                    @foreach($taxs as $tax)
                                    {{$tax}}%<br>
                                    @endforeach
                                    @endif
                                </td>
                                <td style=" padding: 10px;">{{Helper::decimal_number($invoice_item->price * $invoice_item->quantity)}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin: 0 auto;">
                        <tbody>
                            <tr>
                                <td style="text-align: end;">
                                    <b>Total:</b>
                                </td>
                                <td style="text-align: end;
								width: 11%;
								padding: 10px 20px 10px 10px;">{{Helper::decimal_number($invoice->sub_total)}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: end;">
                                    <b>Total Discount:</b>
                                </td>
                                <td style="text-align: end;
								width: 11%;
								padding: 10px 20px 10px 10px;">{{Helper::decimal_number($invoice->total_discount)}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table border="0" cellpadding="0" cellspacing="0" style="width:30%; margin: 0 0 0 auto; border-left: 1px solid var(--button-color);">
                        <tbody>
                            <tr style="background: #eaeaea;">
                                <td style="text-align: end; padding: 10px; border-right: 1px solid var(--button-color); border-bottom: 1px solid var(--button-color);">
                                    <b>Sub Total:</b>
                                </td>
                                <td style="text-align: end; padding: 10px; border-bottom: 1px solid var(--button-color);">{{Helper::decimal_number($invoice->sub_total - $invoice->total_discount)}}</td>
                            </tr>

                            <?php
                            if (!empty($invoice->tax_value) && $invoice->tax_value !== 'null') {
                                $taxvlaues = json_decode($invoice->tax_value, true);
                                foreach ($taxvlaues as $key => $taxvlaue) {
                                  ?>
                                    <tr>
                                        <td style="text-align: end; padding: 10px; border-right: 1px solid var(--button-color);">
                                            <b>{{$key}}:</b>
                                        </td>
                                        <td style="text-align: end; padding: 10px;">{{Helper::decimal_number($taxvlaue)}}</td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>

                            <tr>
                                <td style="text-align: end; padding: 10px; border-right: 1px solid var(--button-color); border-top: 1px solid var(--button-color);">
                                    <b>Grand Total:</b>
                                </td>
                                <td style="text-align: end; padding: 10px; border-top: 1px solid var(--button-color);">{{Helper::decimal_number($invoice->grand_total)}}</td>
                            </tr>

                            @if(count($billings) > 0)
                            @foreach($billings as $billing)
                            <tr>
                                <td style="text-align: end; padding: 10px; border-right: 1px solid var(--button-color); border-top: 1px solid var(--button-color);">
                                    <b>Payment On {{date('d M Y',strtotime($billing->payment_date))}}:</b>
                                </td>
                                <td style="text-align: end; padding: 10px; border-top: 1px solid var(--button-color);">{{Helper::decimal_number($billing->amount)}}</td>
                            </tr>
                            @endforeach
                            @endif
                            @if($amount_due_show != "query")
                            <tr>
                                <td style="text-align: end; padding: 10px; border-right: 1px solid var(--button-color); border-top: 1px solid var(--button-color);">
                                    <b>Amount Due:</b>
                                </td>@php($amount_due = Helper::getInvoiceRecordPartial($invoice->grand_total,$invoice->id))
                                <td style="text-align: end; padding: 10px;">{{Helper::decimal_number($amount_due)}}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin: 0 auto;">
                        <tbody>
                            <tr style="background: #eaeaea;">
                                <td style=" border-bottom: 1px solid var(--button-color); padding: 10px;">

                                    @if($amount_due_show == "query")
                                    <b>Total Amount: {{Helper::AmountInWords($invoice->grand_total)}} </b>
                                    @else
                                    @if($amount_due > 0)
                                    <b>Amount Due: {{Helper::AmountInWords($amount_due)}} </b>
                                    @else
                                    <b>Amount Due: Zero</b>
                                    @endif
                                    @endif

                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin: 0 auto;">
                        <tbody>
                            <tr>
                                <td style="width: 50%; border-right: 1px solid var(--button-color); padding: 20px 0 20px 10px;">
                                    <b style="text-decoration: underline;">Notes / Terms : {{$invoice->footer_note}}</b>
                                </td>
                                <td style="padding-left: 10px; vertical-align: baseline;">
                                    <p style="border-bottom: 1px solid var(--button-color);
									width: auto;
									line-height: 2.5;text-align: end;
									padding: 10px 10px 0px 10px;"> Reciver's Signature: </p>
                                    <p style="text-align: end;
									padding: 10px 10px 0px 10px;">

                                    </p>
                                    <p style="text-align: end;
									padding: 10px 10px 10px 10px;">
                                        <b>Authorised Signatory</b>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>
