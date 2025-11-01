<?php

namespace App\Http\Controllers\BackEnd;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Property\PrtFloorStatus;
use App\Models\Property\Unit;
use App\Models\SaleInvoice;
use App\Models\SaleInvoiceBilling;
use App\Models\SaleInvoiceItem;
use Auth;
use DB;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Validator;

class AccountController extends Controller
{

    public function salesBillListing()
    {

        $prtStatus = SaleInvoice::select('sale_invoices.*','mstc.name')
                  ->where('sale_invoices.type','invoice')
                  ->join('customers as mstc','mstc.id','=','sale_invoices.customer_id')
                  ->get();

        return view('backend.account.sales-billing', compact('prtStatus'));
    }

    public function index()
    {
        $customerIds = DB::table('prt_floor_status')->whereNotNull('customer_id')->distinct()->pluck('customer_id');
        $customers = Customer::whereIn('id',$customerIds)->get();

        return view('backend.account.index',compact('customers'));
    }

    public function customerDetails($customer_id)
    {
      	$customer = Customer::where('id',$customer_id)->first();

        $output = '';
        $output .= ' <h3>Bill to. </h3><h3>'.$customer->name.' </h3><h3>'.$customer->phone_number.' </h3>';
        return response()->json(['status'=>'success','output' =>$output]);
    }

