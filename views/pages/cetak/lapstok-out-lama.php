<?php
//mysql_connect("localhost","root","");
//mysql_select_db("invqc");
include_once('../../../config/koneksi.php');
include_once('../../../controllers/barangclass.php');
include_once('../../../controllers/barangkeluarclass.php');
require('../../../dist/pdf/fpdf.php');
// koneksi ke MySQL via method
$db       = new Database();
$db->connectMySQL();
$barang   = new Barang();
$barangout = new BarangKeluar();
$idsub    = $_GET['idsub'];

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
    $this->settitle('Stock Barang Keluar');

		$h = 13;
		$left = 40;
		$top = 80;

		$this->SetFont('Arial','',8);
		$this->SetWidths(array(20,50,40,140,80,70,40,40,70));
		$this->SetAligns(array('C','C','C','L','L','R','R','C','L'));
		$no = 1; $this->SetFillColor(255);
		foreach ($this->data as $baris) {
			$this->Row(
				array($no++,
        $baris['tanggal'],
				$baris['kode'],
				$baris['nama'],
				$baris['jenis'],
				$baris['harga'],
				$baris['jumlah'],
        $baris['satuan'],
        $baris['note']
			));
      $total=$total+$baris['jumlah'];
		}
		$this->SetFillColor(200,200,200);
    $this->SetFont("", "B", 8);
    $this->Cell(20,$h,'',1,0,'L',true);
    $this->Cell(50, $h, '', 1, 0, 'C',true);
    $this->Cell(40, $h, '', 1, 0, 'C',true);
    $this->Cell(140, $h, '', 1, 0, 'C',true);
		$this->Cell(80, $h, 'Total', 1, 0, 'L',true);
		$this->Cell(70, $h, '', 1, 0, 'C',true);
		$this->Cell(40, $h, number_format($total,'2','.',''), 1, 0, 'R',true);
    $this->Cell(40, $h, '', 1, 0, 'C',true);
    $this->Cell(70, $h, '', 1, 1, 'C',true);
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
    function Header(){

      $this->SetFont("", "B", 15);
      $this->MultiCell(0, 12, 'PT. Indo Taichen Textile Industry');
      $this->Cell(0, 1, " ", "B");
      $this->Ln(10);
      $this->SetFont("", "B", 12);
      if($_GET['idsub'] == 'TQ'){
      $this->SetX($left); $this->Cell(0, 10, 'LAPORAN DATA KELUAR BARANG TEST QUALITY', 0, 1,'C');
      }else if ($_GET['idsub'] == 'PACKING'){
      $this->SetX($left); $this->Cell(0, 10, 'LAPORAN DATA KELUAR BARANG PACKING', 0, 1,'C');
      }else if ($_GET['idsub'] == 'ATK'){
      $this->SetX($left); $this->Cell(0, 10, 'LAPORAN DATA KELUAR BARANG ATK', 0, 1,'C');
      }
      $this->Ln(10);

      $h = 13;
      $left = 40;
      $top = 80;
      #tableheader
      $this->SetFillColor(200,200,200);
      $this->SetFont("", "B", 10);
      $this->Cell(0,0.7,"Di cetak pada : ".date("D, Y-m-d"),0,0,'L');
      $this->Cell(0,0.7,"Periode : ".$_GET[tgl1]." s/d ".$_GET[tgl2],0,0,'R');
      $this->Ln(10);
      $left = $this->GetX();
      $this->Cell(20,$h,'NO',1,0,'L',true);
      $this->SetX($left += 20); $this->Cell(50, $h, 'Tanggal', 1, 0, 'C',true);
      $this->SetX($left += 50); $this->Cell(40, $h, 'Kode', 1, 0, 'C',true);
      $this->SetX($left += 40); $this->Cell(140, $h, 'Nama', 1, 0, 'C',true);
      $this->SetX($left += 140); $this->Cell(80, $h, 'Jenis', 1, 0, 'C',true);
      $this->SetX($left += 80); $this->Cell(70, $h, 'Harga', 1, 0, 'C',true);
      $this->SetX($left += 70); $this->Cell(40, $h, 'Jumlah', 1, 0, 'C',true);
      $this->SetX($left += 40); $this->Cell(40, $h, 'Jumlah', 1, 0, 'C',true);
      $this->SetX($left += 40); $this->Cell(70, $h, 'Note', 1, 1, 'C',true);
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
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Print the text
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
foreach($barangout->tampil_data_out($idsub) as $rowd){
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
?>
