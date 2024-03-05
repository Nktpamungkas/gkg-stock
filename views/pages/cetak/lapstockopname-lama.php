<?php
include_once('../../../config/koneksi.php');
include_once('../../../controllers/opnameclass.php');
require('../../../dist/pdf/fpdf.php');
// koneksi ke MySQL via method
$db       = new Database();
$db->connectMySQL();
$opname   = new Opname();
$idsub    = $_GET['idsub'];
$awal     = $_GET['tgl1'];
$akhir    = $_GET['tgl2'];

class FPDF_AutoWrapTable extends FPDF {
  	private $data = array();
  	private $options = array(
  		'filename' => '',
  		'destinationfile' => '',
  		'paper_size'=>'F4',
  		'orientation'=>'P'
  	);

  	function __construct($data = array(), $options = array()) {
    	parent::__construct();
    	$this->data = $data;
    	$this->options = $options;
	}

	public function rptDetailData () {
		//
		$border = 0;
		$this->AddPage();
		$this->SetAutoPageBreak(true,60);
		$this->AliasNbPages();
		$left = 25;
    $this->settitle('Stock Barang');


		//$this->Ln(10);

		$h    = 13;
		$left = 40;
		$top  = 80;
		#tableheader

		//$this->Ln(20);

		$this->SetFont('Arial','',7);
		$this->SetWidths(array(100,80,60,60,60,90,90));
		$this->SetAligns(array('L','L','R','R','R','R','R'));
    $no = 1;
    $this->SetFillColor(255);
    foreach ($this->data as $baris) {
    if($baris['stok_akhir']<$baris['aktual']){
      $this->SetTextReds(array('0','0','0','0','0',255,'0'));
      $this->SetTextGreens(array('0','0','0','0','0',0,'0'));
      $this->SetTextBlues(array('0','0','0','0','0',0,'0'));
    }else{
      $this->SetTextReds(array('0','0','0','0','0',0,'0'));
      $this->SetTextGreens(array('0','0','0','0','0',0,'0'));
      $this->SetTextBlues(array('0','0','0','0','0',0,'0'));
    }
			$this->Row(
				array(
        $baris['nama'],
				$baris['jenis'],
				$baris['stok_awal'],
				$baris['stok_in'],
				$baris['stok_out'],
				$baris['stok_akhir'],
        $baris['aktual']
			));
      $tawal   = $tawal+$baris['stok_awal'];
      $tsin    = $tsin+$baris['stok_in'];
      $tsout   = $tsout+$baris['stok_out'];
      $takhir  = $takhir+$baris['stok_akhir'];
      $taktual = $taktual+$baris['aktual'];
		}
    $this->SetFillColor(200,200,200);
    $this->SetFont("Arial", "B", 7);
    $this->Cell(100, $h, '', 1, 0, 'C',true);
    $this->Cell(80, $h, 'Total', 1, 0, 'R',true);
    $this->Cell(60, $h, number_format($tawal,'2','.',''), 1, 0, 'R',true);
		$this->Cell(60, $h, number_format($tsin,'2','.',''), 1, 0, 'R',true);
		$this->Cell(60, $h, number_format($tsout,'2','.',''), 1, 0, 'R',true);
    $this->Cell(90, $h, number_format($takhir,'2','.',''), 1, 0, 'R',true);
    $this->Cell(90, $h, number_format($taktual,'2','.',''), 1, 1, 'R',true);
    $this->Ln(10);
    $this->Cell(0, 10, 'Keterangan :', 0, 1,'L');
    $this->Ln(20);
    $this->SetFillColor(255,255,255);
    $this->SetFont("Arial", "", 7);
    $this->Cell(180, $h, '', 1, 0, 'R',true);
    $this->Cell(180, $h, 'Dibuat Oleh :', 1, 0, 'C',true);
    $this->Cell(180, $h, 'Diperiksa Oleh :', 1, 1, 'C',true);
    $this->Cell(180, $h, 'NAMA', 1, 0, 'L',true);
    $this->Cell(180, $h, '', 1, 0, 'R',true);
    $this->Cell(180, $h, 'Agung Cahyono', 1, 1, 'C',true);
    $this->Cell(180, $h, 'JABATAN', 1, 0, 'L',true);
    $this->Cell(180, $h, '', 1, 0, 'C',true);
    $this->Cell(180, $h, 'QCF Manager', 1, 1, 'C',true);
    $this->Cell(180, $h, 'TANGGAL', 1, 0, 'L',true);
    $this->Cell(180, $h, date("d F Y"), 1, 0, 'C',true);
    $this->Cell(180, $h, date("d F Y"), 1, 1, 'C',true);
    $this->Cell(180, 40, 'TANDA TANGAN', 1, 0,'L',true);
    $this->Cell(180, 40, '', 1, 0, 'R',true);
    $this->Cell(180, 40, '', 1, 1, 'C',true);




	}