    public function salesBillStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
          'customer_id' => 'required',
          'invoice_number' => 'required',
          'invoice_date' => 'required',
          'due_date' => 'required',
          'recurring' => 'required',

        ]);
        if ($validator->fails()) {
          return response()->json([
          'status' => 'validation',
          'errors' => $validator->errors()
          ]);
        }


        error_reporting(0);

        if(!isset($request->items))
        {
          return response()->json(['status'=>'error','msg'=>"You have't added any item."]);
        }

        try {
          DB::beginTransaction();
          $user_id = Auth::user()->id;

          $data = $request->only('customer_id','recurring','repeat_every_custom','repeat_type_custom','cycle','invoice_date','due_date','total_discount','grand_total','convert_total','footer_note','invoice_number','prefix','show_bank_id');
          $data['tax_value'] = json_encode($request->tax_value);
          $data['sub_total'] = $request->subtotal;
          $data['time'] = date('H:i:s');
          $data['type'] = 'invoice';
          $data['created_at'] = date('Y-m-d H:i:s');
          $data['updated_at'] = date('Y-m-d H:i:s');

          $object = new SaleInvoice();
          Helper::saveData($object,$data);
          $invoice_id = $object->id;

          $items = $request->items;
          $taxs = ($request->tax)?$request->tax:[];


          foreach($items as $key => $item)
          {
            $tax_key = '';
            if(count($taxs) > 0)
            {
              if(isset($taxs[$key]))
              {
                $tax_key = json_encode($taxs[$key]);
              }
            }

            $invoice_items = [
            'customer_id'=>$request->customer_id,
            'invoice_id'=>$invoice_id,
            'name'=>$item,
            'details'=>$request->details[$key],
            'price'=>$request->price[$key],
            'quantity'=>$request->quantity[$key],
            'unit'=>$request->unit[$key],
            'item_id'=>$request->item_id[$key],
            'hsn_sac'=>$request->hsn_sac[$key],
            'include_tax'=>0,
            'discount'=>($request->discount[$key])?$request->discount[$key]:0,
            'tax'=>$tax_key,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
            ];

            $object1 = new SaleInvoiceItem();
            Helper::saveData($object1,$invoice_items);

          }

          Helper::storeAutoInvoiceNumber(['type'=>'invoice','invoice_id'=>$invoice_id]);
          DB::commit();
          return response()->json(['status'=>'success','msg'=>'The sale invoice has been successfully added.']);
			}
			catch (\Throwable $e)
			{
				DB::rollBack();
				return response()->json(['status' => 'error', 'msg' => 'Something went wrong']);
			}
    }

    public function salesBillItem($i,Request $request)
    {
      try{
        $propertyStatus = PrtFloorStatus::with('customer','property','wing','floors','Units','property.propertyContent')->where('customer_id',$request->customer_id)->get();
        $accountUnits = Unit::whereStatus(1)->get();
        $view = view('backend.account.add-item',compact('i','accountUnits','propertyStatus'))->render();
        return response()->json(['view'=>$view]);
      }catch(Exception $e) {
        	return response()->json(['status' => 'error', 'msg' => 'Something went wrong']);
      }
    }

    public function salesBillInvoiceView($id)
    {

        $invoice = SaleInvoice::where('id',$id)->first();
        $invoice_items = SaleInvoiceItem::with('property')->where('invoice_id',$id)->get();

        // $branch = MstBranch::select('mst_branches.*','mst_countries.name as country_name','mst_states.state_name','mst_cities.city_name')->where('mst_branches.id',$invoice->branch_id)->join('mst_countries','mst_countries.id','=','mst_branches.country_id')->join('mst_states','mst_states.id','=','mst_branches.state_id')->join('mst_cities','mst_cities.id','=','mst_branches.city_id')->first();

        $customer = Customer::select('customers.*')->where('customers.id',$invoice->customer_id)->first();
        $billings = SaleInvoiceBilling::whereInvoice_id($id)->get();
        $width = 70;

        return view('backend.account.invoice-view',compact('customer','width','invoice','invoice_items','id','billings'));
    }

      public function salesBillExport($id)
      {
          $invoice = SaleInvoice::where('id',$id)->first();
          $invoice_items = SaleInvoiceItem::where('invoice_id',$id)->get();


          $customer = Customer::select('customers.*')->where('customers.id',$invoice->customer_id)->first();
          $billings = SaleInvoiceBilling::whereInvoice_id($id)->get();

          $width = 100;
          $prefix = (!empty($invoice->prefix))?$invoice->prefix.'-':'';
          /* echo view('users.account.invoice-view.export_style_1',compact('customer','branch','invoice','invoice_items','id','width','billings'));
          die; */
          $amount_due_show = "invoice";

          // $pdf = PDF::loadView('backend.account.export_style_1',compact('customer','invoice','invoice_items','id','width','billings'));

         $pdf = PDF::loadView('backend.account.export_style_1', compact('customer', 'invoice', 'invoice_items', 'id', 'width', 'billings', 'amount_due_show'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($prefix . $invoice->invoice_number . '.pdf');
      }

      public function salesBillPayment($invoice_id)
      {
          $saleInvoice = SaleInvoice::where('id',$invoice_id)->first();
          // $banks = AccBankCash::whereStatus(1)->where('enterprice_code',$enterprice_code)->where('bz_id',$bz_id)->where('branch_id',$branch_id)->get();
          $amount_due = Helper::getInvoiceRecordPartial($saleInvoice->grand_total,$saleInvoice->id);
          $view = view('backend.account.record-payment',compact('invoice_id','saleInvoice','amount_due'))->render();
          return response()->json(['view'=>$view]);
      }

      public function purchaseBillPaymentStore(Request $request)
      {
          $data = $request->except('_token','total_amount','total_amount');
          // $data['user_id'] = $user_id;
          $data['invoice_type'] = 'bill';
          $data['created_at'] = date('Y-m-d H:i:s');
          $data['updated_at'] = date('Y-m-d H:i:s');

          try
          {
            DB::beginTransaction();
            $object = new SaleInvoiceBilling();
            Helper::saveData($object,$data);
            $billing_id = $object->id;

            $partamt = Helper::getInvoiceRecordPartial($request->total_amount,$request->invoice_id);
            SaleInvoice::whereId($request->invoice_id)->update(['billing_status'=>2]);

            $note = "INV-".$request->invoice_id;

            if($partamt == 0 || $partamt <= 0)
            {
              SaleInvoiceBilling::whereId($billing_id)->update(['status'=>1]);
              SaleInvoice::whereId($request->invoice_id)->update(['billing_status'=>1]);
            }

            Helper::trnBankCash(['bank_id'=>$request->bank_id,'module_id'=>$billing_id,'transaction_type'=>'bill','transaction_mode'=>'debit','debit'=>$request->amount,'date'=>$request->payment_date,'note'=>$note]);

            DB::commit();
            return response()->json(['status'=>'success','msg'=>'The payment has been successfully added.']);
          }
          catch (\Throwable $e)
          {
            DB::rollBack();
            if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] === 1451) {
              $message = trans('custom.delete_msg');
              } else {
              $message = $e->getMessage();
            }
            return response()->json(['status' => 'error', 'msg' => $message]);
          }
      }

      public function salesBillViewPayment($invoice_id)
      {

        $billingInvices = SaleInvoiceBilling::select('sale_invoice_billings.*')->where('sale_invoice_billings.invoice_id',$invoice_id)->orderBy('sale_invoice_billings.id','asc')->get();

        $paidInvoice = SaleInvoiceBilling::where('sale_invoice_billings.invoice_id',$invoice_id)->where('sale_invoice_billings.status',1)->count();

        $view = view('backend.account.view-payment',compact('billingInvices','paidInvoice','invoice_id'))->render();
        return response()->json(['view'=>$view]);
      }

      public function salesBillEdit($invoice_id)
      {
        $customers = Customer::get();
        // $taxes = MstTax::whereStatus(1)->where('enterprice_code',$enterprice_code)->get();
        $saleInvoice = SaleInvoice::where('id',$invoice_id)->first();
        $invoiceItems = SaleInvoiceItem::where('invoice_id',$invoice_id)->get();
        $accountUnits = Unit::whereStatus(1)->get();
        $propertyStatus = PrtFloorStatus::with('customer','property','wing','floors','Units','property.propertyContent')->where('customer_id',$saleInvoice->customer_id)->get();

        return view('backend.account.edit',compact('saleInvoice','invoiceItems','customers','accountUnits','propertyStatus'));
	 	  }

      public function salesBillUpdate(Request $request,$id)
      {
        $validator = Validator::make($request->all(), [
          'customer_id' => 'required',
          'invoice_number' => 'required',
          'invoice_date' => 'required',
          'due_date' => 'required',
          'recurring' => 'required',

        ]);
        if ($validator->fails()) {
          return response()->json([
          'status' => 'validation',
          'errors' => $validator->errors()
          ]);
        }
        if(!isset($request->items))
        {
          return response()->json(['status'=>'error','msg'=>"You have't added any item."]);
        }

        try {
          DB::beginTransaction();

          $data = $request->only('customer_id','recurring','repeat_every_custom','repeat_type_custom','cycle','invoice_date','due_date','total_discount','grand_total','convert_total','footer_note','invoice_number','prefix','show_bank_id');
          $data['tax_value'] = json_encode($request->tax_value);
          $data['sub_total'] = $request->subtotal;
          $data['updated_at'] = date('Y-m-d H:i:s');

          $object = SaleInvoice::find($id);
          Helper::saveData($object,$data);

          $items = $request->items;
          $item_ids = $request->item_id;
          foreach($item_ids as $key12 => $item_id)
          {
            if(!isset($items[$key12]))
            {
              SaleInvoiceItem::where('id',$item_id)->delete();
            }
          }

          $taxs = ($request->tax)?$request->tax:[];

          foreach($items as $key => $item)
          {
            $tax_key = '';
            if(count($taxs) > 0)
            {
              if(isset($taxs[$key]))
              {
                $tax_key = json_encode($taxs[$key]);
              }
            }

            $invoice_items = [
            'item_id'=>$request->status_item_id[$key],
            'customer_id'=>$request->customer_id,
            'invoice_id'=>$id,
            'name'=>$item,
            'details'=>$request->details[$key],
            'price'=>$request->price[$key],
            'quantity'=>$request->quantity[$key],
            'unit'=>$request->unit[$key],
            'hsn_sac'=>$request->hsn_sac?$request->hsn_sac[$key]:'',
            'include_tax'=>0,
            'discount'=>($request->discount[$key])?$request->discount[$key]:0,
            'tax'=>$tax_key,
            'updated_at'=>date('Y-m-d H:i:s'),
            ];

            if(isset($request->item_id[$key]))
            {
              $object1 = SaleInvoiceItem::find($request->item_id[$key]);
              Helper::saveData($object1,$invoice_items);
            }
            else
            {
              $object1 = new SaleInvoiceItem();
              Helper::saveData($object1,$invoice_items);
              $item_id = $object1->id;
            }
          }

          $invoicebilling = SaleInvoiceBilling::whereInvoice_id($id)->whereStatus(1)->first();
          if(!empty($invoicebilling))
          {
            SaleInvoiceBilling::whereId($invoicebilling->id)->update(['status'=>2]);
            SaleInvoice::where('id',$id)->update(['billing_status'=>2]);
          }
          DB::commit();
          return response()->json(['status'=>'success','msg'=>'The sale invoice have been updated successfully.']);
        }
        catch (\Throwable $e)
        {
          dd($e);
          return response()->json(['status' => 'error', 'msg' => 'Something Went Wrong.']);
        }

      }

      public function salesBillDelete($id)
      {
          try
          {
            DB::beginTransaction();
            SaleInvoice::find($id)->delete();
            DB::commit();
            return response()->json(['status'=>'success','msg'=>'The sale invoice has been successfully deleted.']);
          }
          catch (\Throwable $e)
          {
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => 'Something Went Wrong']);
          }
      }

      public function salesBillStopRecurring($id)
      {
        try
        {
          DB::beginTransaction();
          $object = SaleInvoice::find($id);
          Helper::saveData($object,['recurring'=>0,'recurring_stop'=>1,'recurring_stop_datetime'=>now()]);
          DB::commit();
          return response()->json(['status'=>'success','msg'=>'The recurring has been successfully stop']);
        }
        catch (\Throwable $e)
        {
          DB::rollBack();
          return response()->json(['status' => 'error', 'msg' => 'Somwthing Went Wrong']);
        }
      }
}
