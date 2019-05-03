<?php

require("Report/fpdf.php");  
  
class PDF_checklist extends FPDF 
{ 
	/*****************************************************************************************************************/
    function Header() 
   	{ 
		$this->SetTextColor(255,77,0);
	    $this->SetFont('Arial', 'B', 15); 
	    $this->Cell(0, 6, "TITLE OF DOCUMENT", 0, 1, 'C'); 
	    $this->SetFont('Arial', 'B', 9);
	    $this->SetTextColor(9,17,141);
	    $this->Cell(0,6, "Sub Title", 0,1, 'C');
		$this->SetFont('Arial', '', 9);
	    $now = date('d/m/Y h:i:sa',time()+(4*60+30)*60);
		$this->Ln();
		$this->Cell(50,5, 'Printed On: '.strval($now), 0,0, 'L');
		$this->Cell(96,5, "", 0,0, 'C');
		$this->Cell(0,5,'Page# '.$this->PageNo().'/{nb}', 0, 1, 'R');
	    $this->Cell(0,1, "", 'B', 1, 'C');

	    $header1 	= array("Bank Name", "Branch Name", "Customer Name", "Account Number","Transaction Type","Narration","Counter Party","Suspect Type","Relation","Debit","Credit"); 
	    $w = array(25, 25, 25, 25, 23, 33, 32,25,30,20,20); 
		$i = 0;
        foreach ($header1 as $value) 
        {
        	$x=$this->GetX();
			$y=$this->GetY();
			$this->SetTextColor(9,17,141);
			$this->SetFont('Arial', 'B', 9);
			$this->MultiCell($w[$i],5,$value,0,'C');
			$this->SetXY($x+$w[$i],$y);
			$i++;
        }
        $this->Ln(15);
	}

  	/*****************************************************************************************************************/
  	/*****************************************************************************************************************/
	public function auto_fit($length_array)
	{
		$returned = [];
		foreach($length_array as $str)
		{
			$str1;
			if(strlen($str)>12)
			{
				$str1 = wordwrap($str, 12, "\n");
			}
		}
	}
	
