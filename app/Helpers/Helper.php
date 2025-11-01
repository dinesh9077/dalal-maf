<?php
	namespace App\Helpers;

use App\Models\Agent;
use App\Models\Project\Project;
use App\Models\Property\Property;
use App\Models\Property\PropertyContact;
use App\Models\SaleAutoNumber;
use App\Models\SaleInvoiceBilling;
use App\Models\TrnBankCash;
use App\Models\User;
use App\Models\Vendor;

	class Helper
	{

    public static function saveData($object,$data)
		{
			$object->fillable(array_keys($data));
			$object->fill($data);
			$object->save();
		}

    public static function decimalsprint($number,$decimal=0)
		{
			  return sprintf("%0.".$decimal."f", $number);
		}

    public static function unreadProeprties($type)
    {
        $totalCount = Property::where('property_type',$type)->where('is_new','0')->count();
        return $totalCount;
    }

    public static function unreadInquiries()
    {
        $totalCount = PropertyContact::where('is_new','0')->count();
        return $totalCount;
    }

    public static function unreadProjects()
    {
        $totalCount = Project::where('is_new','0')->count();
        return $totalCount;
    }

    public static function unreadStaffs()
    {
        $totalCount = Agent::where('is_new','0')->count();
        return $totalCount;
    }

    public static function unreadPartners()
    {
        $totalCount = Vendor::where('is_new','0')->count();
        return $totalCount;
    }

    public static function unreadUsers()
    {
        $totalCount = User::where('is_new','0')->count();
        return $totalCount;
    }

    public static function storeAutoInvoiceNumber($data)
		{
			$currentTime = now();
      $data['customer_id'] = '1';
			$data['created_at'] = $currentTime;
			$data['updated_at'] = $currentTime;

			return SaleAutoNumber::insert($data);
		}

    public static function getAutoInvoiceNumber($type)
		{
			return SaleAutoNumber::where('type',$type)

			->count() + 1;
		}

    public static function decimal_number($number, $decimal = null)
    {
        // Ensure $number is a valid numeric value
        if (!is_numeric($number)) {
            return 'Invalid number';  // You can return some fallback or error message
        }

        if (empty($decimal)) {
            $decimal = 2;
        }

        $integerPart = floor($number);
        $decimalPart = $number - $integerPart;

        // Format the integer part with commas
        $integerPart = number_format($integerPart);

        // Format the decimal part
        $decimal = substr(sprintf("%.2f", $decimalPart), 1);  // Get the decimal part, formatted to two decimals

        // Combine integer and decimal parts
        return $integerPart . $decimal;
    }


    public static function getInvoiceRecordPartial($total_grand,$invoice_id)
		{
			$partial_amount = SaleInvoiceBilling::whereInvoice_id($invoice_id)->sum('amount');
			return $total_grand - $partial_amount;
		}

    public static function AmountInWords(float $amount)
		{
			$amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
			// Check if there is any number after decimal
			$amt_hundred = null;
			$count_length = strlen($num);
			$x = 0;
			$string = array();
			$change_words = array(0 => '', 1 => 'One', 2 => 'Two',
			3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
			7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
			10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
			13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
			16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
			19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
			40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
			70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
			$here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
			while( $x < $count_length ) {
				$get_divider = ($x == 2) ? 10 : 100;
				$amount = floor($num % $get_divider);
				$num = floor($num / $get_divider);
				$x += $get_divider == 10 ? 1 : 2;
				if ($amount) {
					$add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
					$amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
					$string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.'
					'.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. '
					'.$here_digits[$counter].$add_plural.' '.$amt_hundred;
				}
				else $string[] = null;
			}
			$implode_to_Rupees = implode('', array_reverse($string));
			$get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . "
			" . $change_words[$amount_after_decimal % 10]) . '' : '';
			return ($implode_to_Rupees ? $implode_to_Rupees . '' : '') . $get_paise;
		}

    public static function trnBankCash($data)
		{
			$data['created_at'] = now();
			$data['updated_at'] = now();
			TrnBankCash::insert($data);
			Helper::updateClosingBalance($data['bank_id']);
		}

    public static function updateClosingBalance($bank_id)
		{
			// Use a single query to sum both credits and debits
			$transactionSummary = TrnBankCash::whereBank_id($bank_id)
			->selectRaw('SUM(CASE WHEN transaction_mode = "credit" THEN credit ELSE 0 END) as total_credit')
			->selectRaw('SUM(CASE WHEN transaction_mode = "debit" THEN debit ELSE 0 END) as total_debit')
			->first();

			// Calculate balance
			$balance = $transactionSummary->total_credit - $transactionSummary->total_debit;

			// Update the closing balance
			// AccBankCash::whereId($bank_id)->update(['closing_balance' => $balance]);
		}

  }