	public function printPDF () {

		if ($this->options['paper_size'] == "F4") {
			$a = 8.3 * 72; //1 inch = 72 pt
			$b = 13.0 * 72;
			$this->FPDF($this->options['orientation'], "pt", array($a,$b));
		} else {
			$this->FPDF($this->options['orientation'], "pt", $this->options['paper_size']);
		}

	    $this->SetAutoPageBreak(false);
	    $this->AliasNbPages();
	    $this->SetFont("helvetica", "B", 10);
	    //$this->AddPage();

	    $this->rptDetailData();

	    $this->Output($this->options['filename'],$this->options['destinationfile']);
  	}
     // Page header
    function Header()
    {
        // Logo
        // $this->Image('../../dist/img/logo.png',10,6,30);
        /*$this->SetFont("Arial", "", 6);
    	$this->Cell(480, 10, 'No. Form', 0, 0,'R');
        $this->Cell(60, 10, ': FW-19-QCF-01    ', 0, 1,'R');
        $this->Cell(482, 10, 'No. Revisi', 0, 0,'R');
        $this->Cell(60, 10, ': 05                          ', 0, 1,'R');
        $this->Cell(483, 10, 'Tgl. Terbit:', 0, 0,'R');
        $this->Cell(60, 10, ': 02 Desember 2016', 0, 1,'R');
    		$this->Ln(10);*/
    		$this->SetFont("Arial", "B", 9);
        if($_GET['idsub'] == 'TQ'){
    		$this->SetX($left); $this->Cell(0, 10, 'LAPORAN BULANAN STOCK TEST QUALITY MATERIAL', 0, 1,'C');
        }else if ($_GET['idsub'] == 'PACKING'){
        $this->SetX($left); $this->Cell(0, 10, 'LAPORAN BULANAN STOCK PACKING MATERIAL', 0, 1,'C');			
        }else if ($_GET['idsub'] == 'ATK'){
        $this->SetX($left); $this->Cell(0, 10, 'LAPORAN BULANAN STOCK ATK MATERIAL', 0, 1,'C');
		}
		$this->SetFont("Arial", "", 7);	
		$this->SetX($left); $this->Cell(0, 10, 'FW-19-QCF-01 / 06', 0, 1,'C');

        // Line break
        $h    = 13;
    		$left = 40;
    		$top  = 80;
        $this->Ln(10);
        $this->SetFillColor(200,200,200);
        $this->SetFont("Arial", "B", 7);
        //$this->Cell(0,0.7,"Di cetak pada : ".date("D-d/m/Y"),0,0,'L');
        //$this->Ln(10);
    		$left = $this->GetX();
    		$this->Cell(100,20,'Jenis Barang',1,0,'C');
        $this->SetX($left += 100);$this->Cell(80,20,'Ukuran',1,0,'C');
        $this->SetX($left += 80);$this->Cell(60,20,'Stock Awal',1,0,'C');
        $this->SetX($left += 60);$this->Cell(60,20,'Kedatangan',1,0,'C');
        $this->SetX($left += 60);$this->Cell(60,20,'Pengambilan',1,0,'C');
        $this->SetX($left += 60);$this->Cell(180,10,'STOCK AKHIR',1,0,'C');
        $this->Ln();
        $this->Cell(0,0,'',0,0,'L');
        $this->Cell(0,0,'',0,0,'L');
        $this->Cell(0,0,'',0,0,'L');
        $this->Cell(0,0,'',0,0,'L');
        $this->Cell(0,0,'',0,0,'L');
        $this->SetX($left += 0);$this->Cell(90,10,'Per Tanggal '.date("d/m/Y", strtotime($_GET['tgl2'])),1,0,'C');
        $this->SetX($left += 90);$this->Cell(90,10,'Per Tanggal '.date('d/m/Y'),1,1,'C');


    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
  	private $widths;
	  private $aligns;

	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}

	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}
  function SetTextReds($a)
	{
		//Set the array of color
		$this->reds=$a;
	}
  function SetTextGreens($a)
	{
		//Set the array of color
		$this->greens=$a;
	}
  function SetTextBlues($a)
	{
		//Set the array of color
		$this->blues=$a;
	}

	function Row($data)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=10*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
      $r=$this->reds[$i];
      $g=$this->greens[$i];
      $b=$this->blues[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Print the text
      $this->SetTextColor($r,$g,$b);
      //$this->SetTextColor(0,0,0);
			$this->MultiCell($w,10,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}

	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
} //end of class



#ambil data dari DB dan masukkan ke array
$data = array();
foreach($opname->tampilreport($idsub,$awal,$akhir) as $rowd){
	array_push($data, $rowd);
}

//pilihan
$options = array(
	'filename' => '', //nama file penyimpanan, kosongkan jika output ke browser
	'destinationfile' => '', //I=inline browser (default), F=local file, D=download
	'paper_size'=>'F4',	//paper size: F4, A3, A4, A5, Letter, Legal
	'orientation'=>'P' //orientation: P=portrait, L=landscape
);

$tabel = new FPDF_AutoWrapTable($data, $options);
$tabel->printPDF();