  	function ItemTable($details,$w)
    {
		$i = 0;
		$total_amount = 0;
		foreach($details as $values)
	    {
	    	if($i != 0)
	    	{
		    	if((float)$values[6] > 0)
		    	{
		    		$total_amount = $total_amount - (float)$values[6]; 
		    	}
		    	else
		    	{
		    		$total_amount = $total_amount + (float)$values[7];
		    	}
		    	$this->SetTextColor(0,0,0);
				$this->SetFont('Arial','',7);				  
				$h=15;
				$x=$this->getx();
				$this->myCell($w[0],$h,$x,$values[0],18);
				$x=$this->getx();
				$this->myCell($w[1],$h,$x,$values[1],15);
				$x=$this->getx();
				$this->myCell($w[2],$h,$x,$values[2],15);
				$x=$this->getx();
				$this->myCell($w[3],$h,$x,$values[3],18);
				$x=$this->getx();
				$this->myCell($w[4],$h,$x,$values[4],18);
				$x=$this->getx();
				$this->myCell($w[5],$h,$x,preg_replace('!\s+!', ' ', $values[5]),18);
				$x=$this->getx();
				$this->myCell($w[6],$h,$x,preg_replace('!\s+!', ' ', $values[8]),18);
				$x=$this->getx();
				$this->myCell($w[7],$h,$x,$values[9],15);
				$x=$this->getx();
				$this->myCell($w[8],$h,$x,$values[10],18);

				$x=$this->getx();
				$this->myCell($w[9],$h,$x,$values[6],18);
				$x=$this->getx();
				$this->myCell($w[10],$h,$x,$values[7],18);
				$this->Ln();
			
	    	}
	    	$i++;
		}
		return $total_amount;
  	}
	
	
  	/*****************************************************************************************************************/
  	/*****************************************************************************************************************/
    function ItemTotal($final_total, $amt_words,$w) 
    {
    	$this->SetTextColor(9,17,141);
        $this->SetFont('Arial', 'B', 9);
        $x=$this->GetX();
        $y=$this->GetY();
        $this->MultiCell($w[0] + $w[1],7,"Total: ",1,'R'); 
        $this->SetXY($x+$w[0] + $w[1],$y);
        $this->Cell($w[2] + $w[3] + $w[4] + $w[5] + $w[6] + $w[7] + $w[8] + $w[9] + $w[10],7,strval($final_total),1,1,'R');

        $x=$this->GetX();
        $y=$this->GetY();
        $this->MultiCell($w[0] + $w[1],6,"In Words: ",1,'R'); 
        $this->SetXY($x+$w[0] + $w[1],$y);
        $this->Cell($w[2] + $w[3] + $w[4] + $w[5] + $w[6] + $w[7] + $w[8] + $w[9] + $w[10],7,strval($amt_words),1,1,'R');
    }
    /*****************************************************************************************************************/
  	/*****************************************************************************************************************/
	function myCell($w,$h,$x,$t,$max)
	{
        $height=$h/3;
        $first=$height+2;
        $second=$height+$height+$height+3;
        $len=strlen($t);
        if($len>$max)
        {
            $txt=str_split($t,$max);
            $this->SetX($x);
            $this->Cell($w,$first,$txt[0],'','','');
            $this->SetX($x);
			if(strlen($txt[1])>$max)
			{
				$txt2 = str_split($txt[1],$max);
				$this->Cell($w,$first,$txt2[0],'','','');
                $this->SetX($x);
			    $this->Cell($w,$second,$txt2[1],'','','');
                $this->SetX($x);
			}
			else
			{
				$this->Cell($w,$second,$txt[1],'','','');
                $this->SetX($x);
			}	
			$this->Cell($w,$h,'','LTRB',0,'L',0);
        }
        else
        {
            $this->SetX($x);
            $this->Cell($w,$h,$t,'LTRB',0,'L',0);
        }
    }
	/*****************************************************************************************************************/
  	/*****************************************************************************************************************/
    public function convertNumber($number)
   	{
		$integer = "";
		$fraction = "";
		$output = "";
		if($number == 0) 
        {
			$integer = $number;
			$fraction = $number;
			$output .= "zero";
   		}
	   	else 
        {
		  	list($integer, $fraction) = explode(".", $number);
			if ($integer{0} == "-")
			{
				$output = "negative ";
				$integer    = ltrim($integer, "-");
			}
			else if ($integer{0} == "+")
			{
				$output = "positive ";
				$integer    = ltrim($integer, "+");
			}
			$integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
        	$group   = rtrim(chunk_split($integer, 3, " "), " ");
        	$groups  = explode(" ", $group);
        	$groups2 = array();
        	foreach ($groups as $g)
			{
				$groups2[] = $this->convertThreeDigit($g{0}, $g{1}, $g{2});
			}

        	for ($z = 0; $z < count($groups2); $z++)
          	{
            	if ($groups2[$z] != "")
             	{
                	$output .= $groups2[$z] . $this->convertGroup(11 - $z) . (
                        $z < 11
                        && !array_search('', array_slice($groups2, $z + 1, -1))
                        && $groups2[11] != ''
                        && $groups[11]{0} == '0'
                            ? " and "
                            : ", "
                    );
            	}
        	}
        	$output = rtrim($output, ", ");
			if ($fraction > 0)
          	{
				$output .= " point";
				for ($i = 0; $i < strlen($fraction); $i++)
				{
					$output .= " " . $this->convertDigit($fraction{$i});
				}
         	}
	 	}
     	return $output;
   	}
   	/*****************************************************************************************************************/
   	/*****************************************************************************************************************/
    function ItemDetails($details,$w) 
    {             
		$total_amount = $this->ItemTable($details,$w);
		$word_figure = $this->convertNumber($total_amount);
		$this->ItemTotal($total_amount, $word_figure, $w);
    }
    /*****************************************************************************************************************/
    /*****************************************************************************************************************/
    function Footer()
    { 

		$this->SetFont('Arial', '', 11); 
		$this->SetTextColor(0); 
		$this->SetXY(7,-20); 
		$this->Cell(0, 20, "", 'T', 0, 'R'); 
   	}
    /*****************************************************************************************************************/
    /*****************************************************************************************************************/
	function getIndianCurrency(float $number)
    {
	    $decimal = round($number - ($no = floor($number)), 2) * 100;
	    $hundred = null;
	    $digits_length = strlen($no);
	    $i = 0;
	    $str = array();
	    $words = array(0 => '', 1 => 'one', 2 => 'two',
	        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
	        7 => 'seven', 8 => 'eight', 9 => 'nine',
	        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
	        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
	        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
	        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
	        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
	        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
	    $digits = array('', 'hundred','thousand','lakh', 'crore');
	    while( $i < $digits_length ) 
	    {
	        $divider = ($i == 2) ? 10 : 100;
	        $number = floor($no % $divider);
	        $no = floor($no / $divider);
	        $i += $divider == 10 ? 1 : 2;
	        if ($number) 
	        {
	            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
	            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
	            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
	        } else $str[] = null;
	    }
	    $Rupees = implode('', array_reverse($str));
	    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
	    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise ;
	}
   	/*****************************************************************************************************************/
   	/*****************************************************************************************************************/
    function convertGroup($index)
    {
        switch ($index)
		{
			case 11:
				return " decillion";
			case 10:
				return " nonillion";
			case 9:
				return " octillion";
			case 8:
				return " septillion";
			case 7:
				return " sextillion";
			case 6:
				return " quintrillion";
			case 5:
				return " quadrillion";
			case 4:
				return " trillion";
			case 3:
				return " billion";
			case 2:
				return " million";
			case 1:
				return " Thousand";
			case 0:
				return "";
		}
    }
    /*****************************************************************************************************************/
    /*****************************************************************************************************************/
    function convertThreeDigit($digit1, $digit2, $digit3)
    {
        $buffer = "";
		if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0")
		{
			return "";
		}
		if ($digit1 != "0")
		{
			$buffer .= $this->convertDigit($digit1) . " hundred";
			if ($digit2 != "0" || $digit3 != "0")
			{
				$buffer .= " and ";
			}
		}
		if ($digit2 != "0")
		{
			$buffer .= $this->convertTwoDigit($digit2, $digit3);
		}
		else if ($digit3 != "0")
		{
			$buffer .= $this->convertDigit($digit3);
		}
        return $buffer;
    }
    /*****************************************************************************************************************/
    /*****************************************************************************************************************/
    function convertTwoDigit($digit1, $digit2)
    {
        if ($digit2 == "0")
        {
			switch ($digit1)
			{
				case "1":
					return "Ten";
				case "2":
					return "Twenty";
				case "3":
					return "Thirty";
				case "4":
					return "Forty";
				case "5":
					return "Fifty";
				case "6":
					return "Sixty";
				case "7":
					return "Seventy";
				case "8":
					return "Eighty";
				case "9":
					return "Ninety";
		 	}
        }
        else if ($digit1 == "1")
        {
			switch ($digit2)
			{
				case "1":
					return "Eleven";
				case "2":
					return "Twelve";
				case "3":
					return "Thirteen";
				case "4":
					return "Fourteen";
				case "5":
					return "Fifteen";
				case "6":
					return "Sixteen";
				case "7":
					return "Seventeen";
				case "8":
					return "Eighteen";
				case "9":
					return "Nineteen";
			}
        } 
        else
        {
            $temp = $this->convertDigit($digit2);
			switch ($digit1)
			{
				case "2":
					return "Twenty-$temp";
				case "3":
					return "Thirty-$temp";
				case "4":
					return "Forty-$temp";
				case "5":
					return "Fifty-$temp";
				case "6":
					return "Sixty-$temp";
				case "7":
					return "Seventy-$temp";
				case "8":
					return "Eighty-$temp";
				case "9":
					return "Ninety-$temp";
			}
        }
    }
    /*****************************************************************************************************************/
    /*****************************************************************************************************************/
    function convertDigit($digit)
    {
		switch ($digit)
		{
			case "0":
				return "Zero";
			case "1":
				return "One";
			case "2":
				return "Two";
			case "3":
				return "Three";
			case "4":
				return "Four";
			case "5":
				return "Five";
			case "6":
				return "Six";
			case "7":
				return "Seven";
			case "8":
				return "Eight";
			case "9":
				return "Nine";
		}
    } 
    /*****************************************************************************************************************/
}




	$pdf = new PDF_checklist('P','mm','A4');
	$pdf->SetMargins(7,5,7);

	$pdf->AliasNbPages();
    $pdf->AddPage('L');

	$filename = "PDF_Generated_".date('Y-m-d_H-m-s').".pdf";

	/* Instead of file u can also get data from database*/
	$output = json_decode(file_get_contents('Sample.txt'));

   	/* Width For Columns Table Format */
	$width = array(25, 25, 25, 25, 23, 33, 32,25,30,20,20);
    $pdf->ItemDetails($output,$width);
	$path = dirname(__FILE__)."\\".$filename;  
	$pdf->SetAutoPageBreak(false);
	$pdf->Output($path, 'F');
	// print_r($path);
	 
	$extension = explode(".", $filename);
	// We'll be outputting a PDF
	header('Content-type: application/'.end($extension).'');

	// It will be called downloaded.pdf
	header('Content-Disposition: attachment; filename="'.$filename.'"');

	// The PDF source is in original.pdf
	readfile($path);

?>
